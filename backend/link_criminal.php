<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../link_criminal.php");
    exit();
}

$fir_id = $_POST['fir_id'] ?? '';
$criminal_id = $_POST['criminal_id'] ?? '';

if ($fir_id === '' || $criminal_id === '') {
    die("FIR and criminal are required.");
}

if (
    !filter_var($fir_id, FILTER_VALIDATE_INT) ||
    !filter_var($criminal_id, FILTER_VALIDATE_INT)
) {
    die("Invalid FIR or criminal ID.");
}


/*
 * Check whether the FIR exists
 */
$sql = "SELECT fir_id
        FROM fir
        WHERE fir_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fir_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    $conn->close();
    die("FIR record not found.");
}

$stmt->close();


/*
 * Check whether the criminal exists
 */
$sql = "SELECT criminal_id
        FROM criminal
        WHERE criminal_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $criminal_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $stmt->close();
    $conn->close();
    die("Criminal record not found.");
}

$stmt->close();


/*
 * Check whether this criminal is already
 * linked with this FIR
 */
$sql = "SELECT fir_id
        FROM fir_criminal
        WHERE fir_id = ?
        AND criminal_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $fir_id,
    $criminal_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $stmt->close();
    $conn->close();

    die("This criminal is already linked with this FIR.");
}

$stmt->close();


/*
 * Create FIR-Criminal relationship
 */
$sql = "INSERT INTO fir_criminal
        (fir_id, criminal_id)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $fir_id,
    $criminal_id
);

if ($stmt->execute()) {

    $stmt->close();
    $conn->close();

    header("Location: ../link_criminal.php");
    exit();

} else {

    echo "Error linking criminal: " . $stmt->error;
}

$stmt->close();
$conn->close();

?>