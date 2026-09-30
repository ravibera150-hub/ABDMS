<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

$fir_id = $_POST['fir_id'];
$fir_number = $_POST['fir_number'];
$crime_type_id = $_POST['crime_type_id'];
$incident_date = $_POST['incident_date'];
$location = $_POST['location'];
$description = $_POST['description'];
$status = $_POST['status'];

$sql = "UPDATE fir
        SET fir_number = ?,
            crime_type_id = ?,
            incident_date = ?,
            location = ?,
            description = ?,
            status = ?
        WHERE fir_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sissssi",
    $fir_number,
    $crime_type_id,
    $incident_date,
    $location,
    $description,
    $status,
    $fir_id
);

if ($stmt->execute()) {

    header("Location: ../fir_records.php");
    exit();

} else {

    echo "Error updating FIR: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>