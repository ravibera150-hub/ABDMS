<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: ../index.html");
    exit();
}

require_once "db.php";

$name = $_POST['name'];
$gender = $_POST['gender'];
$date_of_birth = $_POST['date_of_birth'];
$address = $_POST['address'];
$mobile = $_POST['mobile'];
$identification_mark = $_POST['identification_mark'];

$sql = "INSERT INTO criminal
        (name, gender, date_of_birth, address, mobile, identification_mark)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

$stmt->bind_param(
    "ssssss",
    $name,
    $gender,
    $date_of_birth,
    $address,
    $mobile,
    $identification_mark
);

if ($stmt->execute()) {

    echo "Criminal record added successfully!";

} else {

    echo "Error adding criminal: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>