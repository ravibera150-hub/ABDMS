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

    <title>Add Criminal</title>
</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Add Criminal Record</h2>

    <form action="backend/criminal_register.php" method="POST">

        <label>Full Name</label><br>
        <input type="text" name="name" required>

        <br><br>

        <label>Gender</label><br>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>

        <br><br>

        <label>Date of Birth</label><br>
        <input type="date" name="date_of_birth">

        <br><br>

        <label>Address</label><br>
        <textarea name="address" rows="3" required></textarea>

        <br><br>

        <label>Mobile</label><br>
        <input type="tel" name="mobile">

        <br><br>

        <label>Identification Mark</label><br>
        <textarea name="identification_mark" rows="3"></textarea>

        <br><br>

        <button type="submit">Add Criminal</button>

    </form>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>

</html>