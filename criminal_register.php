<?php

session_start();

if (!isset($_SESSION['police_id'])) {
    header("Location: index.html");
    exit();
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Criminal</title>


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


        /* Title */

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
            transition: border-color 0.2s, box-shadow 0.2s;
        }


        .form-control:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(201, 162, 39, 0.12);
        }


        textarea.form-control {
            resize: vertical;
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


        .submit-btn {
            background: var(--navy);
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }


        .submit-btn:hover {
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


            .submit-btn {
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

    <h1>Crime Record Management System</h1>

    <span>

        Police ID:
        <?php echo htmlspecialchars($_SESSION['police_id']); ?>

    </span>

</header>


<!-- Main -->

<main class="container">


    <div class="title-section">

        <h2>Add Criminal Record</h2>

        <p>
            Enter the personal and identification details of the criminal.
        </p>

    </div>


    <!-- Form -->

    <div class="form-card">

        <form
            action="backend/criminal_register.php"
            method="POST"
        >


            <!-- Name -->

            <div class="form-group">

                <label for="name">

                    Full Name
                    <span class="required">*</span>

                </label>

                <input
                    class="form-control"
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter full name"
                    required
                >

            </div>


            <!-- Gender -->

            <div class="form-group">

                <label for="gender">

                    Gender
                    <span class="required">*</span>

                </label>

                <select
                    class="form-control"
                    id="gender"
                    name="gender"
                    required
                >

                    <option value="">
                        -- Select Gender --
                    </option>

                    <option value="Male">
                        Male
                    </option>

                    <option value="Female">
                        Female
                    </option>

                    <option value="Other">
                        Other
                    </option>

                </select>

            </div>


            <!-- Date of Birth -->

            <div class="form-group">

                <label for="date_of_birth">
                    Date of Birth
                </label>

                <input
                    class="form-control"
                    type="date"
                    id="date_of_birth"
                    name="date_of_birth"
                >

            </div>


            <!-- Address -->

            <div class="form-group">

                <label for="address">

                    Address
                    <span class="required">*</span>

                </label>

                <textarea
                    class="form-control"
                    id="address"
                    name="address"
                    rows="3"
                    placeholder="Enter complete address"
                    required
                ></textarea>

            </div>


            <!-- Mobile -->

            <div class="form-group">

                <label for="mobile">
                    Mobile
                </label>

                <input
                    class="form-control"
                    type="tel"
                    id="mobile"
                    name="mobile"
                    placeholder="Enter mobile number"
                >

            </div>


            <!-- Identification Mark -->

            <div class="form-group">

                <label for="identification_mark">
                    Identification Mark
                </label>

                <textarea
                    class="form-control"
                    id="identification_mark"
                    name="identification_mark"
                    rows="3"
                    placeholder="Describe any identifiable physical mark"
                ></textarea>

            </div>


            <!-- Footer -->

            <div class="form-footer">

                <a
                    class="back-link"
                    href="dashboard.php"
                >
                    ← Back to Dashboard
                </a>


                <button
                    class="submit-btn"
                    type="submit"
                >
                    Add Criminal
                </button>

            </div>


        </form>

    </div>


</main>


</body>

</html>