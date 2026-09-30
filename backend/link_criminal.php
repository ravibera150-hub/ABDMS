<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

$fir_id = $_POST['fir_id'];
$criminal_id = $_POST['criminal_id'];

$sql = "INSERT INTO fir_criminal (fir_id, criminal_id)
        VALUES (?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ii",
    $fir_id,
    $criminal_id
);

if ($stmt->execute()) {

    echo "Criminal linked to FIR successfully!";

} else {

    echo "Error linking criminal: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>