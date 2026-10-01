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


// Start transaction

$conn->begin_transaction();


try {

    // First remove FIR-Criminal relationships

    $sql = "DELETE FROM fir_criminal
            WHERE criminal_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $criminal_id
    );

    $stmt->execute();

    $stmt->close();


    // Now delete the criminal record

    $sql = "DELETE FROM criminal
            WHERE criminal_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $criminal_id
    );

    $stmt->execute();

    $stmt->close();


    // Confirm all changes

    $conn->commit();


    header("Location: ../criminal_records.php");
    exit();


} catch (Exception $e) {

    // Undo changes if anything fails

    $conn->rollback();

    echo "Error deleting criminal: " . $e->getMessage();

}


$conn->close();

?>