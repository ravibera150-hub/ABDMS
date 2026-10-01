<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";


// Get statistics

$fir_count = 0;
$criminal_count = 0;
$evidence_count = 0;
$report_count = 0;


$result = $conn->query("SELECT COUNT(*) AS total FROM fir");
if ($result) {
    $fir_count = $result->fetch_assoc()['total'];
}


$result = $conn->query("SELECT COUNT(*) AS total FROM criminal");
if ($result) {
    $criminal_count = $result->fetch_assoc()['total'];
}


$result = $conn->query("SELECT COUNT(*) AS total FROM evidence");
if ($result) {
    $evidence_count = $result->fetch_assoc()['total'];
}


$result = $conn->query("SELECT COUNT(*) AS total FROM report");
if ($result) {
    $report_count = $result->fetch_assoc()['total'];
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Crime Record Management System</title>

    <link rel="stylesheet" href="dashboard.css">

</head>

<body>


<!-- Sidebar -->

<aside class="sidebar">

    <div class="brand">

        <h2>CRIME RECORDS</h2>

        <p>Management System</p>

    </div>


    <nav class="nav">

        <a href="dashboard.php">Dashboard</a>

        <a href="fir_records.php">FIR Records</a>

        <a href="criminal_records.php">Criminal Records</a>

        <a href="evidence_records.php">Evidence Records</a>

        <a href="report_records.php">Reports</a>

        <a href="fir_register.php">Register FIR</a>

        <a href="criminal_register.php">Add Criminal</a>

        <a href="evidence_add.php">Add Evidence</a>

        <a href="report_add.php">Add Report</a>

        <a class="logout" href="logout.php">Logout</a>

    </nav>

</aside>


<!-- Main -->

<main class="main">


    <header class="topbar">

        <h1>Dashboard</h1>

        <div class="user-info">

            <strong>
                <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </strong>

            <span>
                Police ID:
                <?php echo htmlspecialchars($_SESSION['police_id']); ?>
            </span>

        </div>

    </header>


    <section class="content">


        <div class="welcome">

            <h2>
                Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>
            </h2>

            <p>
                Crime record management and case monitoring dashboard.
            </p>

        </div>


        <!-- Statistics -->

        <div class="stats">


            <div class="stat-card">

                <div class="label">Total FIRs</div>

                <div class="number">
                    <?php echo $fir_count; ?>
                </div>

                <div class="accent"></div>

            </div>


            <div class="stat-card">

                <div class="label">Criminal Records</div>

                <div class="number">
                    <?php echo $criminal_count; ?>
                </div>

                <div class="accent"></div>

            </div>


            <div class="stat-card">

                <div class="label">Evidence Records</div>

                <div class="number">
                    <?php echo $evidence_count; ?>
                </div>

                <div class="accent"></div>

            </div>


            <div class="stat-card">

                <div class="label">Reports</div>

                <div class="number">
                    <?php echo $report_count; ?>
                </div>

                <div class="accent"></div>

            </div>


        </div>


        <!-- Operations -->

        <h2 class="section-title">
            Police Operations
        </h2>


        <div class="operations">


            <div class="operation-card">

                <h3>FIR Management</h3>

                <p>
                    Register, search, update and manage First Information Reports.
                </p>

                <a href="fir_records.php">
                    Open Records →
                </a>

            </div>


            <div class="operation-card">

                <h3>Criminal Records</h3>

                <p>
                    Maintain criminal information and FIR associations.
                </p>

                <a href="criminal_records.php">
                    Open Records →
                </a>

            </div>


            <div class="operation-card">

                <h3>Evidence</h3>

                <p>
                    Store, view and manage evidence associated with FIR cases.
                </p>

                <a href="evidence_records.php">
                    Open Records →
                </a>

            </div>


            <div class="operation-card">

                <h3>Reports</h3>

                <p>
                    Create and manage investigation and case reports.
                </p>

                <a href="report_records.php">
                    Open Reports →
                </a>

            </div>


        </div>


    </section>


</main>


</body>

</html>

<?php

$conn->close();

?>