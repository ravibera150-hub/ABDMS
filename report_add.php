<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$sql = "SELECT fir_id, fir_number
        FROM fir
        ORDER BY fir_id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Report</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Add Report</h2>

    <form action="backend/report_add.php" method="POST">

        <label>Select FIR</label><br>

        <select name="fir_id" required>

            <option value="">-- Select FIR --</option>

            <?php while ($row = $result->fetch_assoc()) { ?>

                <option value="<?php echo $row['fir_id']; ?>">
                    <?php echo htmlspecialchars($row['fir_number']); ?>
                </option>

            <?php } ?>

        </select>

        <br><br>


        <label>Report Date</label><br>

        <input type="date" name="report_date" required>

        <br><br>


        <label>Report Type</label><br>

        <select name="report_type" required>

            <option value="">-- Select Report Type --</option>

            <option value="Investigation Report">
                Investigation Report
            </option>

            <option value="Evidence Report">
                Evidence Report
            </option>

            <option value="Progress Report">
                Progress Report
            </option>

            <option value="Final Report">
                Final Report
            </option>

        </select>

        <br><br>


        <label>Remarks</label><br>

        <textarea
            name="remarks"
            rows="5"
            placeholder="Enter report details or remarks"></textarea>

        <br><br>


        <button type="submit">Add Report</button>

    </form>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>

</html>

<?php
$conn->close();
?>