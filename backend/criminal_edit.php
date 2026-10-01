<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../criminal_records.php");
    exit();
}

$criminal_id = $_POST['criminal_id'] ?? '';
$name = trim($_POST['name'] ?? '');
$gender = $_POST['gender'] ?? '';
$date_of_birth = $_POST['date_of_birth'] ?? '';
$address = trim($_POST['address'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$identification_mark = trim($_POST['identification_mark'] ?? '');

if (
    $criminal_id === '' ||
    $name === '' ||
    $gender === '' ||
    $address === ''
) {
    die("Name, gender and address are required.");
}

if (!filter_var($criminal_id, FILTER_VALIDATE_INT)) {
    die("Invalid criminal ID.");
}

$allowed_gender = [
    "Male",
    "Female",
    "Other"
];

if (!in_array($gender, $allowed_gender, true)) {
    die("Invalid gender.");
}

if (
    $mobile !== '' &&
    !preg_match('/^[0-9]{10}$/', $mobile)
) {
    die("Mobile number must contain exactly 10 digits.");
}

$sql = "UPDATE criminal
        SET name = ?,
            gender = ?,
            date_of_birth = ?,
            address = ?,
            mobile = ?,
            identification_mark = ?
        WHERE criminal_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssssi",
    $name,
    $gender,
    $date_of_birth,
    $address,
    $mobile,
    $identification_mark,
    $criminal_id
);

if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    header("Location: ../criminal_records.php");
    exit();

} else {

    echo "Error updating criminal: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>