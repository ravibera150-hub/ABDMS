<?php

session_start();


if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}


require_once "db.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../fir_register.php");
    exit();
}


/* Get form data */

$fir_number = trim($_POST['fir_number'] ?? '');
$crime_type_id = $_POST['crime_type_id'] ?? '';
$incident_date = $_POST['incident_date'] ?? '';
$location = trim($_POST['location'] ?? '');
$description = trim($_POST['description'] ?? '');

$police_id = $_SESSION['police_id'];


/* Validate required fields */

if (
    $fir_number === '' ||
    $crime_type_id === '' ||
    $incident_date === '' ||
    $location === '' ||
    $description === ''
) {
    die("All FIR fields are required.");
}


/* Validate numeric IDs */

if (!filter_var($crime_type_id, FILTER_VALIDATE_INT)) {
    die("Invalid crime type.");
}


/* Insert FIR */

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

    $stmt->close();
    $conn->close();

    header("Location: ../fir_records.php");
    exit();

} else {

    echo "Error registering FIR: " . $stmt->error;

}


$stmt->close();
$conn->close();

?>