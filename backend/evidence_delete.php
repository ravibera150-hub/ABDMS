<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

if (!isset($_GET['id'])) {
    header("Location: ../evidence_records.php");
    exit();
}

$evidence_id = $_GET['id'];

if (!filter_var($evidence_id, FILTER_VALIDATE_INT)) {
    die("Invalid evidence ID.");
}


/*
 * Get physical file path
 */
$sql = "SELECT file_path
        FROM evidence
        WHERE evidence_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "i",
    $evidence_id
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    $stmt->close();
    $conn->close();

    die("Evidence record not found.");
}

$evidence = $result->fetch_assoc();

$stmt->close();


/*
 * Start transaction
 */
$conn->begin_transaction();

try {

    $sql = "DELETE FROM evidence
            WHERE evidence_id = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "i",
        $evidence_id
    );

    $stmt->execute();

    if ($stmt->affected_rows !== 1) {
        throw new Exception("Evidence record could not be deleted.");
    }

    $stmt->close();

    /*
     * Confirm database deletion
     */
    $conn->commit();


    /*
     * Delete physical file
     */
    if (!empty($evidence['file_path'])) {

        $physical_path =
            __DIR__ . "/../" . $evidence['file_path'];

        if (file_exists($physical_path)) {
            unlink($physical_path);
        }
    }

    $conn->close();

    header("Location: ../evidence_records.php");
    exit();

} catch (Exception $e) {

    $conn->rollback();
    $conn->close();

    echo "Error deleting evidence: " . $e->getMessage();
}

?>