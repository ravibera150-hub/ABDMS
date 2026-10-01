<?php

session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password</title>

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

        <h2>Forgot Password</h2>

        <p>
            Enter your registered police email address to reset your password.
        </p>

    </div>


    <div class="form-card">

        <form
            action="backend/forgot_password.php"
            method="POST"
        >

            <div class="form-group">

                <label for="email">
                    Email ID
                </label>

                <input
                    class="form-control"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter registered email"
                    required
                >

            </div>


            <button
                class="primary-btn"
                type="submit"
            >
                Generate Reset Link
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