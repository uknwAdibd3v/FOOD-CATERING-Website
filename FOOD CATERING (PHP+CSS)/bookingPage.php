<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['cus_id'])) {
    header("Location: loginPage.php");
    exit();
}

$fullName = $_SESSION['cus_name'];
$nameParts = explode(" ", $fullName);
$firstName = $nameParts[0];

$message = "";
$messageColor = "";

$venue_id = "";
$event_type = "";
$event_date = "";
$event_session = "";
$guest_count = "";

$venueQuery = "SELECT * FROM venue";
$venueResult = mysqli_query($conn, $venueQuery);

if (isset($_POST['check_availability']) || isset($_POST['next_page'])) {
    $venue_id = $_POST['venue_id'];
    $event_type = $_POST['event_type'];
    $event_date = $_POST['event_date'];
    $event_session = $_POST['event_session'];
    $guest_count = $_POST['guest_count'];

    if (empty($venue_id) || empty($event_type) || empty($event_date) 
        || empty($event_session) || empty($guest_count)) {
        $message = "❌ Please fill in all fields!";
        $messageColor = "red";
    } 
    else if ($event_date < date("Y-m-d")) {
        $message = "❌ You cannot book a past date!";
        $messageColor = "red";
    } 
    else {
        $capacityQuery = "SELECT VENUE_CAPACITY FROM venue WHERE VENUE_ID = '$venue_id'";
        $capacityResult = mysqli_query($conn, $capacityQuery);
        $capacityRow = mysqli_fetch_assoc($capacityResult);

        if ($guest_count > $capacityRow['VENUE_CAPACITY']) {
            $message = "❌ Number of guests exceeds venue capacity!";
            $messageColor = "red";
        } 
        else {
            // Query the event table instead of the booking table, using the correct column names
            $checkQuery = "SELECT * FROM event
                        WHERE VENUE_ID = '$venue_id'
                        AND EVENT_DATE = '$event_date'
                        AND EVENT_SESSION = '$event_session'";

            $checkResult = mysqli_query($conn, $checkQuery);

            if (!$checkResult) {
                die("Query Failed: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($checkResult) > 0) {
                $message = "❌ Venue is not available for this date and session!";
                $messageColor = "red";
            }
            else {
                $message = "✅ Venue is available!";
                $messageColor = "green";

                if (isset($_POST['next_page'])) {
                    $_SESSION['booking_venue_id'] = $venue_id;
                    $_SESSION['booking_event_type'] = $event_type;
                    $_SESSION['booking_date'] = $event_date;
                    $_SESSION['booking_session'] = $event_session;
                    $_SESSION['booking_guest_count'] = $guest_count;

                    header("Location: bookingPage2.php");
                    exit();
                }
            }
        }
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
            <div class="step">2</div>
            <div class="line"></div>
            <div class="step">3</div>
            <div class="line"></div>
            <div class="step">4</div>
        </div>

        <div class="form-card booking">
            <form action="bookingPage.php" method="post">
                <div class="booking-form">

                    <label>VENUE</label>
                    <select name="venue_id">
                        <option value="">-SELECT VENUE-</option>

                        <?php
                        while ($venue = mysqli_fetch_assoc($venueResult)) {
                            $selected = "";

                            if ($venue_id == $venue['VENUE_ID']) {
                                $selected = "selected";
                            }

                            echo '<option value="' . $venue['VENUE_ID'] . '" ' . $selected . '>' . $venue['VENUE_NAME'] . '</option>';
                        }
                        ?>
                    </select>

                    <label>EVENT TYPE</label>
                    <select name="event_type">
                        <option value="">-SELECT EVENT TYPE-</option>

                        <option value="Wedding" <?php if ($event_type == "Wedding") echo "selected"; ?>>
                            Wedding
                        </option>

                        <option value="Birthday" <?php if ($event_type == "Birthday") echo "selected"; ?>>
                            Birthday
                        </option>

                        <option value="Corporate" <?php if ($event_type == "Corporate") echo "selected"; ?>>
                            Corporate
                        </option>

                        <option value="Engagement" <?php if ($event_type == "Engagement") echo "selected"; ?>>
                            Engagement
                        </option>

                        <option value="Dinner" <?php if ($event_type == "Dinner") echo "selected"; ?>>
                            Dinner
                        </option>
                    </select>

                    <label>EVENT DATE</label>
                    <input type="date" name="event_date" placeholder="Select Date" value="<?php echo $event_date; ?>">

                    <label>EVENT SESSION</label>
                    <select name="event_session">
                        <option value="">-SELECT SESSION-</option>

                        <option value="Morning: 10:00 AM - 2:00 PM" <?php if ($event_session == "Morning: 10:00 AM - 2:00 PM") echo "selected"; ?>>
                            Morning: 10:00 AM - 2:00 PM
                        </option>

                        <option value="Evening: 5:00 PM - 11:00 PM" <?php if ($event_session == "Evening: 5:00 PM - 11:00 PM") echo "selected"; ?>>
                            Evening: 5:00 PM - 11:00 PM
                        </option>

                        <option value="Full Day: 10:00 AM - 10:00 PM" <?php if ($event_session == "Full Day: 10:00 AM - 10:00 PM") echo "selected"; ?>>
                            Full Day: 10:00 AM - 10:00 PM
                        </option>
                    </select>

                    <label>NUMBER OF GUESTS</label>
                    <input type="number" name="guest_count" placeholder="0" value="<?php echo $guest_count; ?>">

                    <div class="booking-buttons">
                        <button type="submit" name="check_availability" class="btn">
                            CHECK AVAILABILITY
                        </button>

                        <button type="submit" name="next_page" class="btn">
                            SELECT PACKAGES →
                        </button>
                    </div>

                    <p class="available" style="color: <?php echo $messageColor; ?>">
                        <?php echo $message; ?>
                    </p>

                </div>
            </form>
        </div>
    </div>
</body>
</html>