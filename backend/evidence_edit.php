<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

$evidence_id = $_POST['evidence_id'];
$description = $_POST['description'];


// Get existing evidence file information

$sql = "SELECT file_name, file_path
        FROM evidence
        WHERE evidence_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $evidence_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("Evidence record not found.");
}

$old_file = $result->fetch_assoc();

$stmt->close();


// Default: keep existing file

$file_name = $old_file['file_name'];
$file_path = $old_file['file_path'];
$file_type = null;


// Check whether a new file was uploaded

if (isset($_FILES['evidence_file']) &&
    $_FILES['evidence_file']['error'] !== UPLOAD_ERR_NO_FILE) {

    $file = $_FILES['evidence_file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        die("Error uploading new file.");
    }

    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_type = $file['type'];

    $unique_name = uniqid() . "_" . basename($file_name);

    $upload_path = __DIR__ . "/../uploads/evidence/" . $unique_name;

    if (!move_uploaded_file($file_tmp, $upload_path)) {
        die("Failed to save new evidence file.");
    }

    $file_path = "uploads/evidence/" . $unique_name;
}


// Update database

if ($file_type !== null) {

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

} else {

    $sql = "UPDATE evidence
            SET description = ?
            WHERE evidence_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "si",
        $description,
        $evidence_id
    );
}


if ($stmt->execute()) {

    header("Location: ../evidence_records.php");
    exit();

} else {

    echo "Error updating evidence: " . $stmt->error;

}


$stmt->close();
$conn->close();

?>