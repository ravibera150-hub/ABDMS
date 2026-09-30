<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$fir_sql = "SELECT fir_id, fir_number FROM fir ORDER BY fir_id DESC";
$fir_result = $conn->query($fir_sql);

$criminal_sql = "SELECT criminal_id, name FROM criminal ORDER BY name";
$criminal_result = $conn->query($criminal_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Link Criminal to FIR</title>
</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Link Criminal to FIR</h2>

    <form action="backend/link_criminal.php" method="POST">

        <label>Select FIR</label><br>

        <select name="fir_id" required>

            <option value="">-- Select FIR --</option>

            <?php while ($fir = $fir_result->fetch_assoc()) { ?>

                <option value="<?php echo $fir['fir_id']; ?>">
                    <?php echo htmlspecialchars($fir['fir_number']); ?>
                </option>

            <?php } ?>

        </select>

        <br><br>

        <label>Select Criminal</label><br>

        <select name="criminal_id" required>

            <option value="">-- Select Criminal --</option>

            <?php while ($criminal = $criminal_result->fetch_assoc()) { ?>

                <option value="<?php echo $criminal['criminal_id']; ?>">
                    <?php echo htmlspecialchars($criminal['name']); ?>
                </option>

            <?php } ?>

        </select>

        <br><br>

        <button type="submit">Link Criminal</button>

    </form>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>

</html>

<?php

$conn->close();

?>