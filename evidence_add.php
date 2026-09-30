<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$sql = "SELECT fir_id, fir_number FROM fir ORDER BY fir_id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Evidence</title>
</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Add Evidence</h2>

    <form action="backend/evidence_add.php"
          method="POST"
          enctype="multipart/form-data">

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


        <label>Select Evidence File</label><br>

        <input type="file" name="evidence_file" required>

        <br><br>


        <label>Description</label><br>

        <textarea
            name="description"
            rows="5"
            placeholder="Enter details about the evidence"></textarea>

        <br><br>


        <button type="submit">Upload Evidence</button>

    </form>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>

</html>

<?php
$conn->close();
?>