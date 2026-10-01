<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../fir_records.php");
    exit();
}


/* Get form data */

$fir_id = $_POST['fir_id'] ?? '';
$fir_number = trim($_POST['fir_number'] ?? '');
$crime_type_id = $_POST['crime_type_id'] ?? '';
$incident_date = $_POST['incident_date'] ?? '';
$location = trim($_POST['location'] ?? '');
$description = trim($_POST['description'] ?? '');
$status = $_POST['status'] ?? '';


/* Validate required fields */

if (
    $fir_id === '' ||
    $fir_number === '' ||
    $crime_type_id === '' ||
    $incident_date === '' ||
    $location === '' ||
    $description === '' ||
    $status === ''
) {
    die("All FIR fields are required.");
}


/* Validate IDs */

if (!filter_var($fir_id, FILTER_VALIDATE_INT)) {
    die("Invalid FIR ID.");
}

if (!filter_var($crime_type_id, FILTER_VALIDATE_INT)) {
    die("Invalid crime type.");
}


/* Allowed FIR statuses */

$allowed_statuses = [
    "Pending",
    "Under Investigation",
    "Solved",
    "Closed"
];

if (!in_array($status, $allowed_statuses, true)) {
    die("Invalid FIR status.");
}


/* Update FIR */

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

    $stmt->close();
    $conn->close();

    header("Location: ../fir_records.php");
    exit();

} else {

    echo "Error updating FIR: " . $stmt->error;

}


$stmt->close();
$conn->close();

?>