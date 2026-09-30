<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$sql = "SELECT crime_type_id, crime_name FROM crime_type ORDER BY crime_name";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register FIR</title>
</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Register FIR</h2>

    <form action="backend/fir_register.php" method="POST">

        <label>FIR Number</label><br>
        <input type="text" name="fir_number" required>
        <br><br>

        <label>Crime Type</label><br>

        <select name="crime_type_id" required>

            <option value="">-- Select Crime Type --</option>

            <?php while ($row = $result->fetch_assoc()) { ?>

                <option value="<?php echo $row['crime_type_id']; ?>">
                    <?php echo htmlspecialchars($row['crime_name']); ?>
                </option>

            <?php } ?>

        </select>

        <br><br>

        <label>Incident Date</label><br>
        <input type="date" name="incident_date" required>
        <br><br>

        <label>Location</label><br>
        <input type="text" name="location" required>
        <br><br>

        <label>Description</label><br>
        <textarea name="description" rows="5" required></textarea>
        <br><br>

        <button type="submit">Register FIR</button>

    </form>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>

</html>