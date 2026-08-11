<?php
include 'db_connect.php';

/* APPROVE PAYMENT */
if (isset($_GET['approve'])) {
    $payment_id = mysqli_real_escape_string($conn, $_GET['approve']);

    $sql = "UPDATE payment
            SET PAYMENT_STATUS = 'CONFIRMED'
            WHERE PAYMENT_ID = '$payment_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Payment approved successfully!');
                window.location.href = 'adminPayPage.php';
              </script>";
        exit();
    } else {
        echo "Query Failed: " . mysqli_error($conn);
    }
}

/* CANCEL PAYMENT */
if (isset($_GET['cancel'])) {
    $payment_id = mysqli_real_escape_string($conn, $_GET['cancel']);

    $sql = "UPDATE payment
            SET PAYMENT_STATUS = 'CANCELLED'
            WHERE PAYMENT_ID = '$payment_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Payment cancelled!');
                window.location.href = 'adminPayPage.php';
              </script>";
        exit();
    } else {
        echo "Query Failed: " . mysqli_error($conn);
    }
}

/* DISPLAY PAYMENT DATA */
// FIXED: Removed non-existent orders table references, joining payment directly through booking to customer
$sql = "SELECT 
            payment.PAYMENT_ID,
            payment.BOOK_ID,
            payment.PAYMENT_TOTAL,
            payment.PAYMENT_METHOD,
            payment.PAYMENT_STATUS,
            customer.CUS_NAME
        FROM payment
        INNER JOIN booking
            ON payment.BOOK_ID = booking.BOOK_ID
        INNER JOIN customer
            ON booking.CUS_ID = customer.CUS_ID";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<html>
<head>
    <link rel="stylesheet" href="adminPayStyle.css">
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
            <a class="active" href="adminPayPage.php">PAYMENT</a>
            <a href="adminReportPage.php">REPORTS</a>
        </div>

        <div class="admin-content">
            <h1>PAYMENT DETAILS</h1>

            <?php
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    $paymentID = $row['PAYMENT_ID'];
                    $bookID = $row['BOOK_ID'];
                    $customerName = $row['CUS_NAME'];
                    $paymentMethod = $row['PAYMENT_METHOD'];
                    $paymentTotal = $row['PAYMENT_TOTAL'];
                    $paymentStatus = $row['PAYMENT_STATUS'];

                    if (empty($customerName)) {
                        $customerName = "Unknown Customer";
                    }

                    if ($paymentStatus == "CONFIRMED") {
                        $statusClass = "confirmed";
                    } else if ($paymentStatus == "CANCELLED") {
                        $statusClass = "cancelled";
                    } else {
                        $statusClass = "pending";
                    }

                    echo '<div class="form-card payment-card">';

                    echo '<div>';
                    echo '<h2>BK-' . htmlspecialchars($bookID) . ' — ' . strtoupper(htmlspecialchars($customerName)) . '</h2>';
                    echo '<p>Method: ' . htmlspecialchars($paymentMethod) . '<br>Total: RM ' . number_format($paymentTotal, 2) . '</p>';
                    echo '</div>';

                    echo '<div class="payment-right">';
                    echo '<span class="tag ' . $statusClass . '">' . htmlspecialchars($paymentStatus) . '</span>';

                    if ($paymentStatus == "PENDING") {
                        echo '<div class="payment-actions">';

                        echo '<a href="adminPayPage.php?approve=' . htmlspecialchars($paymentID) . '" onclick="return confirm(\'Approve this payment?\');">';
                        echo '<button class="btn approve-btn">APPROVE</button>';
                        echo '</a>';

                        echo '<a href="adminPayPage.php?cancel=' . htmlspecialchars($paymentID) . '" onclick="return confirm(\'Cancel this payment?\');">';
                        echo '<button class="btn cancel-btn">CANCEL</button>';
                        echo '</a>';

                        echo '</div>';
                    }

                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="form-card payment-card">';
                echo '<div>';
                echo '<h2>No payment records found.</h2>';
                echo '<p>Payment details will appear here once customers make payments.</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>

        </div>
    </div>
</body>
</html>