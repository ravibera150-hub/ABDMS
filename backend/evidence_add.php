<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../evidence_add.php");
    exit();
}

$fir_id = $_POST['fir_id'] ?? '';
$description = trim($_POST['description'] ?? '');

if ($fir_id === '') {
    die("FIR is required.");
}

if (!filter_var($fir_id, FILTER_VALIDATE_INT)) {
    die("Invalid FIR ID.");
}

if (!isset($_FILES['evidence_file'])) {
    die("No file uploaded.");
}

$file = $_FILES['evidence_file'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    die("Error uploading file.");
}

if ($file['size'] <= 0) {
    die("Uploaded file is empty.");
}


/*
 * Check whether FIR exists
 */
$sql = "SELECT fir_id
        FROM fir
        WHERE fir_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fir_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    $conn->close();
    die("FIR record not found.");
}

$stmt->close();


/*
 * Validate file type
 */
$allowed_types = [
    'image/jpeg',
    'image/png',
    'image/gif',
    'application/pdf',
    'text/plain'
];

$finfo = new finfo(FILEINFO_MIME_TYPE);
$detected_type = $finfo->file($file['tmp_name']);

if (!in_array($detected_type, $allowed_types, true)) {
    $conn->close();
    die("File type is not allowed.");
}


/*
 * Maximum file size: 5 MB
 */
$max_size = 5 * 1024 * 1024;

if ($file['size'] > $max_size) {
    $conn->close();
    die("File size must not exceed 5 MB.");
}


/*
 * Generate safe unique file name
 */
$original_name = basename($file['name']);

$extension = strtolower(
    pathinfo($original_name, PATHINFO_EXTENSION)
);

$unique_name = uniqid('', true) . "." . $extension;

$upload_directory = __DIR__ . "/../uploads/evidence/";

if (!is_dir($upload_directory)) {
    mkdir($upload_directory, 0755, true);
}

$upload_path = $upload_directory . $unique_name;


/*
 * Move uploaded file
 */
if (!move_uploaded_file(
    $file['tmp_name'],
    $upload_path
)) {
    $conn->close();
    die("Failed to save uploaded file.");
}


/*
 * Store evidence information
 */
$db_file_path = "uploads/evidence/" . $unique_name;

$sql = "INSERT INTO evidence
        (fir_id, file_name, file_path, file_type, description)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "issss",
    $fir_id,
    $original_name,
    $db_file_path,
    $detected_type,
    $description
);

if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    header("Location: ../evidence_records.php");
    exit();

} else {

    /*
     * Database insert failed.
     * Remove uploaded file so there is no orphan file.
     */
    if (file_exists($upload_path)) {
        unlink($upload_path);
    }

    echo "Error saving evidence: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>