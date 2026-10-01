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

if (!filter_var($report_id, FILTER_VALIDATE_INT)) {
    die("Invalid report ID.");
}


/*
 * Delete report
 */
$sql = "DELETE FROM report
        WHERE report_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $report_id
);

$stmt->execute();

if ($stmt->affected_rows !== 1) {

    $stmt->close();
    $conn->close();

    die("Report record not found.");
}

$stmt->close();
$conn->close();

header("Location: ../report_records.php");
exit();

?>