<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

$fir_number = $_POST['fir_number'];
$crime_type_id = $_POST['crime_type_id'];
$incident_date = $_POST['incident_date'];
$location = $_POST['location'];
$description = $_POST['description'];

$police_id = $_SESSION['police_id'];

$sql = "INSERT INTO fir
        (fir_number, crime_type_id, police_id, incident_date, location, description)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "siisss",
    $fir_number,
    $crime_type_id,
    $police_id,
    $incident_date,
    $location,
    $description
);

if ($stmt->execute()) {

    echo "FIR registered successfully!";

} else {

    echo "Error registering FIR: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>