<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$sql = "SELECT
            criminal.criminal_id,
            criminal.name,
            criminal.gender,
            criminal.date_of_birth,
            criminal.address,
            criminal.mobile,
            criminal.identification_mark,
            GROUP_CONCAT(fir.fir_number SEPARATOR ', ') AS fir_numbers
        FROM criminal
        LEFT JOIN fir_criminal
            ON criminal.criminal_id = fir_criminal.criminal_id
        LEFT JOIN fir
            ON fir_criminal.fir_id = fir.fir_id
        GROUP BY criminal.criminal_id
        ORDER BY criminal.criminal_id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Criminal Records</title>
</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Criminal Records</h2>

    <table border="1" cellpadding="8">

        <tr>
            <th>Criminal ID</th>
            <th>Name</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Address</th>
            <th>Mobile</th>
            <th>Identification Mark</th>
            <th>Associated FIRs</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()) { ?>

            <tr>

                <td>
                    <?php echo htmlspecialchars($row['criminal_id']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['name']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['gender']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['date_of_birth'] ?? ''); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['address']); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['mobile'] ?? ''); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['identification_mark'] ?? ''); ?>
                </td>

                <td>
                    <?php echo htmlspecialchars($row['fir_numbers'] ?? 'No FIR linked'); ?>
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