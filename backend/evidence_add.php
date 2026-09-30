<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";


$fir_id = $_POST['fir_id'];
$description = $_POST['description'];


if (!isset($_FILES['evidence_file'])) {
    die("No file uploaded.");
}


$file = $_FILES['evidence_file'];

$file_name = $file['name'];
$file_tmp = $file['tmp_name'];
$file_type = $file['type'];
$file_error = $file['error'];


if ($file_error !== UPLOAD_ERR_OK) {
    die("Error uploading file.");
}


/*
 * Create a unique file name
 * so that two files with the same original
 * name do not overwrite each other.
 */

$unique_name = uniqid() . "_" . basename($file_name);

$upload_path = __DIR__ . "/../uploads/evidence/" . $unique_name;


/*
 * Move uploaded file from temporary location
 * to our project's evidence folder.
 */

if (!move_uploaded_file($file_tmp, $upload_path)) {
    die("Failed to save uploaded file.");
}


/*
 * Store file information in database.
 */

$db_file_path = "uploads/evidence/" . $unique_name;

$sql = "INSERT INTO evidence
        (fir_id, file_name, file_path, file_type, description)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "issss",
    $fir_id,
    $file_name,
    $db_file_path,
    $file_type,
    $description
);


if ($stmt->execute()) {

    echo "Evidence uploaded successfully!";

} else {

    echo "Error saving evidence: " . $stmt->error;

}


$stmt->close();
$conn->close();

?>