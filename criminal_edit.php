<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

if (!isset($_GET['id'])) {
    header("Location: criminal_records.php");
    exit();
}

$criminal_id = $_GET['id'];

$sql = "SELECT * FROM criminal WHERE criminal_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $criminal_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("Criminal record not found.");
}

$criminal = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Criminal</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Edit Criminal Record</h2>

    <form action="backend/criminal_edit.php" method="POST">

        <input
            type="hidden"
            name="criminal_id"
            value="<?php echo htmlspecialchars($criminal['criminal_id']); ?>"
        >

        <label>Full Name</label><br>

        <input
            type="text"
            name="name"
            value="<?php echo htmlspecialchars($criminal['name']); ?>"
            required
        >

        <br><br>


        <label>Gender</label><br>

        <select name="gender" required>

            <option value="Male"
                <?php if ($criminal['gender'] == 'Male') echo 'selected'; ?>>
                Male
            </option>

            <option value="Female"
                <?php if ($criminal['gender'] == 'Female') echo 'selected'; ?>>
                Female
            </option>

            <option value="Other"
                <?php if ($criminal['gender'] == 'Other') echo 'selected'; ?>>
                Other
            </option>

        </select>

        <br><br>


        <label>Date of Birth</label><br>

        <input
            type="date"
            name="date_of_birth"
            value="<?php echo htmlspecialchars($criminal['date_of_birth'] ?? ''); ?>"
        >

        <br><br>


        <label>Address</label><br>

        <textarea
            name="address"
            rows="3"
            required><?php echo htmlspecialchars($criminal['address']); ?></textarea>

        <br><br>


        <label>Mobile</label><br>

        <input
            type="tel"
            name="mobile"
            value="<?php echo htmlspecialchars($criminal['mobile'] ?? ''); ?>"
        >

        <br><br>


        <label>Identification Mark</label><br>

        <textarea
            name="identification_mark"
            rows="3"><?php echo htmlspecialchars($criminal['identification_mark'] ?? ''); ?></textarea>

        <br><br>


        <button type="submit">Update Criminal</button>

    </form>

    <br>

    <a href="criminal_records.php">Back to Criminal Records</a>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>