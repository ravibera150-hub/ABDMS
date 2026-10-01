<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$search = $_GET['search'] ?? '';

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
        WHERE fir.fir_number LIKE ?
           OR evidence.file_name LIKE ?
           OR evidence.file_type LIKE ?
           OR evidence.description LIKE ?
        ORDER BY evidence.uploaded_at DESC";

$search_value = "%" . $search . "%";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssss",
    $search_value,
    $search_value,
    $search_value,
    $search_value
);

$stmt->execute();

$result = $stmt->get_result();

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


    <form method="GET" action="evidence_records.php">

        <input
            type="text"
            name="search"
            placeholder="Search evidence..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">Search</button>

        <a href="evidence_records.php">Clear</a>

    </form>


    <br>


    <table border="1" cellpadding="8">

        <tr>

            <th>Evidence ID</th>

            <th>FIR Number</th>

            <th>File Name</th>

            <th>File Type</th>

            <th>Description</th>

            <th>Uploaded At</th>

            <th>View File</th>

            <th>Action</th>

        </tr>


        <?php if ($result->num_rows > 0) { ?>

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

                        <a
                            href="<?php echo htmlspecialchars($row['file_path']); ?>"
                            target="_blank">
                            Open File
                        </a>

                    </td>

                    <td>

                        <a href="evidence_edit.php?id=<?php echo $row['evidence_id']; ?>">
                            Edit
                        </a>

                        <a href="backend/evidence_delete.php?id=<?php echo $row['evidence_id']; ?>"
                           onclick="return confirm('Are you sure you want to delete this evidence?');">
                           Delete
                        </a>

                    </td>

                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>

                <td colspan="8">
                    No evidence records found.
                </td>

            </tr>

        <?php } ?>

    </table>


    <br>


    <a href="evidence_add.php">
        Add Evidence
    </a>


    <br><br>


    <a href="dashboard.php">
        Back to Dashboard
    </a>


</body>

</html>

<?php

$stmt->close();

$conn->close();

?>