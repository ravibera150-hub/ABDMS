<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../evidence_records.php");
    exit();
}

$evidence_id = $_POST['evidence_id'] ?? '';
$description = trim($_POST['description'] ?? '');

if ($evidence_id === '') {
    die("Evidence ID is required.");
}

if (!filter_var($evidence_id, FILTER_VALIDATE_INT)) {
    die("Invalid evidence ID.");
}


/*
 * Get existing evidence
 */
$sql = "SELECT file_name, file_path, file_type
        FROM evidence
        WHERE evidence_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $evidence_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    $conn->close();
    die("Evidence record not found.");
}

$old_file = $result->fetch_assoc();

$stmt->close();


$old_file_name = $old_file['file_name'];
$old_file_path = $old_file['file_path'];
$old_file_type = $old_file['file_type'];

$file_name = $old_file_name;
$file_path = $old_file_path;
$file_type = $old_file_type;

$new_file_path = null;


/*
 * Check whether a new file was uploaded
 */
if (
    isset($_FILES['evidence_file']) &&
    $_FILES['evidence_file']['error'] !== UPLOAD_ERR_NO_FILE
) {

    $file = $_FILES['evidence_file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading new file.");
    }

    if ($file['size'] <= 0) {
        die("Uploaded file is empty.");
    }


    /*
     * Maximum file size: 5 MB
     */
    $max_size = 5 * 1024 * 1024;

    if ($file['size'] > $max_size) {
        die("File size must not exceed 5 MB.");
    }


    /*
     * Detect actual MIME type
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
        die("File type is not allowed.");
    }


    /*
     * Generate safe unique name
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
     * Save new file
     */
    if (!move_uploaded_file(
        $file['tmp_name'],
        $upload_path
    )) {
        die("Failed to save new evidence file.");
    }

    $file_name = $original_name;
    $file_path = "uploads/evidence/" . $unique_name;
    $file_type = $detected_type;

    $new_file_path = $upload_path;
}


/*
 * Update database
 */
$sql = "UPDATE evidence
        SET file_name = ?,
            file_path = ?,
            file_type = ?,
            description = ?
        WHERE evidence_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssi",
    $file_name,
    $file_path,
    $file_type,
    $description,
    $evidence_id
);

if ($stmt->execute()) {

    /*
     * Database update succeeded.
     * Now remove old physical file if a new one was uploaded.
     */
    if (
        $new_file_path !== null &&
        !empty($old_file_path)
    ) {

        $old_physical_path =
            __DIR__ . "/../" . $old_file_path;

        if (
            file_exists($old_physical_path) &&
            $old_physical_path !== $new_file_path
        ) {
            unlink($old_physical_path);
        }
    }

    $stmt->close();
    $conn->close();

    header("Location: ../evidence_records.php");
    exit();

} else {

    /*
     * Database update failed.
     * Remove newly uploaded file.
     */
    if (
        $new_file_path !== null &&
        file_exists($new_file_path)
    ) {
        unlink($new_file_path);
    }

    echo "Error updating evidence: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>