<?php
include 'db_connect.php';

/* Dashboard card queries */
$totalBookingsQuery = "SELECT COUNT(*) AS total_bookings FROM booking";
$totalBookingsResult = mysqli_query($conn, $totalBookingsQuery);
if (!$totalBookingsResult) { die("Metric Query 1 Failed: " . mysqli_error($conn)); }
$totalBookingsRow = mysqli_fetch_assoc($totalBookingsResult);
$totalBookings = $totalBookingsRow['total_bookings'];

$confirmedQuery = "SELECT COUNT(*) AS confirmed 
                   FROM payment 
                   WHERE PAYMENT_STATUS = 'CONFIRMED'";
$confirmedResult = mysqli_query($conn, $confirmedQuery);
if (!$confirmedResult) { die("Metric Query 2 Failed: " . mysqli_error($conn)); }
$confirmedRow = mysqli_fetch_assoc($confirmedResult);
$confirmed = $confirmedRow['confirmed'];

$pendingQuery = "SELECT COUNT(*) AS pending 
                 FROM payment 
                 WHERE PAYMENT_STATUS = 'PENDING'";
$pendingResult = mysqli_query($conn, $pendingQuery);
if (!$pendingResult) { die("Metric Query 3 Failed: " . mysqli_error($conn)); }
$pendingRow = mysqli_fetch_assoc($pendingResult);
$pending = $pendingRow['pending'];

$revenueQuery = "SELECT SUM(PAYMENT_TOTAL) AS total_revenue 
                 FROM payment 
                 WHERE PAYMENT_STATUS = 'CONFIRMED'";
$revenueResult = mysqli_query($conn, $revenueQuery);
if (!$revenueResult) { die("Metric Query 4 Failed: " . mysqli_error($conn)); }
$revenueRow = mysqli_fetch_assoc($revenueResult);
$totalRevenue = $revenueRow['total_revenue'];

if ($totalRevenue == NULL) {
    $totalRevenue = 0;
}

/* Main overview table query */
$sql = "SELECT 
            booking.BOOK_ID AS ORDER_ID,
            customer.CUS_NAME,
            venue.VENUE_NAME,
            booking.ORDER_DATE,
            package.PACKAGE_NAME,
            package.PACKAGE_PAX,
            booking.ORDER_TOTAL,
            payment.PAYMENT_METHOD,
            payment.PAYMENT_STATUS
        FROM booking
        INNER JOIN customer
            ON booking.CUS_ID = customer.CUS_ID
        INNER JOIN package
            ON booking.PACKAGE_ID = package.PACKAGE_ID
        INNER JOIN event
            ON booking.EVENT_CODE = event.EVENT_CODE
        INNER JOIN venue
            ON event.VENUE_ID = venue.VENUE_ID
        LEFT JOIN payment
            ON booking.BOOK_ID = payment.BOOK_ID";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Overview Query Failed: " . mysqli_error($conn));
}
?>

<html>
<head>
    <link rel="stylesheet" href="adminPageStyle.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="nobgLogo.png">
            <span>WARISAN NORMAN</span>
        </div>

        <div class="right-section">
            <a href="logout.php">
                <button class="btn">LOGOUT</button>
            </a>
        </div>
    </header>

    <div class="admin-layout">
        <div class="admin-sidebar">
            <h3>ADMIN PANEL</h3>

            <a class="active" href="adminPage.php">OVERVIEW</a>
            <a href="adminBookPage.php">BOOKINGS</a>
            <a href="adminVenuePage.php">VENUE</a>
            <a href="adminEventPage.php">EVENTS</a>
            <a href="adminPackPage.php">PACKAGE</a>
            <a href="adminPayPage.php">PAYMENT</a>
            <a href="adminReportPage.php">REPORTS</a>
        </div>

        <div class="admin-content">
            <h1>Dashboard Overview</h1>

            <div class="dashboard-cards">
                <div class="dash-card">
                    <h2><?php echo $totalBookings; ?></h2>
                    <p>TOTAL BOOKINGS</p>
                </div>

                <div class="dash-card">
                    <h2><?php echo $pending; ?></h2>
                    <p>PENDING</p>
                </div>

                <div class="dash-card">
                    <h2><?php echo $confirmed; ?></h2>
                    <p>CONFIRMED</p>
                </div>

                <div class="dash-card">
                    <h2>RM <?php echo number_format($totalRevenue, 2); ?></h2>
                    <p>TOTAL REVENUE</p>
                </div>
            </div>

            <table class="admin-table">
                <tr>
                    <th>Booking ID</th>
                    <th>Customer</th>
                    <th>Venue</th>
                    <th>Date</th>
                    <th>Pax</th>
                    <th>Package</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                </tr>

                <?php
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {

                        if ($row['PAYMENT_METHOD'] == NULL) {
                            $paymentMethod = "N/A";
                        } else {
                            $paymentMethod = $row['PAYMENT_METHOD'];
                        }

                        if ($row['PAYMENT_STATUS'] == "CONFIRMED") {
                            $status = "CONFIRMED";
                            $statusClass = "confirmed";
                        } 
                        else if ($row['PAYMENT_STATUS'] == "CANCELLED") {
                            $status = "CANCELLED";
                            $statusClass = "cancelled";
                        } 
                        else {
                            $status = "PENDING";
                            $statusClass = "pending";
                        }

                        echo "<tr>";
                        echo "<td>BK-" . htmlspecialchars($row['ORDER_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['CUS_NAME']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['VENUE_NAME']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ORDER_DATE']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['PACKAGE_PAX']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['PACKAGE_NAME']) . "</td>";
                        echo "<td>RM " . number_format($row['ORDER_TOTAL'], 2) . "</td>";
                        echo "<td><span class='tag qr'>" . htmlspecialchars($paymentMethod) . "</span></td>";
                        echo "<td><span class='tag " . $statusClass . "'>" . htmlspecialchars($status) . "</span></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr>";
                    echo "<td colspan='9'>No booking records found.</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>