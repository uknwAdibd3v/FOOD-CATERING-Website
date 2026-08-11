<?php
session_start();
include 'db_connect.php';

if (isset($_POST['login'])) {

    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('Please enter email and password!');</script>";
    } 
    else {
        if ($role == "customer") {

            $sql = "SELECT * FROM customer 
                    WHERE CUS_EMAIL = '$email' 
                    AND CUS_PASSWORD = '$password'";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {

                $row = mysqli_fetch_assoc($result);

                $_SESSION['cus_id'] = $row['CUS_ID'];
                $_SESSION['cus_name'] = $row['CUS_NAME'];

                echo "<script>
                        alert('Login successful!');
                        window.location.href = 'homePage.php';
                      </script>";
                exit();
            } 
            else {
                echo "<script>alert('Wrong customer email or password!');</script>";
            }

        } 
        else if ($role == "admin") {

            $sql = "SELECT * FROM staff 
                    WHERE STAFF_EMAIL = '$email' 
                    AND STAFF_PASSWORD = '$password'";

            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {

                $row = mysqli_fetch_assoc($result);

                $_SESSION['staff_id'] = $row['STAFF_ID'];
                $_SESSION['staff_email'] = $row['STAFF_EMAIL'];

                echo "<script>
                        alert(' login successful!');
                        window.location.href = 'adminPage.php';
                      </script>";
                exit();
            } 
            else {
                echo "<script>alert('Wrong admin email or password!');</script>";
            }
        }
    }
}
?>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="loginPageStyle.css">
</head>

<body class="form-page">

    <div class="top-section">
        <img src="nobgLogo.png"><br>
        <h2>WELCOME TO NORMAN WARISAN!</h2>
    </div>

    <form action="loginPage.php" method="post">
        <div class="form-card">

            <div class="role-select">
                <button type="button" class="role-btn active" id="customerBtn">CUSTOMER</button>
                <button type="button" class="role-btn" id="adminBtn">STAFF</button>
            </div>

            <input type="hidden" name="role" id="roleInput" value="customer">

            <h2>LOGIN ACCOUNT</h2>

            <label>Email Address</label>
            <input type="text" name="email" placeholder="Enter your email" id="em" required>

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" id="pass" required>

            <button type="submit" name="login" class="submit" id="loginBtn">LOGIN</button>

            <p class="switch">
                Don't have an account? 
                <a href="registerPage.php">Sign Up</a>
            </p>

        </div>
    </form>

    <script>
        let customerBtn = document.getElementById("customerBtn");
        let adminBtn = document.getElementById("adminBtn");
        let roleInput = document.getElementById("roleInput");

        customerBtn.onclick = function() {
            customerBtn.classList.add("active");
            adminBtn.classList.remove("active");
            roleInput.value = "customer";
        }

        adminBtn.onclick = function() {
            adminBtn.classList.add("active");
            customerBtn.classList.remove("active");
            roleInput.value = "admin";
        }
    </script>

</body>
</html>