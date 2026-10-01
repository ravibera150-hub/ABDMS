<?php

session_start();

require_once "db.php";


if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.html");
    exit();
}


$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';


if ($email === '' || $password === '') {
    die("Email and password are required.");
}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}


$sql = "SELECT *
        FROM police
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 1) {

    $police = $result->fetch_assoc();


    if (password_verify($password, $police['password'])) {

        session_regenerate_id(true);

        $_SESSION['police_id'] = $police['police_id'];
        $_SESSION['full_name'] = $police['full_name'];
        $_SESSION['email'] = $police['email'];

        header("Location: ../dashboard.php");
        exit();

    } else {

        die("Invalid email or password.");

    }

} else {

    die("Invalid email or password.");

}


$stmt->close();
$conn->close();

?>