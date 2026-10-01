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

    <link rel="stylesheet" href="records.css">

</head>

<body>


<!-- Header -->

<header class="page-header">

    <h1>Crime Record Management System</h1>

    <span>
        Police ID:
        <?php echo htmlspecialchars($_SESSION['police_id']); ?>
    </span>

</header>


<!-- Main Content -->

<main class="container">


    <div class="title-row">

        <h2>FIR Records</h2>

        <a class="primary-link" href="fir_register.php">
            + Register New FIR
        </a>

    </div>


    <!-- Search -->

    <div class="search-box">

        <form
            class="search-form"
            method="GET"
            action="fir_records.php"
        >

            <input
                type="text"
                name="search"
                placeholder="Search FIR number, crime type, location..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button
                class="search-btn"
                type="submit"
            >
                Search
            </button>

            <a
                class="clear-btn"
                href="fir_records.php"
            >
                Clear
            </a>

        </form>

    </div>


    <!-- Table -->

    <div class="table-card">

        <table>

            <thead>

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

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>


                <?php if ($result->num_rows > 0) { ?>


                    <?php while ($row = $result->fetch_assoc()) { ?>


                        <tr>

                            <td>
                                <?php echo htmlspecialchars($row['fir_id']); ?>
                            </td>


                            <td>
                                <strong>
                                    <?php echo htmlspecialchars($row['fir_number']); ?>
                                </strong>
                            </td>


                            <td>
                                <?php echo htmlspecialchars($row['crime_name']); ?>
                            </td>


                            <td>
                                <?php echo htmlspecialchars($row['full_name']); ?>
                            </td>


                            <td>
                                <?php echo htmlspecialchars($row['incident_date']); ?>
                            </td>


                            <td>
                                <?php echo htmlspecialchars($row['location']); ?>
                            </td>


                            <td>
                                <?php echo htmlspecialchars($row['description']); ?>
                            </td>


                            <td>

                                <span class="status">

                                    <?php echo htmlspecialchars($row['status']); ?>

                                </span>

                            </td>


                            <td>
                                <?php echo htmlspecialchars($row['created_at']); ?>
                            </td>


                            <td>

                                <a
                                    class="action-edit"
                                    href="fir_edit.php?id=<?php echo $row['fir_id']; ?>"
                                >
                                    Edit
                                </a>

                                &nbsp;|&nbsp;

                                <a
                                    class="action-delete"
                                    href="backend/fir_delete.php?id=<?php echo $row['fir_id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this FIR and all related records?');"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>


                    <?php } ?>


                <?php } else { ?>


                    <tr>

                        <td colspan="10" style="text-align: center; padding: 30px;">

                            No FIR records found.

                        </td>

                    </tr>


                <?php } ?>


            </tbody>

        </table>

    </div>


    <!-- Bottom Navigation -->

    <div class="bottom-links">

        <a
            class="secondary-link"
            href="dashboard.php"
        >
            ← Back to Dashboard
        </a>

    </div>


</main>


</body>

</html>


<?php

$stmt->close();

$conn->close();

?>