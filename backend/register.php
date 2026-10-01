<?php

session_start();

require_once "db.php";


/* Check request method */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../register.php");
    exit();
}


/* Get form data */

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$rank = trim($_POST['rank'] ?? '');
$station = trim($_POST['station'] ?? '');
$mobile = trim($_POST['mobile'] ?? '');


/* Validate required fields */

if (
    $full_name === '' ||
    $email === '' ||
    $password === '' ||
    $confirm_password === '' ||
    $rank === '' ||
    $station === '' ||
    $mobile === ''
) {
    die("All fields are required.");
}


/* Validate email */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}


/* Validate password */

if (strlen($password) < 6) {
    die("Password must contain at least 6 characters.");
}


/* Check password confirmation */

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}


/* Validate mobile */

if (!preg_match('/^[0-9]{10}$/', $mobile)) {
    die("Mobile number must contain exactly 10 digits.");
}


/* Check duplicate email */

$sql = "SELECT police_id
        FROM police
        WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows > 0) {

    $stmt->close();

    $conn->close();

    die("An account with this email already exists.");

}


$stmt->close();


/* Hash password */

$hashed_password = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/* Insert police officer */

$sql = "INSERT INTO police
        (full_name, email, password, rank, station, mobile)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssss",
    $full_name,
    $email,
    $hashed_password,
    $rank,
    $station,
    $mobile
);


/* Execute registration */

if ($stmt->execute()) {

    $stmt->close();

    $conn->close();

    header("Location: ../index.html");

    exit();

} else {

    echo "Registration failed: " . $stmt->error;

}


$stmt->close();

$conn->close();

?>