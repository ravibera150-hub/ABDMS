<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";


$fir_id = $_POST['fir_id'];
$report_date = $_POST['report_date'];
$report_type = $_POST['report_type'];
$remarks = $_POST['remarks'];


$sql = "INSERT INTO report
        (fir_id, report_date, report_type, remarks)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "isss",
    $fir_id,
    $report_date,
    $report_type,
    $remarks
);


if ($stmt->execute()) {

    echo "Report added successfully!";

} else {

    echo "Error adding report: " . $stmt->error;

}


$stmt->close();
$conn->close();

?>