<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['cus_id'])) {
    header("Location: loginPage.php");
    exit();
}

if (!isset($_SESSION['last_book_id'])) {
    header("Location: homePage.php");
    exit();
}

// Securing variables by casting to integers
$cus_id = (int)$_SESSION['cus_id'];
$book_id = (int)$_SESSION['last_book_id'];

// FIXED SQL: Changed booking.EVENT_ID to booking.EVENT_CODE in the ON clause
$sql = "SELECT 
            booking.BOOK_ID,
            event.EVENT_DATE,
            event.EVENT_SESSION,
            event.EVENT_DESC AS EVENT_TYPE,
            booking.GUEST_COUNT,
            customer.CUS_NAME,
            venue.VENUE_NAME,
            venue.VENUE_LOCATION,
            package.PACKAGE_NAME,
            package.PACKAGE_PRICE,
            payment.PAYMENT_METHOD,
            payment.PAYMENT_STATUS,
            payment.PAYMENT_TOTAL
        FROM booking
        INNER JOIN customer
            ON booking.CUS_ID = customer.CUS_ID
        INNER JOIN event
            ON booking.EVENT_CODE = event.EVENT_CODE
        INNER JOIN venue
            ON event.VENUE_ID = venue.VENUE_ID
        LEFT JOIN payment
            ON booking.BOOK_ID = payment.BOOK_ID
        LEFT JOIN package
            ON booking.PACKAGE_ID = package.PACKAGE_ID
        WHERE booking.BOOK_ID = $book_id
        AND booking.CUS_ID = $cus_id";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}

if (mysqli_num_rows($result) == 0) {
    header("Location: homePage.php");
    exit();
}

$row = mysqli_fetch_assoc($result);

// Defensive Fallbacks for dynamic optional joins
$packageName   = $row['PACKAGE_NAME'] ?? 'Custom Selection / No Package';
$paymentMethod = $row['PAYMENT_METHOD'] ?? 'Not Specified';
$paymentStatus = $row['PAYMENT_STATUS'] ?? 'PENDING';
$paymentTotal  = $row['PAYMENT_TOTAL'] ?? 0.00;
?>

<html>
<head>
    <link rel="stylesheet" href="bookingPageStyle.css">
</head>

<body class="homeBg">
    <?php include 'components/navbar/navbar.php'; ?>

    <div class="booking-wrapper confirm-wrapper">
        <div class="step-indicator">
            <div class="step active">1</div>
            <div class="line"></div>
            <div class="step active">2</div>
            <div class="line"></div>
            <div class="step active">3</div>
            <div class="line"></div>
            <div class="step active">4</div>
        </div>

        <div class="confirm-page">
            <div class="confirm-text">
                <img src="bgImage.jpg" alt="Success">

                <h2>You're All Set!</h2>

                <p><b>Booking ID:</b> BK-<?php echo htmlspecialchars($row['BOOK_ID'], ENT_QUOTES); ?></p>
                <p><b>Customer:</b> <?php echo htmlspecialchars($row['CUS_NAME'], ENT_QUOTES); ?></p>
                <p><b>Venue:</b> <?php echo htmlspecialchars($row['VENUE_NAME'], ENT_QUOTES); ?></p>
                <p><b>Location:</b> <?php echo htmlspecialchars($row['VENUE_LOCATION'], ENT_QUOTES); ?></p>
                <p><b>Event Type:</b> <?php echo htmlspecialchars($row['EVENT_TYPE'], ENT_QUOTES); ?></p>
                <p><b>Date:</b> <?php echo htmlspecialchars($row['EVENT_DATE'], ENT_QUOTES); ?></p>
                <p><b>Session Slot:</b> <?php echo htmlspecialchars($row['EVENT_SESSION'], ENT_QUOTES); ?></p>
                <p><b>Expected Guest Count:</b> <?php echo htmlspecialchars($row['GUEST_COUNT'], ENT_QUOTES); ?> Pax</p>
                <p><b>Selected Package:</b> <?php echo htmlspecialchars($packageName, ENT_QUOTES); ?></p>
                <p><b>Payment Method:</b> <?php echo htmlspecialchars($paymentMethod, ENT_QUOTES); ?></p>
                <p><b>Payment Status:</b> <span class="status-badge <?php echo strtolower(htmlspecialchars($paymentStatus, ENT_QUOTES)); ?>"><?php echo htmlspecialchars($paymentStatus, ENT_QUOTES); ?></span></p>
                
                <h2>Total Paid: RM <?php echo number_format($paymentTotal, 2); ?></h2>

                <div class="confirm-actions" style="margin-top: 25px;">
                    <a href="homePage.php" class="btn proceed-btn" style="text-decoration: none; display: inline-block; text-align: center;">
                        RETURN TO HOME
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>