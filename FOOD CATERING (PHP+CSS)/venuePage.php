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

$sql = "SELECT * FROM venue";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<html>
    <head>
        <link rel="stylesheet" href="venuePageStyle.css">
    </head>

    <body>
        <?php include 'components/navbar/navbar.php'; ?>

        <div class="venue-container">

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<div class="form-card vertical">';
                    echo '<img src="bgImage.jpg">';

                    echo '<h2>' . strtoupper($row['VENUE_NAME']) . '</h2>';

                    echo '<p>';
                    echo 'Beautiful Scenery<br>';
                    echo '📍 ' . $row['VENUE_LOCATION'] . '<br>';
                    echo '👥 Up to ' . $row['VENUE_CAPACITY'] . ' pax';
                    echo '</p>';

                    echo '</div>';
                }
            } else {
                echo '<div class="form-card vertical">';
                echo '<img src="bgImage.jpg">';
                echo '<h2>No venues available.</h2>';
                echo '<p>Please check again later.</p>';
                echo '</div>';
            }
            ?>

        </div>
    </body>
</html>