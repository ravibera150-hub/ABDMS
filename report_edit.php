<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

if (!isset($_GET['id'])) {
    header("Location: report_records.php");
    exit();
}

$report_id = $_GET['id'];

$sql = "SELECT *
        FROM report
        WHERE report_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $report_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("Report not found.");
}

$report = $result->fetch_assoc();

$fir_sql = "SELECT fir_id, fir_number
            FROM fir
            ORDER BY fir_id DESC";

$fir_result = $conn->query($fir_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Report</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Edit Report</h2>

    <form action="backend/report_edit.php" method="POST">

        <input
            type="hidden"
            name="report_id"
            value="<?php echo htmlspecialchars($report['report_id']); ?>"
        >

        <label>Select FIR</label><br>

        <select name="fir_id" required>

            <?php while ($fir = $fir_result->fetch_assoc()) { ?>

                <option
                    value="<?php echo $fir['fir_id']; ?>"
                    <?php
                    if ($fir['fir_id'] == $report['fir_id']) {
                        echo "selected";
                    }
                    ?>
                >
                    <?php echo htmlspecialchars($fir['fir_number']); ?>
                </option>

            <?php } ?>

        </select>

        <br><br>


        <label>Report Date</label><br>

        <input
            type="date"
            name="report_date"
            value="<?php echo htmlspecialchars($report['report_date']); ?>"
            required
        >

        <br><br>


        <label>Report Type</label><br>

        <select name="report_type" required>

            <option value="Investigation Report"
                <?php if ($report['report_type'] == 'Investigation Report') echo 'selected'; ?>>
                Investigation Report
            </option>

            <option value="Evidence Report"
                <?php if ($report['report_type'] == 'Evidence Report') echo 'selected'; ?>>
                Evidence Report
            </option>

            <option value="Progress Report"
                <?php if ($report['report_type'] == 'Progress Report') echo 'selected'; ?>>
                Progress Report
            </option>

            <option value="Final Report"
                <?php if ($report['report_type'] == 'Final Report') echo 'selected'; ?>>
                Final Report
            </option>

        </select>

        <br><br>


        <label>Remarks</label><br>

        <textarea
            name="remarks"
            rows="5"><?php echo htmlspecialchars($report['remarks'] ?? ''); ?></textarea>

        <br><br>

        <button type="submit">Update Report</button>

    </form>

    <br>

    <a href="report_records.php">Back to Reports</a>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>