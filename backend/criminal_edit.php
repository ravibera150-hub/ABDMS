<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

$criminal_id = $_POST['criminal_id'];
$name = $_POST['name'];
$gender = $_POST['gender'];
$date_of_birth = $_POST['date_of_birth'];
$address = $_POST['address'];
$mobile = $_POST['mobile'];
$identification_mark = $_POST['identification_mark'];

$sql = "UPDATE criminal
        SET name = ?,
            gender = ?,
            date_of_birth = ?,
            address = ?,
            mobile = ?,
            identification_mark = ?
        WHERE criminal_id = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssssi",
    $name,
    $gender,
    $date_of_birth,
    $address,
    $mobile,
    $identification_mark,
    $criminal_id
);

if ($stmt->execute()) {

    header("Location: ../criminal_records.php");
    exit();

} else {

    echo "Error updating criminal: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>