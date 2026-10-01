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

    <style>

        :root {
            --navy: #172033;
            --navy-light: #24304a;
            --gold: #c9a227;
            --cream: #f5f2ea;
            --white: #ffffff;
            --text: #263044;
            --muted: #70798a;
            --border: #dfe3e8;
            --danger: #b42318;
            --shadow: 0 8px 25px rgba(23, 32, 51, 0.08);
        }


        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--cream);
            color: var(--text);
        }


        /* Header */

        .page-header {
            background: var(--navy);
            color: white;

            padding: 22px 35px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }


        .page-header h1 {
            margin: 0;
            font-size: 21px;
            letter-spacing: 0.3px;
        }


        .page-header span {
            color: #cbd2df;
            font-size: 13px;
        }


        /* Main */

        .container {
            max-width: 850px;
            margin: 40px auto;
            padding: 0 20px;
        }


        .title-section {
            margin-bottom: 25px;
        }


        .title-section h2 {
            margin: 0 0 7px;

            color: var(--navy);

            font-size: 27px;
        }


        .title-section p {
            margin: 0;

            color: var(--muted);

            font-size: 14px;
        }


        /* Form Card */

        .form-card {
            background: var(--white);

            border: 1px solid var(--border);

            border-radius: 12px;

            padding: 35px;

            box-shadow: var(--shadow);
        }


        .form-card::before {
            content: "";

            display: block;

            width: 45px;
            height: 3px;

            background: var(--gold);

            margin-bottom: 25px;
        }


        /* Form */

        .form-group {
            margin-bottom: 22px;
        }


        .form-group label {
            display: block;

            margin-bottom: 8px;

            color: var(--navy);

            font-size: 13px;

            font-weight: 600;
        }


        .required {
            color: var(--danger);
        }


        .form-control {
            width: 100%;

            padding: 12px 13px;

            border: 1px solid #cbd2dc;

            border-radius: 6px;

            background: white;

            color: var(--text);

            font-size: 14px;

            outline: none;

            transition:
                border-color 0.2s,
                box-shadow 0.2s;
        }


        .form-control:focus {
            border-color: var(--gold);

            box-shadow:
                0 0 0 3px rgba(201, 162, 39, 0.12);
        }


        textarea.form-control {
            resize: vertical;

            min-height: 130px;

            line-height: 1.5;
        }


        /* Footer */

        .form-footer {
            display: flex;

            justify-content: space-between;

            align-items: center;

            margin-top: 30px;

            padding-top: 22px;

            border-top: 1px solid var(--border);
        }


        .update-btn {
            background: var(--navy);

            color: white;

            border: none;

            padding: 12px 22px;

            border-radius: 6px;

            font-size: 14px;

            font-weight: 600;

            cursor: pointer;
        }


        .update-btn:hover {
            background: var(--navy-light);
        }


        .back-link {
            color: var(--navy);

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;
        }


        .back-link:hover {
            color: var(--gold);
        }


        /* Responsive */

        @media (max-width: 600px) {

            .page-header {
                padding: 18px 20px;
            }


            .page-header span {
                display: none;
            }


            .container {
                margin: 25px auto;
            }


            .form-card {
                padding: 25px 20px;
            }


            .form-footer {
                flex-direction: column-reverse;

                gap: 15px;

                align-items: stretch;
            }


            .update-btn {
                width: 100%;
            }


            .back-link {
                text-align: center;
            }

        }

    </style>

</head>


<body>


<!-- Header -->

<header class="page-header">

    <h1>
        Crime Record Management System
    </h1>


    <span>

        Police ID:
        <?php echo htmlspecialchars($_SESSION['police_id']); ?>

    </span>

</header>



<!-- Main -->

<main class="container">


    <div class="title-section">

        <h2>Edit Report</h2>

        <p>
            Update the FIR, date, report type, or remarks of this report.
        </p>

    </div>



    <!-- Form Card -->

    <div class="form-card">


        <form
            action="backend/report_edit.php"
            method="POST"
        >


            <!-- Hidden Report ID -->

            <input
                type="hidden"
                name="report_id"
                value="<?php echo htmlspecialchars($report['report_id']); ?>"
            >



            <!-- FIR -->

            <div class="form-group">

                <label for="fir_id">

                    Select FIR

                    <span class="required">*</span>

                </label>


                <select
                    class="form-control"
                    id="fir_id"
                    name="fir_id"
                    required
                >

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

            </div>



            <!-- Report Date -->

            <div class="form-group">

                <label for="report_date">

                    Report Date

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="date"
                    id="report_date"
                    name="report_date"
                    value="<?php echo htmlspecialchars($report['report_date']); ?>"
                    required
                >

            </div>



            <!-- Report Type -->

            <div class="form-group">

                <label for="report_type">

                    Report Type

                    <span class="required">*</span>

                </label>


                <select
                    class="form-control"
                    id="report_type"
                    name="report_type"
                    required
                >

                    <option
                        value="Investigation Report"

                        <?php

                        if ($report['report_type'] == 'Investigation Report') {
                            echo 'selected';
                        }

                        ?>
                    >
                        Investigation Report
                    </option>


                    <option
                        value="Evidence Report"

                        <?php

                        if ($report['report_type'] == 'Evidence Report') {
                            echo 'selected';
                        }

                        ?>
                    >
                        Evidence Report
                    </option>


                    <option
                        value="Progress Report"

                        <?php

                        if ($report['report_type'] == 'Progress Report') {
                            echo 'selected';
                        }

                        ?>
                    >
                        Progress Report
                    </option>


                    <option
                        value="Final Report"

                        <?php

                        if ($report['report_type'] == 'Final Report') {
                            echo 'selected';
                        }

                        ?>
                    >
                        Final Report
                    </option>

                </select>

            </div>



            <!-- Remarks -->

            <div class="form-group">

                <label for="remarks">
                    Remarks
                </label>


                <textarea
                    class="form-control"
                    id="remarks"
                    name="remarks"
                    rows="5"
                    placeholder="Enter report details or remarks..."
                ><?php echo htmlspecialchars($report['remarks'] ?? ''); ?></textarea>

            </div>



            <!-- Footer -->

            <div class="form-footer">

                <a
                    class="back-link"
                    href="report_records.php"
                >
                    ← Back to Reports
                </a>


                <button
                    class="update-btn"
                    type="submit"
                >
                    Update Report
                </button>

            </div>


        </form>

    </div>


</main>


</body>

</html>


<?php

$stmt->close();

$conn->close();

?>