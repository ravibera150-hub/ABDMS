<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Crime Record Management System</title>
</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h2>

    <p>
        Police ID:
        <?php echo htmlspecialchars($_SESSION['police_id']); ?>
    </p>

    <p>
        Email:
        <?php echo htmlspecialchars($_SESSION['email']); ?>
    </p>

    <a href="logout.php">Logout</a>

</body>

</html>