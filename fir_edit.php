<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

if (!isset($_GET['id'])) {
    header("Location: fir_records.php");
    exit();
}

$fir_id = $_GET['id'];

$sql = "SELECT * FROM fir WHERE fir_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fir_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("FIR not found.");
}

$fir = $result->fetch_assoc();


$crime_sql = "SELECT crime_type_id, crime_name
              FROM crime_type
              ORDER BY crime_name";

$crime_result = $conn->query($crime_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>Edit FIR</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Edit FIR</h2>

    <form action="backend/fir_edit.php" method="POST">

        <input
            type="hidden"
            name="fir_id"
            value="<?php echo htmlspecialchars($fir['fir_id']); ?>"
        >

        <label>FIR Number</label><br>

        <input
            type="text"
            name="fir_number"
            value="<?php echo htmlspecialchars($fir['fir_number']); ?>"
            required
        >

        <br><br>


        <label>Crime Type</label><br>

        <select name="crime_type_id" required>

            <?php while ($crime = $crime_result->fetch_assoc()) { ?>

                <option
                    value="<?php echo $crime['crime_type_id']; ?>"
                    <?php
                    if ($crime['crime_type_id'] == $fir['crime_type_id']) {
                        echo "selected";
                    }
                    ?>
                >
                    <?php echo htmlspecialchars($crime['crime_name']); ?>
                </option>

            <?php } ?>

        </select>

        <br><br>


        <label>Incident Date</label><br>

        <input
            type="date"
            name="incident_date"
            value="<?php echo htmlspecialchars($fir['incident_date']); ?>"
            required
        >

        <br><br>


        <label>Location</label><br>

        <input
            type="text"
            name="location"
            value="<?php echo htmlspecialchars($fir['location']); ?>"
            required
        >

        <br><br>


        <label>Description</label><br>

        <textarea
            name="description"
            rows="5"
            required><?php echo htmlspecialchars($fir['description']); ?></textarea>

        <br><br>


        <label>Status</label><br>

        <select name="status" required>

            <option value="Pending"
                <?php if ($fir['status'] == 'Pending') echo 'selected'; ?>>
                Pending
            </option>

            <option value="Under Investigation"
                <?php if ($fir['status'] == 'Under Investigation') echo 'selected'; ?>>
                Under Investigation
            </option>

            <option value="Solved"
                <?php if ($fir['status'] == 'Solved') echo 'selected'; ?>>
                Solved
            </option>

            <option value="Closed"
                <?php if ($fir['status'] == 'Closed') echo 'selected'; ?>>
                Closed
            </option>

        </select>

        <br><br>


        <button type="submit">Update FIR</button>

    </form>

    <br>

    <a href="fir_records.php">Back to FIR Records</a>

</body>

</html>

<?php
$stmt->close();
$conn->close();
?>