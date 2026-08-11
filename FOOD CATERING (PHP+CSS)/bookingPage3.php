<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['cus_id'])) {
    header("Location: loginPage.php");
    exit();
}

if (!isset($_SESSION['booking_package_id'])) {
    header("Location: bookingPage2.php");
    exit();
}

$cus_id       = $_SESSION['cus_id'];
$venue_id     = $_SESSION['booking_venue_id'];
$event_type   = $_SESSION['booking_event_type'];
$event_date   = $_SESSION['booking_date'];
$event_session= $_SESSION['booking_session'];
$guest_count  = $_SESSION['booking_guest_count'];
$package_id   = $_SESSION['booking_package_id'];

// Fetch venue info
$venueResult = mysqli_query($conn, "SELECT * FROM venue WHERE VENUE_ID = '$venue_id'");
$venue = mysqli_fetch_assoc($venueResult);

// Fetch package info
$packageResult = mysqli_query($conn, "SELECT * FROM `package` WHERE PACKAGE_ID = '$package_id'");
$package = mysqli_fetch_assoc($packageResult);

$total = $package['PACKAGE_PRICE'];

if (isset($_POST['proceed_payment'])) {
    if (empty($_POST['payment_method'])) {
        echo "<script>alert('Please select a payment method!');</script>";
    } else {
        $payment_method = $_POST['payment_method'];
        $payment_date   = date("Y-m-d");
        $payment_depo   = 0.00;

        // 1. Check if this venue slot is already booked
        $checkQuery  = "SELECT * FROM event 
                        WHERE VENUE_ID = '$venue_id' 
                        AND EVENT_DATE = '$event_date' 
                        AND EVENT_SESSION = '$event_session'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (!$checkResult) {
            die("Availability Check Failed: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($checkResult) > 0) {
            echo "<script>
                    alert('Sorry, this venue slot has already been booked.');
                    window.location.href = 'bookingPage.php';
                  </script>";
            exit();
        }

        // 2. Generate unique EVENT_CODE (e.g. EVT-4821)
        do {
            $event_code = "EVT-" . rand(1000, 9999);
            $codeCheck  = mysqli_query($conn, "SELECT EVENT_CODE FROM event WHERE EVENT_CODE = '$event_code'");
        } while (mysqli_num_rows($codeCheck) > 0);

        // 3. Insert into event table
        $eventInsertQuery = "INSERT INTO event 
                             (EVENT_CODE, VENUE_ID, EVENT_DESC, EVENT_SESSION, EVENT_DATE, STAFF_ID) 
                             VALUES 
                             ('$event_code', '$venue_id', '$event_type', '$event_session', '$event_date', 1)";

        if (mysqli_query($conn, $eventInsertQuery)) {

            // 4. Insert into booking table
            $bookingInsertQuery = "INSERT INTO booking 
                                   (CUS_ID, PACKAGE_ID, EVENT_CODE, ORDER_TOTAL, ORDER_DATE, STAFF_ID, GUEST_COUNT) 
                                   VALUES 
                                   ('$cus_id', '$package_id', '$event_code', '$total', '$payment_date', 1, '$guest_count')";

            if (mysqli_query($conn, $bookingInsertQuery)) {
                $book_id = mysqli_insert_id($conn);

                // 5. Insert into payment table
                $paymentQuery = "INSERT INTO payment 
                                 (BOOK_ID, PAYMENT_TOTAL, PAYMENT_DATE, PAYMENT_METHOD, PAYMENT_DEPO, PAYMENT_STATUS) 
                                 VALUES 
                                 ('$book_id', '$total', '$payment_date', '$payment_method', '$payment_depo', 'PENDING')";

                if (mysqli_query($conn, $paymentQuery)) {

                    // Clear booking session data
                    unset($_SESSION['booking_venue_id']);
                    unset($_SESSION['booking_event_type']);
                    unset($_SESSION['booking_date']);
                    unset($_SESSION['booking_session']);
                    unset($_SESSION['booking_guest_count']);
                    unset($_SESSION['booking_package_id']);

                    // Save for confirmation page
                    $_SESSION['last_book_id']   = $book_id;
                    $_SESSION['last_event_code'] = $event_code;

                    echo "<script>
                            alert('Booking submitted successfully! Your payment is pending admin approval.');
                            window.location.href = 'bookingPage4.php';
                          </script>";
                    exit();

                } else {
                    die("Payment Entry Failed: " . mysqli_error($conn));
                }
            } else {
                die("Booking Entry Failed: " . mysqli_error($conn));
            }
        } else {
            die("Event Entry Failed: " . mysqli_error($conn));
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Warisan Norman</title>
    <link rel="stylesheet" href="bookingPageStyle.css">
</head>

<body>
    <?php include 'components/navbar/navbar.php'; ?>

    <div class="booking-wrapper">

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step active">1</div>
            <div class="line"></div>
            <div class="step active">2</div>
            <div class="line"></div>
            <div class="step active">3</div>
            <div class="line"></div>
            <div class="step">4</div>
        </div>

        <form action="bookingPage3.php" method="post">
            <div class="payment-layout">

                <!-- Payment Methods -->
                <div class="payment-methods">

                    <div class="form-card vertical">
                        <img src="bgImage.jpg" alt="Credit Card">
                        <input type="radio" name="payment_method" value="CARD" id="pay_card">
                        <label for="pay_card">Credit Card</label>
                    </div>

                    <div class="form-card vertical">
                        <img src="bgImage.jpg" alt="PayPal">
                        <input type="radio" name="payment_method" value="PAYPAL" id="pay_paypal">
                        <label for="pay_paypal">PayPal</label>
                    </div>

                    <div class="form-card vertical">
                        <img src="bgImage.jpg" alt="Bank Transfer">
                        <input type="radio" name="payment_method" value="BANK TRANSFER" id="pay_bank">
                        <label for="pay_bank">Bank Transfer</label>
                    </div>

                </div>

                <!-- Order Summary -->
                <div class="order-summary">

                    <h2>ORDER SUMMARY</h2>

                    <p>VENUE : <?php echo htmlspecialchars($venue['VENUE_NAME'] ?? '-'); ?></p>
                    <p>EVENT : <?php echo htmlspecialchars($event_type); ?></p>
                    <p>DATE : <?php echo htmlspecialchars($event_date); ?></p>
                    <p>SESSION : <?php echo htmlspecialchars($event_session); ?></p>
                    <p>GUESTS : <?php echo htmlspecialchars($guest_count); ?></p>
                    <p>PACKAGE : <?php echo htmlspecialchars($package['PACKAGE_NAME'] ?? '-'); ?></p>

                    <h2>TOTAL : RM <?php echo number_format($total, 2); ?></h2>

                    <button type="submit" name="proceed_payment" class="btn proceed-btn">
                        PROCEED PAYMENT
                    </button>

                </div>

            </div>
        </form>

    </div>
</body>
</html>