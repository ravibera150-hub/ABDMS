<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

$report_id = $_POST['report_id'];
$fir_id = $_POST['fir_id'];
$report_date = $_POST['report_date'];
$report_type = $_POST['report_type'];
$remarks = $_POST['remarks'];

$sql = "UPDATE report
        SET fir_id = ?,
            report_date = ?,
            report_type = ?,
            remarks = ?
        WHERE report_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "isssi",
    $fir_id,
    $report_date,
    $report_type,
    $remarks,
    $report_id
);

if ($stmt->execute()) {

    header("Location: ../report_records.php");
    exit();

} else {

    echo "Error updating report: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>