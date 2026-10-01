<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

$search = $_GET['search'] ?? '';

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
        WHERE fir.fir_number LIKE ?
           OR report.report_type LIKE ?
           OR report.remarks LIKE ?
        ORDER BY report.created_at DESC";

$search_value = "%" . $search . "%";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "sss",
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

    <title>Report Records</title>

    <link rel="stylesheet" href="records.css">

</head>

<body>


<header class="page-header">

    <h1>Crime Record Management System</h1>

    <span>
        Police ID:
        <?php echo htmlspecialchars($_SESSION['police_id']); ?>
    </span>

</header>


<main class="container">


    <div class="title-row">

        <h2>Report Records</h2>

        <a
            class="primary-link"
            href="report_add.php"
        >
            + Add Report
        </a>

    </div>


    <!-- Search -->

    <div class="search-box">

        <form
            class="search-form"
            method="GET"
            action="report_records.php"
        >

            <input
                type="text"
                name="search"
                placeholder="Search FIR number, report type..."
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
                href="report_records.php"
            >
                Clear
            </a>

        </form>

    </div>


    <!-- Reports Table -->

    <div class="table-card">

        <table>

            <thead>

                <tr>

                    <th>Report ID</th>

                    <th>FIR Number</th>

                    <th>Report Date</th>

                    <th>Report Type</th>

                    <th>Remarks</th>

                    <th>Created At</th>

                    <th>Action</th>

                </tr>

            </thead>


            <tbody>


                <?php if ($result->num_rows > 0) { ?>


                    <?php while ($row = $result->fetch_assoc()) { ?>


                        <tr>

                            <td>
                                <?php echo htmlspecialchars($row['report_id']); ?>
                            </td>


                            <td>

                                <strong>
                                    <?php echo htmlspecialchars($row['fir_number']); ?>
                                </strong>

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


                            <td>

                                <a
                                    class="action-edit"
                                    href="report_edit.php?id=<?php echo $row['report_id']; ?>"
                                >
                                    Edit
                                </a>

                                &nbsp;|&nbsp;

                                <a
                                    class="action-delete"
                                    href="backend/report_delete.php?id=<?php echo $row['report_id']; ?>"
                                    onclick="return confirm('Are you sure you want to delete this report?');"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>


                    <?php } ?>


                <?php } else { ?>


                    <tr>

                        <td
                            colspan="7"
                            style="text-align: center; padding: 30px;"
                        >

                            No report records found.

                        </td>

                    </tr>


                <?php } ?>


            </tbody>

        </table>

    </div>


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