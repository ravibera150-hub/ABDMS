<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

require_once "backend/db.php";

if (!isset($_GET['id'])) {
    header("Location: fir_records.php");
    exit();
}

$fir_id = $_GET['id'];

$sql = "SELECT * FROM fir WHERE fir_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fir_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("FIR not found.");
}

$fir = $result->fetch_assoc();


$crime_sql = "SELECT crime_type_id, crime_name
              FROM crime_type
              ORDER BY crime_name";

$crime_result = $conn->query($crime_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit FIR</title>


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


        .required {
            color: var(--danger);
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

        <h2>Edit FIR</h2>

        <p>
            Update the information and current status of this FIR record.
        </p>

    </div>



    <!-- Form -->

    <div class="form-card">

        <form
            action="backend/fir_edit.php"
            method="POST"
        >


            <!-- Hidden FIR ID -->

            <input
                type="hidden"
                name="fir_id"
                value="<?php echo htmlspecialchars($fir['fir_id']); ?>"
            >



            <!-- FIR Number -->

            <div class="form-group">

                <label for="fir_number">

                    FIR Number

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="text"
                    id="fir_number"
                    name="fir_number"
                    value="<?php echo htmlspecialchars($fir['fir_number']); ?>"
                    required
                >

            </div>



            <!-- Crime Type -->

            <div class="form-group">

                <label for="crime_type_id">

                    Crime Type

                    <span class="required">*</span>

                </label>


                <select
                    class="form-control"
                    id="crime_type_id"
                    name="crime_type_id"
                    required
                >

                    <?php while ($crime = $crime_result->fetch_assoc()) { ?>

                        <option
                            value="<?php echo $crime['crime_type_id']; ?>"

                            <?php
                            if ($crime['crime_type_id'] == $fir['crime_type_id']) {
                                echo "selected";
                            }
                            ?>
                        >

                            <?php echo htmlspecialchars($crime['crime_name']); ?>

                        </option>

                    <?php } ?>

                </select>

            </div>



            <!-- Incident Date -->

            <div class="form-group">

                <label for="incident_date">

                    Incident Date

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="date"
                    id="incident_date"
                    name="incident_date"
                    value="<?php echo htmlspecialchars($fir['incident_date']); ?>"
                    required
                >

            </div>



            <!-- Location -->

            <div class="form-group">

                <label for="location">

                    Location

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="text"
                    id="location"
                    name="location"
                    value="<?php echo htmlspecialchars($fir['location']); ?>"
                    required
                >

            </div>



            <!-- Description -->

            <div class="form-group">

                <label for="description">

                    Description

                    <span class="required">*</span>

                </label>


                <textarea
                    class="form-control"
                    id="description"
                    name="description"
                    rows="5"
                    required
                ><?php echo htmlspecialchars($fir['description']); ?></textarea>

            </div>



            <!-- Status -->

            <div class="form-group">

                <label for="status">

                    Status

                    <span class="required">*</span>

                </label>


                <select
                    class="form-control"
                    id="status"
                    name="status"
                    required
                >

                    <option
                        value="Pending"

                        <?php
                        if ($fir['status'] == 'Pending') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Pending
                    </option>


                    <option
                        value="Under Investigation"

                        <?php
                        if ($fir['status'] == 'Under Investigation') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Under Investigation
                    </option>


                    <option
                        value="Solved"

                        <?php
                        if ($fir['status'] == 'Solved') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Solved
                    </option>


                    <option
                        value="Closed"

                        <?php
                        if ($fir['status'] == 'Closed') {
                            echo 'selected';
                        }
                        ?>
                    >
                        Closed
                    </option>

                </select>

            </div>



            <!-- Footer -->

            <div class="form-footer">

                <a
                    class="back-link"
                    href="fir_records.php"
                >
                    ← Back to FIR Records
                </a>


                <button
                    class="update-btn"
                    type="submit"
                >
                    Update FIR
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