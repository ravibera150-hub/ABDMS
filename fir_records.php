<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$search = $_GET['search'] ?? '';

$sql = "SELECT 
            fir.fir_id,
            fir.fir_number,
            crime_type.crime_name,
            police.full_name,
            fir.incident_date,
            fir.location,
            fir.description,
            fir.status,
            fir.created_at
        FROM fir
        INNER JOIN crime_type
            ON fir.crime_type_id = crime_type.crime_type_id
        INNER JOIN police
            ON fir.police_id = police.police_id
        WHERE fir.fir_number LIKE ?
           OR crime_type.crime_name LIKE ?
           OR fir.location LIKE ?
           OR fir.description LIKE ?
        ORDER BY fir.created_at DESC";

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

    <title>FIR Records</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>FIR Records</h2>

    <form method="GET" action="fir_records.php">

        <input
            type="text"
            name="search"
            placeholder="Search FIR, crime type, location..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">Search</button>

        <a href="fir_records.php">Clear</a>

    </form>

    <br>

    <table border="1" cellpadding="8">

        <tr>
            <th>FIR ID</th>
            <th>FIR Number</th>
            <th>Crime Type</th>
            <th>Registered By</th>
            <th>Incident Date</th>
            <th>Location</th>
            <th>Description</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>

        <?php if ($result->num_rows > 0) { ?>

            <?php while ($row = $result->fetch_assoc()) { ?>

                <tr>

                    <td><?php echo htmlspecialchars($row['fir_id']); ?></td>

                    <td><?php echo htmlspecialchars($row['fir_number']); ?></td>

                    <td><?php echo htmlspecialchars($row['crime_name']); ?></td>

                    <td><?php echo htmlspecialchars($row['full_name']); ?></td>

                    <td><?php echo htmlspecialchars($row['incident_date']); ?></td>

                    <td><?php echo htmlspecialchars($row['location']); ?></td>

                    <td><?php echo htmlspecialchars($row['description']); ?></td>

                    <td><?php echo htmlspecialchars($row['status']); ?></td>

                    <td><?php echo htmlspecialchars($row['created_at']); ?></td>

                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>

                <td colspan="9">
                    No FIR records found.
                </td>

            </tr>

        <?php } ?>

    </table>

    <br>

    <a href="fir_register.php">Register New FIR</a>

    <br><br>

    <a href="dashboard.php">Back to Dashboard</a>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>