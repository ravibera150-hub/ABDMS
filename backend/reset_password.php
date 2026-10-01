<?php

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../index.html");
    exit();
}

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if ($token === '') {
    die("Invalid reset token.");
}

if ($password === '' || $confirm_password === '') {
    die("Password fields are required.");
}

if (strlen($password) < 6) {
    die("Password must contain at least 6 characters.");
}

if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

$sql = "SELECT reset_id, police_id
        FROM password_reset
        WHERE token = ?
        AND expires_at > NOW()";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $token);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("This reset link is invalid or has expired.");
}

$reset = $result->fetch_assoc();

$stmt->close();

$hashed_password = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$sql = "UPDATE police
        SET password = ?
        WHERE police_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "si",
    $hashed_password,
    $reset['police_id']
);

if (!$stmt->execute()) {
    die("Unable to reset password.");
}

$stmt->close();

$sql = "DELETE FROM password_reset
        WHERE reset_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $reset['reset_id']
);

$stmt->execute();

$stmt->close();
$conn->close();

header("Location: ../index.html");
exit();

?>