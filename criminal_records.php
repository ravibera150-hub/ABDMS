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

        <h2>Criminal Records</h2>

        <a
            class="primary-link"
            href="criminal_register.php"
        >
            + Add Criminal
        </a>

    </div>


    <!-- Search -->

    <div class="search-box">

        <form
            class="search-form"
            method="GET"
            action="criminal_records.php"
        >

            <input
                type="text"
                name="search"
                placeholder="Search criminal name, address, mobile..."
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
                href="criminal_records.php"
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

            </thead>


            <tbody>


                <?php if ($result->num_rows > 0) { ?>


                    <?php while ($row = $result->fetch_assoc()) { ?>


                        <tr>

                            <td>
                                <?php echo htmlspecialchars($row['criminal_id']); ?>
                            </td>


                            <td>

                                <strong>
                                    <?php echo htmlspecialchars($row['name']); ?>
                                </strong>

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

                                <?php

                                if (!empty($row['fir_numbers'])) {

                                    echo htmlspecialchars($row['fir_numbers']);

                                } else {

                                    echo '<span class="status">No FIR linked</span>';

                                }

                                ?>

                            </td>


                            <td>

                                <a
                                    class="action-edit"
                                    href="criminal_edit.php?id=<?php echo $row['criminal_id']; ?>"
                                >
                                    Edit
                                </a>

                                &nbsp;|&nbsp;

                                <a
                                    class="action-delete"
                                    href="backend/criminal_delete.php?id=<?php echo $row['criminal_id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this criminal record?');"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>


                    <?php } ?>


                <?php } else { ?>


                    <tr>

                        <td
                            colspan="9"
                            style="text-align: center; padding: 30px;"
                        >

                            No criminal records found.

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