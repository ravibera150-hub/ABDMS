<?php

require_once "db.php";

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM police WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 1) {

    $police = $result->fetch_assoc();

    if ($password === $police['password']) {
        echo "Login successful!";
    } else {
        echo "Invalid password!";
    }

} else {

    echo "Email not found!";

}

$stmt->close();
$conn->close();

?>