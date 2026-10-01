<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../report_add.php");
    exit();
}

$fir_id = $_POST['fir_id'] ?? '';
$report_date = $_POST['report_date'] ?? '';
$report_type = trim($_POST['report_type'] ?? '');
$remarks = trim($_POST['remarks'] ?? '');

if (
    $fir_id === '' ||
    $report_date === '' ||
    $report_type === '' ||
    $remarks === ''
) {
    die("All report fields are required.");
}

if (!filter_var($fir_id, FILTER_VALIDATE_INT)) {
    die("Invalid FIR ID.");
}


/*
 * Allowed report types
 */
$allowed_report_types = [
    "Investigation Report",
    "Evidence Report",
    "Progress Report",
    "Final Report"
];

if (!in_array($report_type, $allowed_report_types, true)) {
    die("Invalid report type.");
}


/*
 * Check whether FIR exists
 */
$sql = "SELECT fir_id
        FROM fir
        WHERE fir_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $fir_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    $stmt->close();
    $conn->close();

    die("FIR record not found.");
}

$stmt->close();


/*
 * Insert report
 */
$sql = "INSERT INTO report
        (fir_id, report_date, report_type, remarks)
        VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "isss",
    $fir_id,
    $report_date,
    $report_type,
    $remarks
);

if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    header("Location: ../report_records.php");
    exit();

} else {

    echo "Error adding report: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>