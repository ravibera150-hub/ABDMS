<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$sql = "SELECT
            evidence.evidence_id,
            fir.fir_number,
            evidence.file_name,
            evidence.file_path,
            evidence.file_type,
            evidence.description,
            evidence.uploaded_at
        FROM evidence
        INNER JOIN fir
            ON evidence.fir_id = fir.fir_id
        ORDER BY evidence.uploaded_at DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Evidence Records</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Evidence Records</h2>

    <table border="1" cellpadding="8">

        <tr>
            <th>Evidence ID</th>
            <th>FIR Number</th>
            <th>File Name</th>
            <th>File Type</th>
            <th>Description</th>
            <th>Uploaded At</th>
            <th>View File</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>

        <tr>

            <td>
                <?php echo htmlspecialchars($row['evidence_id']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['fir_number']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['file_name']); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['file_type'] ?? ''); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['description'] ?? ''); ?>
            </td>

            <td>
                <?php echo htmlspecialchars($row['uploaded_at']); ?>
            </td>

            <td>
                <a href="<?php echo htmlspecialchars($row['file_path']); ?>"
                   target="_blank">
                    Open File
                </a>
            </td>

        </tr>

        <?php } ?>

    </table>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>

</html>

<?php

$conn->close();

?>