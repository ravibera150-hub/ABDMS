<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$search = $_GET['search'] ?? '';

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
        WHERE criminal.name LIKE ?
           OR criminal.gender LIKE ?
           OR criminal.address LIKE ?
           OR criminal.mobile LIKE ?
           OR criminal.identification_mark LIKE ?
        GROUP BY criminal.criminal_id
        ORDER BY criminal.criminal_id DESC";

$search_value = "%" . $search . "%";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sssss",
    $search_value,
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

    <title>Criminal Records</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Criminal Records</h2>


    <form method="GET" action="criminal_records.php">

        <input
            type="text"
            name="search"
            placeholder="Search criminal..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">Search</button>

        <a href="criminal_records.php">Clear</a>

    </form>


    <br>


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

            <th>Action</th>

        </tr>


        <?php if ($result->num_rows > 0) { ?>

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
                        <?php echo htmlspecialchars(
                            $row['fir_numbers'] ?? 'No FIR linked'
                        ); ?>
                    </td>

                    <td>

                        <a href="criminal_edit.php?id=<?php echo $row['criminal_id']; ?>">
                            Edit
                        </a>

                    </td>

                </tr>

            <?php } ?>

        <?php } else { ?>

            <tr>

                <td colspan="9">
                    No criminal records found.
                </td>

            </tr>

        <?php } ?>

    </table>


    <br>


    <a href="criminal_register.php">
        Add Criminal
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