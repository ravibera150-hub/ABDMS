<?php

date_default_timezone_set('Asia/Kolkata');

session_start();

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../forgot_password.php");
    exit();
}

$email = trim($_POST['email'] ?? '');

if ($email === '') {
    die("Email address is required.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email address.");
}

$sql = "SELECT police_id
        FROM police
        WHERE email = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    $conn->close();

    die("No account found with this email address.");
}

$police = $result->fetch_assoc();

$stmt->close();

$police_id = $police['police_id'];

$token = bin2hex(random_bytes(32));

$expires_at = date(
    "Y-m-d H:i:s",
    time() + 1800
);

$sql = "DELETE FROM password_reset
        WHERE police_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $police_id);
$stmt->execute();
$stmt->close();

$sql = "INSERT INTO password_reset
        (police_id, token, expires_at)
        VALUES (?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "iss",
    $police_id,
    $token,
    $expires_at
);

if (!$stmt->execute()) {
    die("Unable to generate reset link.");
}

$stmt->close();

$reset_link =
    "http://localhost/ADBMS/reset_password.php?token=" .
    urlencode($token);

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Password Reset Link</title>

    <link rel="stylesheet" href="../auth.css">

</head>

<body>

<header class="page-header">

    <h1>
        Crime Record Management System
    </h1>

</header>


<main class="container">

    <div class="title-section">

        <h2>Password Reset</h2>

        <p>
            Your password reset request was successfully created.
        </p>

    </div>


    <div class="form-card">

        <div class="success-box">

            <strong>
                Reset link generated successfully.
            </strong>

            <p>
                For local XAMPP testing, use the button below.
                The link expires in 30 minutes.
            </p>

        </div>


        <a
            class="reset-link"
            href="<?php echo htmlspecialchars($reset_link); ?>"
        >
            Open Reset Password
        </a>


        <a
            href="../index.html"
            class="back-link"
        >
            ← Back to Login
        </a>

    </div>

</main>

</body>

</html>