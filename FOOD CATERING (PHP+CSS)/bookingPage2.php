<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['cus_id'])) {
    header("Location: loginPage.php");
    exit();
}

if (!isset($_SESSION['booking_guest_count'])) {
    header("Location: bookingPage.php");
    exit();
}

$fullName = $_SESSION['cus_name'];
$nameParts = explode(" ", $fullName);
$firstName = $nameParts[0];

$guest_count = $_SESSION['booking_guest_count'];

$packageQuery = "SELECT * FROM `package`
                 WHERE PACKAGE_PAX >= '$guest_count'
                 ORDER BY PACKAGE_PRICE ASC";

$packageResult = mysqli_query($conn, $packageQuery);

if (!$packageResult) {
    die("Query Failed: " . mysqli_error($conn));
}

if (isset($_POST['next_payment'])) {
    if (empty($_POST['package_id'])) {
        echo "<script>alert('Please select a package!');</script>";
    } else {
        $_SESSION['booking_package_id'] = $_POST['package_id'];

        header("Location: bookingPage3.php");
        exit();
    }
}
?>

<html>
<head>
    <link rel="stylesheet" href="bookingPageStyle.css">
</head>

<body>
    <?php include 'components/navbar/navbar.php'; ?>


    <div class="booking-wrapper">
        <div class="step-indicator">
            <div class="step active">1</div>
            <div class="line"></div>
            <div class="step active">2</div>
            <div class="line"></div>
            <div class="step">3</div>
            <div class="line"></div>
            <div class="step">4</div>
        </div>

        <div class="form-card booking">
            <p>CHOOSE A PACKAGE</p>

            <form action="bookingPage2.php" method="post">

                <?php
                if (mysqli_num_rows($packageResult) > 0) {
                    while ($package = mysqli_fetch_assoc($packageResult)) {

                        echo '<div class="form-card horizontal">';

                        echo '<div class="packageInfo">';
                        echo '<p>PKG' . str_pad($package['PACKAGE_ID'], 4, "0", STR_PAD_LEFT) . '</p>';

                        echo '<div>';
                        echo '<h2>' . strtoupper($package['PACKAGE_NAME']) . '</h2>';
                        echo '<p>Up to ' . $package['PACKAGE_PAX'] . ' pax</p>';
                        echo '</div>';
                        echo '</div>';

                        echo '<h2 class="price">RM ' . number_format($package['PACKAGE_PRICE'], 2) . '</h2>';
                        echo '<input type="radio" name="package_id" value="' . $package['PACKAGE_ID'] . '">';

                        echo '</div>';
                    }

                    echo '<button type="submit" name="next_payment" class="btn">NEXT</button>';
                } else {
                    echo '<div class="form-card horizontal">';
                    echo '<div class="packageInfo">';
                    echo '<div>';
                    echo '<h2>No suitable packages found.</h2>';
                    echo '<p>No package can support ' . $guest_count . ' guests.</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>

            </form>
        </div>
    </div>
</body>
</html>