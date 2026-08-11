<?php
include 'db_connect.php';

/* TOTAL REVENUE */
$totalRevenueQuery = "SELECT SUM(PAYMENT_TOTAL) AS total_revenue FROM payment WHERE PAYMENT_STATUS = 'CONFIRMED'";
$totalRevenueResult = mysqli_query($conn, $totalRevenueQuery);
if (!$totalRevenueResult) { die("Revenue Query Failed: " . mysqli_error($conn)); }
$totalRevenueRow = mysqli_fetch_assoc($totalRevenueResult);
$totalRevenue = $totalRevenueRow['total_revenue'];

if ($totalRevenue == NULL) {
    $totalRevenue = 0;
}

/* AVG PAX PER BOOKING */
// FIXED: Changed package.PACKAGE_PAX to booking.GUEST_COUNT based on your real schema
$avgPaxQuery = "SELECT AVG(GUEST_COUNT) AS avg_pax FROM booking";
$avgPaxResult = mysqli_query($conn, $avgPaxQuery);
if (!$avgPaxResult) { die("Avg Pax Query Failed: " . mysqli_error($conn)); }
$avgPaxRow = mysqli_fetch_assoc($avgPaxResult);
$avgPax = $avgPaxRow['avg_pax'];

if ($avgPax == NULL) {
    $avgPax = 0;
}

/* TOP VENUE */
// FIXED: Aligned foreign key relationship path linking booking -> event -> venue
$topVenueQuery = "SELECT venue.VENUE_NAME, COUNT(booking.BOOK_ID) AS total_booked
                  FROM booking
                  INNER JOIN event
                  ON booking.EVENT_CODE = event.EVENT_CODE
                  INNER JOIN venue
                  ON event.VENUE_ID = venue.VENUE_ID
                  GROUP BY venue.VENUE_ID
                  ORDER BY total_booked DESC
                  LIMIT 1";

$topVenueResult = mysqli_query($conn, $topVenueQuery);
if (!$topVenueResult) { die("Top Venue Query Failed: " . mysqli_error($conn)); }

if (mysqli_num_rows($topVenueResult) > 0) {
    $topVenueRow = mysqli_fetch_assoc($topVenueResult);
    $topVenue = $topVenueRow['VENUE_NAME'];
} else {
    $topVenue = "N/A";
}

/* TOP PACKAGE */
$topPackageQuery = "SELECT package.PACKAGE_NAME, COUNT(booking.BOOK_ID) AS total_ordered
                    FROM booking
                    INNER JOIN package
                    ON booking.PACKAGE_ID = package.PACKAGE_ID
                    GROUP BY package.PACKAGE_ID
                    ORDER BY total_ordered DESC
                    LIMIT 1";

$topPackageResult = mysqli_query($conn, $topPackageQuery);
if (!$topPackageResult) { die("Top Package Query Failed: " . mysqli_error($conn)); }

if (mysqli_num_rows($topPackageResult) > 0) {
    $topPackageRow = mysqli_fetch_assoc($topPackageResult);
    $topPackage = $topPackageRow['PACKAGE_NAME'];
} else {
    $topPackage = "N/A";
}

/* BOOKING BREAKDOWN */
$breakdownQuery = "SELECT 
                        customer.CUS_NAME,
                        package.PACKAGE_NAME,
                        booking.ORDER_TOTAL,
                        payment.PAYMENT_STATUS
                   FROM booking
                   INNER JOIN customer
                   ON booking.CUS_ID = customer.CUS_ID
                   INNER JOIN package
                   ON booking.PACKAGE_ID = package.PACKAGE_ID
                   LEFT JOIN payment
                   ON booking.BOOK_ID = payment.BOOK_ID";

$breakdownResult = mysqli_query($conn, $breakdownQuery);
if (!$breakdownResult) {
    die("Breakdown Query Failed: " . mysqli_error($conn));
}
?>

<html>
<head>
    <link rel="stylesheet" href="adminReportStyle.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="nobgLogo.png">
            <span>WARISAN NORMAN</span>
        </div>

        <div class="right-section">
            <a href="adminPage.php">
                <button class="btn">DASHBOARD</button>
            </a>
        </div>
    </header>

    <div class="admin-layout">
        <div class="admin-sidebar">
            <h3>ADMIN PANEL</h3>

            <a href="adminPage.php">OVERVIEW</a>
            <a href="adminBookPage.php">BOOKINGS</a>
            <a href="adminVenuePage.php">VENUE</a>
            <a href="adminEventPage.php">EVENTS</a>
            <a href="adminPackPage.php">PACKAGE</a>
            <a href="adminPayPage.php">PAYMENT</a>
            <a class="active" href="adminReportPage.php">REPORTS</a>
        </div>

        <div class="admin-content">
            <h1>REPORTS</h1>

            <div class="report-cards">
                <div class="form-card report-card">
                    <p>TOTAL REVENUE</p>
                    <h2>RM <?php echo number_format($totalRevenue, 2); ?></h2>
                    <h3>CONFIRMED PAYMENTS</h3>
                </div>

                <div class="form-card report-card">
                    <p>AVG PAX PER BOOKING</p>
                    <h2><?php echo round($avgPax); ?></h2>
                    <h3>GUEST</h3>
                </div>

                <div class="form-card report-card">
                    <p>TOP VENUE</p>
                    <h2><?php echo strtoupper(htmlspecialchars($topVenue)); ?></h2>
                    <h3>MOST BOOKED</h3>
                </div>

                <div class="form-card report-card">
                    <p>TOP PACKAGE</p>
                    <h2><?php echo strtoupper(htmlspecialchars($topPackage)); ?></h2>
                    <h3>FAVOURITE CHOICE</h3>
                </div>
            </div>

            <div class="form-card report-breakdown">
                <h2>BOOKING BREAKDOWN</h2>

                <?php
                if ($breakdownResult && mysqli_num_rows($breakdownResult) > 0) {
                    while ($row = mysqli_fetch_assoc($breakdownResult)) {

                        $customerName = $row['CUS_NAME'];
                        $packageName = $row['PACKAGE_NAME'];
                        $orderTotal = $row['ORDER_TOTAL'];
                        $paymentStatus = $row['PAYMENT_STATUS'];

                        if (empty($paymentStatus)) {
                            $paymentStatus = "PENDING";
                            $statusClass = "pending";
                        } 
                        else if ($paymentStatus == "CONFIRMED") {
                            $statusClass = "confirmed";
                        } 
                        else if ($paymentStatus == "CANCELLED") {
                            $statusClass = "cancelled";
                        } 
                        else {
                            $statusClass = "pending";
                        }

                        echo '<div class="report-row">';
                        echo '<span>' . strtoupper(htmlspecialchars($customerName)) . '</span>';
                        echo '<span>' . strtoupper(htmlspecialchars($packageName)) . '</span>';
                        echo '<span>RM ' . number_format($orderTotal, 2) . '</span>';
                        echo '<span class="tag ' . $statusClass . '">' . htmlspecialchars($paymentStatus) . '</span>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="report-row">';
                    echo '<span>No booking records found.</span>';
                    echo '<span>-</span>';
                    echo '<span>RM 0.00</span>';
                    echo '<span class="tag pending">N/A</span>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>