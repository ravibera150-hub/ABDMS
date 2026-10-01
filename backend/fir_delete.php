<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if (!isset($_GET['id'])) {
    header("Location: ../fir_records.php");
    exit();
}

$fir_id = $_GET['id'];

$sql = "DELETE FROM fir
        WHERE fir_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $fir_id
);

if ($stmt->execute()) {

    header("Location: ../fir_records.php");
    exit();

} else {

    echo "Error deleting FIR: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>
