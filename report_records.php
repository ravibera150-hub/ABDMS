<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$sql = "SELECT
            report.report_id,
            fir.fir_number,
            report.report_date,
            report.report_type,
            report.remarks,
            report.created_at
        FROM report
        INNER JOIN fir
            ON report.fir_id = fir.fir_id
        ORDER BY report.created_at DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Report Records</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Report Records</h2>

    <table border="1" cellpadding="8">

        <tr>
            <th>Report ID</th>
            <th>FIR Number</th>
            <th>Report Date</th>
            <th>Report Type</th>
            <th>Remarks</th>
            <th>Created At</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($row['report_id']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['fir_number']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['report_date']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['report_type']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['remarks'] ?? ''); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['created_at']); ?>
            </td>

        </tr>

        <?php } ?>

    </table>

    <br>

    <a href="report_add.php">Add Another Report</a>

    <br><br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>

</html>

<?php

$conn->close();

?>