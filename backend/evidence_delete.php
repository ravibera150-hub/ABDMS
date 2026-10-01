<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if (!isset($_GET['id'])) {
    header("Location: ../evidence_records.php");
    exit();
}

$evidence_id = $_GET['id'];


// Get file path before deleting database record

$sql = "SELECT file_path
        FROM evidence
        WHERE evidence_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $evidence_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("Evidence record not found.");
}

$evidence = $result->fetch_assoc();

$stmt->close();


// Delete database record

$sql = "DELETE FROM evidence
        WHERE evidence_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $evidence_id
);

if ($stmt->execute()) {

    // Delete physical file

    $file_path = "../" . $evidence['file_path'];

    if (file_exists($file_path)) {
        unlink($file_path);
    }

    header("Location: ../evidence_records.php");
    exit();

} else {

    echo "Error deleting evidence: " . $stmt->error;

}


$stmt->close();
$conn->close();

?>