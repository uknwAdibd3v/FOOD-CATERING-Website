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

/* DISPLAY ONLY UPCOMING EVENTS */
$sql = "SELECT 
            event.EVENT_CODE,
            event.EVENT_DESC,
            event.EVENT_SESSION,
            event.EVENT_DATE,
            venue.VENUE_NAME
        FROM event
        LEFT JOIN venue
        ON event.VENUE_ID = venue.VENUE_ID
        WHERE event.EVENT_DATE >= CURDATE()
        ORDER BY event.EVENT_DATE ASC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<html>
    <head>
        <link rel="stylesheet" href="eventPageStyle.css">
    </head>

    <body class="homeBg">
        
        <?php include 'components/navbar/navbar.php'; ?>


        <div class="bHeader">
            <p>UPCOMING</p>
            <h2>EVENTS CALENDAR</h2>
        </div>

        <div class="event-container">

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    echo '<div class="form-card horizontal">';

                    echo '<img src="bgImage.jpg">';

                    echo '<h2>' . strtoupper($row['EVENT_DESC']) . '</h2>';

                    echo '<p>';
                    echo '📍 ' . strtoupper($row['VENUE_NAME']) . '<br>';
                    echo '📅 ' . $row['EVENT_DATE'] . '<br>';
                    echo '🕐 ' . $row['EVENT_SESSION'];
                    echo '</p>';

                    echo '<button class="btn">' . $row['EVENT_CODE'] . '</button>';

                    echo '</div>';
                }
            } else {
                echo '<div class="form-card horizontal">';
                echo '<img src="bgImage.jpg">';
                echo '<h2>No upcoming events.</h2>';
                echo '<p>Please check again later.</p>';
                echo '<button class="btn">N/A</button>';
                echo '</div>';
            }
            ?>

        </div>
    </body>
</html>