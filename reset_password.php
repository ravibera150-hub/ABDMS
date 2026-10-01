<?php

require_once "backend/db.php";

$token = $_GET['token'] ?? '';

if ($token === '') {
    die("Invalid reset link.");
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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reset Password</title>

    <link rel="stylesheet" href="auth.css">

</head>

<body>

<header class="page-header">

    <h1>
        Crime Record Management System
    </h1>

</header>


<main class="container">

    <div class="title-section">

        <h2>Reset Password</h2>

        <p>
            Create a new password for your police account.
        </p>

    </div>


    <div class="form-card">

        <form
            action="backend/reset_password.php"
            method="POST"
        >

            <input
                type="hidden"
                name="token"
                value="<?php echo htmlspecialchars($token); ?>"
            >


            <div class="form-group">

                <label for="password">
                    New Password
                </label>

                <input
                    class="form-control"
                    type="password"
                    id="password"
                    name="password"
                    minlength="6"
                    placeholder="Enter new password"
                    required
                >

            </div>


            <div class="form-group">

                <label for="confirm_password">
                    Confirm Password
                </label>

                <input
                    class="form-control"
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    minlength="6"
                    placeholder="Re-enter new password"
                    required
                >

            </div>


            <button
                class="primary-btn"
                type="submit"
            >
                Reset Password
            </button>

        </form>


        <a
            href="index.html"
            class="back-link"
        >
            ← Back to Login
        </a>

    </div>

</main>

</body>

</html>