<?php
include 'db_connect.php';

// FIXED: Adjusted base table from 'book' to 'booking' and aligned relationships with 'event' and 'package'
$sql = "SELECT 
            booking.BOOK_ID,
            customer.CUS_NAME,
            venue.VENUE_NAME,
            booking.ORDER_DATE,
            package.PACKAGE_PAX,
            package.PACKAGE_NAME,
            booking.ORDER_TOTAL,
            payment.PAYMENT_METHOD,
            payment.PAYMENT_STATUS
        FROM booking
        INNER JOIN customer 
            ON booking.CUS_ID = customer.CUS_ID
        INNER JOIN package 
            ON booking.PACKAGE_ID = package.PACKAGE_ID
        INNER JOIN event 
            ON booking.EVENT_CODE= event.EVENT_CODE
        INNER JOIN venue 
            ON event.VENUE_ID = venue.VENUE_ID
        LEFT JOIN payment 
            ON booking.BOOK_ID = payment.BOOK_ID";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<html>
<head>
    <link rel="stylesheet" href="adminBookStyle.css">
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
            <a class="active" href="adminBookPage.php">BOOKINGS</a>
            <a href="adminVenuePage.php">VENUE</a>
            <a href="adminEventPage.php">EVENTS</a>
            <a href="adminPackPage.php">PACKAGE</a>
            <a href="adminPayPage.php">PAYMENT</a>
            <a href="adminReportPage.php">REPORTS</a>
        </div>

        <div class="admin-content">
            <h1>ALL BOOKINGS</h1>

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

                        if (empty($row['PAYMENT_METHOD'])) {
                            $paymentMethod = "N/A";
                            $paymentClass = "pending";
                        } else {
                            $paymentMethod = $row['PAYMENT_METHOD'];
                            $paymentClass = strtolower($row['PAYMENT_METHOD']);

                            if ($paymentMethod == "QR") {
                                $paymentClass = "qr";
                            } else if ($paymentMethod == "CARD") {
                                $paymentClass = "card-pay";
                            } else if ($paymentMethod == "BANK TRANSFER") {
                                $paymentClass = "bank";
                            } else if ($paymentMethod == "PAYPAL") {
                                $paymentClass = "paypal";
                            }
                        }

                        if ($row['PAYMENT_STATUS'] == "CONFIRMED") {
                            $status = "CONFIRMED";
                            $statusClass = "confirmed";
                        } else if ($row['PAYMENT_STATUS'] == "CANCELLED") {
                            $status = "CANCELLED";
                            $statusClass = "cancelled";
                        } else {
                            $status = "PENDING";
                            $statusClass = "pending";
                        }

                        echo "<tr>";
                        echo "<td>BK-" . htmlspecialchars($row['BOOK_ID']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['CUS_NAME'] ?? "N/A") . "</td>";
                        echo "<td>" . htmlspecialchars($row['VENUE_NAME'] ?? "N/A") . "</td>";
                        echo "<td>" . htmlspecialchars($row['ORDER_DATE'] ?? "N/A") . "</td>";
                        echo "<td>" . htmlspecialchars($row['PACKAGE_PAX'] ?? "N/A") . "</td>";
                        echo "<td>" . htmlspecialchars($row['PACKAGE_NAME'] ?? "N/A") . "</td>";

                        if ($row['ORDER_TOTAL'] == NULL) {
                            echo "<td>RM 0.00</td>";
                        } else {
                            echo "<td>RM " . number_format($row['ORDER_TOTAL'], 2) . "</td>";
                        }

                        echo "<td><span class='tag " . $paymentClass . "'>" . htmlspecialchars($paymentMethod) . "</span></td>";
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