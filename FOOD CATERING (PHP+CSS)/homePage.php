<?php
session_start();

if (!isset($_SESSION['cus_id'])) {
    header("Location: loginPage.php");
    exit();
}

$fullName = $_SESSION['cus_name'];
$nameParts = explode(" ", $fullName);
$firstName = $nameParts[0];
?>

<html>
    <head>
        <!-- FIXED: Changed link to match your actual file name seen in the screenshots -->
        <link rel="stylesheet" href="homePageStyle.css">
    </head>

    <body class="homeBg">
        <?php include 'components/navbar/navbar.php'; ?>

        <section class="homeText">
            <h1 class="welcome-user">WELCOME, <?php echo htmlspecialchars(strtoupper($firstName)); ?>!</h1>
            <h2>EFFORTLESSLY BOOKED, WITH JUST A CLICK.</h2>
            <h3>BOOK YOUR CATERING NOW!</h3>

            <div class="bookNow">
                <a href="bookingPage.php" class="btn">BOOK NOW!</a>
            </div>
        </section>
    </body>
</html>