<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if (!isset($_GET['id'])) {
    header("Location: ../criminal_records.php");
    exit();
}

$criminal_id = $_GET['id'];

if (!filter_var($criminal_id, FILTER_VALIDATE_INT)) {
    die("Invalid criminal ID.");
}

$conn->begin_transaction();

try {

    // Remove FIR-Criminal relationships first
    $sql = "DELETE FROM fir_criminal
            WHERE criminal_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $criminal_id
    );

    $stmt->execute();
    $stmt->close();


    // Delete criminal record
    $sql = "DELETE FROM criminal
            WHERE criminal_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $criminal_id
    );

    $stmt->execute();

    if ($stmt->affected_rows !== 1) {
        throw new Exception("Criminal record not found.");
    }

    $stmt->close();


    // Confirm transaction
    $conn->commit();

    $conn->close();

    header("Location: ../criminal_records.php");
    exit();

} catch (Exception $e) {

    $conn->rollback();
    $conn->close();

    echo "Error deleting criminal: " . $e->getMessage();
}

?>