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

    <style>

        :root {
            --navy: #172033;
            --navy-light: #24304a;
            --gold: #c9a227;
            --cream: #f5f2ea;
            --white: #ffffff;
            --text: #263044;
            --muted: #70798a;
            --border: #dfe3e8;
            --danger: #b42318;
            --shadow: 0 8px 25px rgba(23, 32, 51, 0.08);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            background: var(--cream);
            color: var(--text);
        }

        /* Header */

        .page-header {
            background: var(--navy);
            color: white;
            padding: 22px 35px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            margin: 0;
            font-size: 21px;
            letter-spacing: 0.3px;
        }

        .page-header span {
            color: #cbd2df;
            font-size: 13px;
        }

        /* Main */

        .container {
            max-width: 850px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .title-section {
            margin-bottom: 25px;
        }

        .title-section h2 {
            margin: 0 0 7px;
            color: var(--navy);
            font-size: 27px;
        }

        .title-section p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        /* Form Card */

        .form-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 35px;
            box-shadow: var(--shadow);
        }

        .form-card::before {
            content: "";

            display: block;

            width: 45px;
            height: 3px;

            background: var(--gold);

            margin-bottom: 25px;
        }

        /* Case Information */

        .case-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;

            margin-bottom: 28px;
        }

        .info-box {
            background: #f8f8f5;
            border: 1px solid var(--border);
            border-radius: 7px;
            padding: 14px;
        }

        .info-label {
            display: block;
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 5px;
        }

        .info-value {
            color: var(--navy);
            font-size: 14px;
            font-weight: 600;
            word-break: break-word;
        }

        /* Form */

        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--navy);
            font-size: 13px;
            font-weight: 600;
        }

        .form-control {
            width: 100%;
            padding: 12px 13px;

            border: 1px solid #cbd2dc;
            border-radius: 6px;

            background: white;
            color: var(--text);

            font-size: 14px;
            outline: none;

            transition:
                border-color 0.2s,
                box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: var(--gold);

            box-shadow:
                0 0 0 3px rgba(201, 162, 39, 0.12);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 130px;
            line-height: 1.5;
        }

        /* File Upload */

        .file-upload {
            border: 1px dashed #b8bec8;
            border-radius: 8px;
            padding: 18px;
            background: #fafaf8;
        }

        .file-upload input {
            width: 100%;
            font-size: 14px;
        }

        .file-help {
            display: block;
            margin-top: 8px;
            color: var(--muted);
            font-size: 12px;
        }

        /* Footer */

        .form-footer {
            display: flex;

            justify-content: space-between;
            align-items: center;

            margin-top: 30px;
            padding-top: 22px;

            border-top: 1px solid var(--border);
        }

        .update-btn {
            background: var(--navy);
            color: white;

            border: none;
            padding: 12px 22px;

            border-radius: 6px;

            font-size: 14px;
            font-weight: 600;

            cursor: pointer;
        }

        .update-btn:hover {
            background: var(--navy-light);
        }

        .back-link {
            color: var(--navy);
            text-decoration: none;

            font-size: 14px;
            font-weight: 600;
        }

        .back-link:hover {
            color: var(--gold);
        }

        /* Responsive */

        @media (max-width: 600px) {

            .page-header {
                padding: 18px 20px;
            }

            .page-header span {
                display: none;
            }

            .container {
                margin: 25px auto;
            }

            .form-card {
                padding: 25px 20px;
            }

            .case-info {
                grid-template-columns: 1fr;
            }

            .form-footer {
                flex-direction: column-reverse;
                gap: 15px;
                align-items: stretch;
            }

            .update-btn {
                width: 100%;
            }

            .back-link {
                text-align: center;
            }

        }

    </style>

</head>

<body>


<!-- Header -->

<header class="page-header">

    <h1>
        Crime Record Management System
    </h1>

    <span>
        Police ID:
        <?php echo htmlspecialchars($_SESSION['police_id']); ?>
    </span>

</header>


<!-- Main -->

<main class="container">


    <div class="title-section">

        <h2>Edit Evidence</h2>

        <p>
            Update the evidence file or modify its description.
        </p>

    </div>


    <!-- Form Card -->

    <div class="form-card">


        <!-- Current Information -->

        <div class="case-info">

            <div class="info-box">

                <span class="info-label">
                    FIR Number
                </span>

                <span class="info-value">
                    <?php echo htmlspecialchars($evidence['fir_number']); ?>
                </span>

            </div>


            <div class="info-box">

                <span class="info-label">
                    Current File
                </span>

                <span class="info-value">
                    <?php echo htmlspecialchars($evidence['file_name']); ?>
                </span>

            </div>

        </div>


        <!-- Form -->

        <form
            action="backend/evidence_edit.php"
            method="POST"
            enctype="multipart/form-data"
        >


            <input
                type="hidden"
                name="evidence_id"
                value="<?php echo htmlspecialchars($evidence['evidence_id']); ?>"
            >


            <!-- Replace File -->

            <div class="form-group">

                <label for="evidence_file">
                    Replace Evidence File
                </label>

                <div class="file-upload">

                    <input
                        type="file"
                        id="evidence_file"
                        name="evidence_file"
                    >

                    <span class="file-help">
                        Optional. Leave empty to keep the current evidence file.
                    </span>

                </div>

            </div>


            <!-- Description -->

            <div class="form-group">

                <label for="description">
                    Description
                </label>

                <textarea
                    class="form-control"
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Enter details about the evidence..."
                ><?php echo htmlspecialchars($evidence['description'] ?? ''); ?></textarea>

            </div>


            <!-- Footer -->

            <div class="form-footer">

                <a
                    class="back-link"
                    href="evidence_records.php"
                >
                    ← Back to Evidence Records
                </a>


                <button
                    class="update-btn"
                    type="submit"
                >
                    Update Evidence
                </button>

            </div>


        </form>

    </div>


</main>


</body>

</html>

<?php

$stmt->close();
$conn->close();

?>