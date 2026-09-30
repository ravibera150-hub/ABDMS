<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

if (!isset($_GET['id'])) {
    header("Location: evidence_records.php");
    exit();
}

$evidence_id = $_GET['id'];

$sql = "SELECT
            evidence.evidence_id,
            evidence.fir_id,
            fir.fir_number,
            evidence.file_name,
            evidence.file_path,
            evidence.description
        FROM evidence
        INNER JOIN fir
            ON evidence.fir_id = fir.fir_id
        WHERE evidence.evidence_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $evidence_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("Evidence record not found.");
}

$evidence = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Evidence</title>

</head>

<body>

    <h1>Crime Record Management System</h1>

    <h2>Edit Evidence</h2>

    <p>
        FIR:
        <?php echo htmlspecialchars($evidence['fir_number']); ?>
    </p>

    <p>
        Current File:
        <?php echo htmlspecialchars($evidence['file_name']); ?>
    </p>

    <form action="backend/evidence_edit.php"
          method="POST"
          enctype="multipart/form-data">

        <input
            type="hidden"
            name="evidence_id"
            value="<?php echo htmlspecialchars($evidence['evidence_id']); ?>"
        >

        <label>Replace Evidence File (Optional)</label><br>

        <input
            type="file"
            name="evidence_file"
        >

        <br><br>

        <label>Description</label><br>

        <textarea
            name="description"
            rows="5"><?php echo htmlspecialchars($evidence['description'] ?? ''); ?></textarea>

        <br><br>

        <button type="submit">Update Evidence</button>

    </form>

    <br>

    <a href="evidence_records.php">Back to Evidence Records</a>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>