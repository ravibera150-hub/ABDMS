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

    <hr>

    <h3>Police Operations</h3>

    <a href="fir_register.php">Register FIR</a>

    <br><br>

    <a href="fir_records.php">View FIR Records</a>

    <br><br>

    <a href="criminal_register.php">Add Criminal</a>

    <br><br>

    <a href="link_criminal.php">Link Criminal to FIR</a>

    <br><br>

    <a href="criminal_records.php">View Criminal Records</a>

    <br><br>

    <a href="evidence_add.php">Add Evidence</a>

    <br><br>

    <a href="evidence_records.php">View Evidence Records</a>

    <br><br>

    <a href="logout.php">Logout</a>

</body>

</html>