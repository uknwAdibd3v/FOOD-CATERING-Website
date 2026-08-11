<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['cus_name'])) {
    header("Location: loginPage.php");
    exit();
}

$fullName = $_SESSION['cus_name'];
$nameParts = explode(" ", $fullName);
$firstName = $nameParts[0];

$sql = "SELECT * FROM `package`";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<html>
    <head>
        <link rel="stylesheet" href="packagePageStyle.css">
    </head>

    <body class="homeBg">
        <?php include 'components/navbar/navbar.php'; ?>

        <div class="bHeader">
            <p>TAILORED FOR YOU</p>
            <h2>AVAILABLE PACKAGES</h2>
        </div>

        <div class="venue-container">

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    echo '<div class="form-card vertical">';
                    echo '<p>PKG' . str_pad($row['PACKAGE_ID'], 4, "0", STR_PAD_LEFT) . '</p>';
                    echo '<img src="bgImage.jpg">';
                    echo '<h2>' . strtoupper($row['PACKAGE_NAME']) . '</h2>';
                    echo '<p class="package-desc">' . $row['PACKAGE_DESC'] . '</p>';
                    echo '<p>Up to ' . $row['PACKAGE_PAX'] . ' pax</p>';
                    echo '<h2><b>RM ' . number_format($row['PACKAGE_PRICE'], 2) . '</b></h2>';
                    echo '</div>';
                }
            } else {
                echo '<div class="form-card vertical">';
                echo '<p>PKG0000</p>';
                echo '<img src="bgImage.jpg">';
                echo '<h2>No packages available.</h2>';
                echo '<p>Please check again later.</p>';
                echo '<h2><b>RM 0.00</b></h2>';
                echo '</div>';
            }
            ?>

        </div>
    </body>
</html>