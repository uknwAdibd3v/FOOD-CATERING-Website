<?php
include 'db_connect.php';

// ⚠️ Change this to your own secret code, and keep it only known to your staff.
// This is checked on the SERVER, so it is never visible in the page source or browser console.
define('STAFF_SECRET_CODE', 'WARISANSTAFF2026');

if (isset($_POST['register'])) {

    $role = isset($_POST['role']) ? $_POST['role'] : 'customer';

    if ($role == 'staff') {

        /* ---------- STAFF REGISTRATION ---------- */
        $staff_name     = trim($_POST['staff_name']);
        $staff_phone    = trim($_POST['staff_phone']);
        $staff_email    = trim($_POST['staff_email']);
        $staff_password = $_POST['staff_password'];
        $staff_confirm  = $_POST['staff_confirm_password'];
        $staff_secret   = trim($_POST['staff_secret_code']);

        if (empty($staff_name) || empty($staff_phone) || empty($staff_email) || empty($staff_password) || empty($staff_confirm) || empty($staff_secret)) {
            echo "<script>alert('Please fill in all fields!');</script>";
        }
        else if ($staff_password !== $staff_confirm) {
            echo "<script>alert('Password and Confirm Password does not match!');</script>";
        }
        else if ($staff_secret !== STAFF_SECRET_CODE) {
            echo "<script>alert('Invalid staff secret code! Please check with your manager.');</script>";
        }
        else {
            $staff_name_esc     = mysqli_real_escape_string($conn, $staff_name);
            $staff_phone_esc    = mysqli_real_escape_string($conn, $staff_phone);
            $staff_email_esc    = mysqli_real_escape_string($conn, $staff_email);
            $staff_password_esc = mysqli_real_escape_string($conn, $staff_password);

            $sql = "INSERT INTO staff (STAFF_NAME, STAFF_PHONE, STAFF_EMAIL, STAFF_PASSWORD)
                    VALUES ('$staff_name_esc', '$staff_phone_esc', '$staff_email_esc', '$staff_password_esc')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>
                        alert('Staff registration successful!');
                        window.location.href = 'loginPage.php';
                      </script>";
                exit();
            } else {
                echo "Query Failed: " . mysqli_error($conn);
            }
        }

    } else {

        /* ---------- CUSTOMER REGISTRATION ---------- */
        $cus_name         = trim($_POST['cus_name']);
        $cus_phone        = trim($_POST['cus_phone']);
        $cus_email        = trim($_POST['cus_email']);
        $cus_password     = $_POST['cus_password'];
        $confirm_password = $_POST['confirm_password'];

        if (empty($cus_name) || empty($cus_phone) || empty($cus_email) || empty($cus_password) || empty($confirm_password)) {
            echo "<script>alert('Please fill in all fields!');</script>";
        }
        else if ($cus_password !== $confirm_password) {
            echo "<script>alert('Password and Confirm Password Does not Match!');</script>";
        }
        else {
            $cus_name_esc     = mysqli_real_escape_string($conn, $cus_name);
            $cus_phone_esc    = mysqli_real_escape_string($conn, $cus_phone);
            $cus_email_esc    = mysqli_real_escape_string($conn, $cus_email);
            $cus_password_esc = mysqli_real_escape_string($conn, $cus_password);

            $sql = "INSERT INTO customer (cus_name, cus_phone, cus_email, cus_password)
                    VALUES ('$cus_name_esc','$cus_phone_esc','$cus_email_esc','$cus_password_esc')";

            if (mysqli_query($conn, $sql)) {
                echo "<script>
                        alert('Registration Succesful!');
                        window.location.href = 'loginPage.php';
                      </script>";
                exit();
            } else {
                echo "Query Failed: " . mysqli_error($conn);
            }
        }
    }
}
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="registerPageStyle.css">
    </head>
    <body class="form-page">
        <div class="top-section">
            <img src="nobgLogo.png"><br>
            <h2>Sign Up!</h2>
            <p>Start of a cuisine paradise!</p>
        </div>

        <form action="registerPage.php" method="post" id="registerForm">
            <div class="form-card">

                <div class="role-select">
                    <button type="button" class="role-btn active" id="customerBtn">CUSTOMER</button>
                    <button type="button" class="role-btn" id="staffBtn">STAFF</button>
                </div>

                <input type="hidden" name="role" id="roleInput" value="customer">

                <!-- CUSTOMER FIELDS -->
                <div id="customerFields" class="role-fields">
                    <label>FULL NAME</label>
                    <input type="text" name="cus_name" placeholder="Full Name">

                    <label>PHONE NUMBER</label>
                    <input type="text" name="cus_phone" placeholder="Contact No">

                    <label>EMAIL ADDRESS</label>
                    <input type="text" name="cus_email" placeholder="example@gmail.com">

                    <label>PASSWORD</label>
                    <input type="password" name="cus_password" placeholder="Password">

                    <label>CONFIRM PASSWORD</label>
                    <input type="password" name="confirm_password" placeholder="Retype Password">
                </div>

                <!-- STAFF FIELDS -->
                <div id="staffFields" class="role-fields hidden">
                    <label>FULL NAME</label>
                    <input type="text" name="staff_name" placeholder="Full Name">

                    <label>PHONE NUMBER</label>
                    <input type="text" name="staff_phone" placeholder="Contact No">

                    <label>EMAIL ADDRESS</label>
                    <input type="text" name="staff_email" placeholder="example@gmail.com">

                    <label>PASSWORD</label>
                    <input type="password" name="staff_password" placeholder="Password">

                    <label>CONFIRM PASSWORD</label>
                    <input type="password" name="staff_confirm_password" placeholder="Retype Password">

                    <label>STAFF SECRET CODE</label>
                    <input type="password" name="staff_secret_code" placeholder="Ask your manager for this code">
                </div>

                <button type="submit" name="register" class="submit">REGISTER</button>

                <p class="switch">Lost? Click Here
                    <a href="frontPage.php">Home Page</a>
                </p>
            </div>
        </form>

        <script>
            const customerBtn = document.getElementById("customerBtn");
            const staffBtn = document.getElementById("staffBtn");
            const roleInput = document.getElementById("roleInput");
            const customerFields = document.getElementById("customerFields");
            const staffFields = document.getElementById("staffFields");

            customerBtn.onclick = function () {
                customerBtn.classList.add("active");
                staffBtn.classList.remove("active");
                roleInput.value = "customer";

                customerFields.classList.remove("hidden");
                staffFields.classList.add("hidden");
            };

            staffBtn.onclick = function () {
                staffBtn.classList.add("active");
                customerBtn.classList.remove("active");
                roleInput.value = "staff";

                staffFields.classList.remove("hidden");
                customerFields.classList.add("hidden");
            };
        </script>
    </body>
</html>
