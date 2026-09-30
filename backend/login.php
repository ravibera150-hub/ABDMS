<?php

session_start();

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

    if (password_verify($password, $police['password'])) {

        // Store officer information in session
        $_SESSION['police_id'] = $police['police_id'];
        $_SESSION['full_name'] = $police['full_name'];
        $_SESSION['email'] = $police['email'];

        // Go to dashboard
        header("Location: ../dashboard.php");
        exit();

    } else {

        echo "Invalid password!";

    }

} else {

    echo "Email not found!";

}

$stmt->close();
$conn->close();

?>