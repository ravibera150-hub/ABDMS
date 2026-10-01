<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if (!isset($_GET['id'])) {
    header("Location: ../report_records.php");
    exit();
}

$report_id = $_GET['id'];

$sql = "DELETE FROM report
        WHERE report_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $report_id
);

if ($stmt->execute()) {

    header("Location: ../report_records.php");
    exit();

} else {

    echo "Error deleting report: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>