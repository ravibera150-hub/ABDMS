<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Police Registration</title>


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

            text-align: center;
        }


        .page-header h1 {
            margin: 0;

            font-size: 21px;

            letter-spacing: 0.3px;
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


        /* Password */

        .password-help {
            display: block;

            margin-top: 7px;

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


        .register-btn {
            background: var(--navy);

            color: white;

            border: none;

            padding: 12px 22px;

            border-radius: 6px;

            font-size: 14px;

            font-weight: 600;

            cursor: pointer;
        }


        .register-btn:hover {
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


            .register-btn {
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

</header>



<!-- Main -->

<main class="container">


    <div class="title-section">

        <h2>Police Officer Registration</h2>

        <p>
            Create an account to access the Crime Record Management System.
        </p>

    </div>



    <!-- Form -->

    <div class="form-card">


        <form
            action="backend/register.php"
            method="POST"
        >


            <!-- Full Name -->

            <div class="form-group">

                <label for="full_name">

                    Full Name

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="text"
                    id="full_name"
                    name="full_name"
                    placeholder="Enter full name"
                    required
                >

            </div>



            <!-- Email -->

            <div class="form-group">

                <label for="email">

                    Email ID

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter email address"
                    required
                >

            </div>



            <!-- Password -->

            <div class="form-group">

                <label for="password">

                    Password

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter password"
                    minlength="6"
                    required
                >


                <span class="password-help">
                    Password must contain at least 6 characters.
                </span>

            </div>



            <!-- Confirm Password -->

            <div class="form-group">

                <label for="confirm_password">

                    Confirm Password

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Re-enter password"
                    minlength="6"
                    required
                >

            </div>



            <!-- Rank -->

            <div class="form-group">

                <label for="rank">

                    Rank

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="text"
                    id="rank"
                    name="rank"
                    placeholder="e.g. Inspector"
                    required
                >

            </div>



            <!-- Station -->

            <div class="form-group">

                <label for="station">

                    Police Station

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="text"
                    id="station"
                    name="station"
                    placeholder="Enter police station"
                    required
                >

            </div>



            <!-- Mobile -->

            <div class="form-group">

                <label for="mobile">

                    Mobile Number

                    <span class="required">*</span>

                </label>


                <input
                    class="form-control"
                    type="tel"
                    id="mobile"
                    name="mobile"
                    placeholder="Enter mobile number"
                    pattern="[0-9]{10}"
                    maxlength="10"
                    required
                >

            </div>



            <!-- Footer -->

            <div class="form-footer">

                <a
                    class="back-link"
                    href="index.html"
                >
                    ← Back to Login
                </a>


                <button
                    class="register-btn"
                    type="submit"
                >
                    Create Account
                </button>

            </div>


        </form>

    </div>


</main>


</body>

</html>