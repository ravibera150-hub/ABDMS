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


if (!filter_var($fir_id, FILTER_VALIDATE_INT)) {
    die("Invalid FIR ID.");
}


/*
 * First collect evidence file paths.
 * We need these after deleting the database records.
 */

$evidence_files = [];

$sql = "SELECT file_path
        FROM evidence
        WHERE fir_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fir_id);
$stmt->execute();

$result = $stmt->get_result();


while ($row = $result->fetch_assoc()) {

    if (!empty($row['file_path'])) {
        $evidence_files[] = $row['file_path'];
    }

}

$stmt->close();


/*
 * Start transaction.
 */

$conn->begin_transaction();


try {


    /* Delete FIR-Criminal relationships */

    $sql = "DELETE FROM fir_criminal
            WHERE fir_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fir_id);
    $stmt->execute();
    $stmt->close();


    /* Delete evidence records */

    $sql = "DELETE FROM evidence
            WHERE fir_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fir_id);
    $stmt->execute();
    $stmt->close();


    /* Delete reports */

    $sql = "DELETE FROM report
            WHERE fir_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fir_id);
    $stmt->execute();
    $stmt->close();


    /* Finally delete FIR */

    $sql = "DELETE FROM fir
            WHERE fir_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fir_id);
    $stmt->execute();


    if ($stmt->affected_rows !== 1) {
        throw new Exception("FIR record not found.");
    }


    $stmt->close();


    /* Commit database changes */

    $conn->commit();


    /*
     * Delete physical evidence files
     * after successful database transaction.
     */

    foreach ($evidence_files as $file_path) {

        $physical_path = __DIR__ . "/../" . $file_path;

        if (file_exists($physical_path)) {
            unlink($physical_path);
        }

    }


    $conn->close();


    header("Location: ../fir_records.php");
    exit();


} catch (Exception $e) {

    $conn->rollback();

    $conn->close();

    echo "Error deleting FIR: " . $e->getMessage();

}

?>