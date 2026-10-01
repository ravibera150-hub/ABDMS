<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../criminal_register.php");
    exit();
}


/* Get form data */

$name = trim($_POST['name'] ?? '');
$gender = $_POST['gender'] ?? '';
$date_of_birth = $_POST['date_of_birth'] ?? '';
$address = trim($_POST['address'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');
$identification_mark = trim($_POST['identification_mark'] ?? '');


/* Validate required fields */

if (
    $name === '' ||
    $gender === '' ||
    $address === ''
) {
    die("Name, gender and address are required.");
}


/* Validate gender */

$allowed_gender = [
    "Male",
    "Female",
    "Other"
];

if (!in_array($gender, $allowed_gender, true)) {
    die("Invalid gender.");
}


/* Validate mobile if provided */

if (
    $mobile !== '' &&
    !preg_match('/^[0-9]{10}$/', $mobile)
) {
    die("Mobile number must contain exactly 10 digits.");
}


/* Insert criminal */

$sql = "INSERT INTO criminal
        (name, gender, date_of_birth, address, mobile, identification_mark)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssss",
    $name,
    $gender,
    $date_of_birth,
    $address,
    $mobile,
    $identification_mark
);


if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    header("Location: ../criminal_records.php");
    exit();

} else {

    echo "Error adding criminal: " . $stmt->error;

}


$stmt->close();
$conn->close();

?>