<?php
include 'db_connect.php';

/* ADD EVENT */
if (isset($_POST['add_event'])) {
    $event_code    = isset($_POST['event_code']) ? mysqli_real_escape_string($conn, $_POST['event_code']) : '';
    $venue_id      = isset($_POST['venue_id']) ? mysqli_real_escape_string($conn, $_POST['venue_id']) : '';
    $event_desc    = isset($_POST['event_desc']) ? mysqli_real_escape_string($conn, $_POST['event_desc']) : '';
    $event_session = isset($_POST['event_session']) ? mysqli_real_escape_string($conn, $_POST['event_session']) : '';
    $event_date    = isset($_POST['event_date']) ? mysqli_real_escape_string($conn, $_POST['event_date']) : '';
    $staff_id      = isset($_POST['staff_id']) ? trim($_POST['staff_id']) : '';

    // CRITICAL PHP BLOCK: Drop anything that isn't exactly string '1'
    if ($staff_id !== '1') {
        echo "<script>
                alert('Access Denied! Your Staff ID ($staff_id) does not have permission to add events.');
                window.location.href = 'adminEventPage.php';
              </script>";
        exit();
    }

    if (empty($event_code) || empty($venue_id) || empty($event_desc) || empty($event_session) || empty($event_date)) {
        echo "<script>alert('Please fill in all event details!');</script>";
    } else {
        $sql = "INSERT INTO event
                (EVENT_CODE, VENUE_ID, EVENT_DESC, EVENT_SESSION, EVENT_DATE, STAFF_ID)
                VALUES
                ('$event_code', '$venue_id', '$event_desc', '$event_session', '$event_date', '1')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Event added successfully!');
                    window.location.href = 'adminEventPage.php';
                  </script>";
            exit();
        } else {
            echo "Query Failed: " . mysqli_error($conn);
        }
    }
}

/* EDIT EVENT */
if (isset($_POST['edit_event'])) {
    $old_event_code = isset($_POST['old_event_code']) ? mysqli_real_escape_string($conn, $_POST['old_event_code']) : '';
    $venue_id       = isset($_POST['edit_venue_id']) ? mysqli_real_escape_string($conn, $_POST['edit_venue_id']) : '';
    $event_desc     = isset($_POST['edit_event_desc']) ? mysqli_real_escape_string($conn, $_POST['edit_event_desc']) : '';
    $event_session  = isset($_POST['edit_event_session']) ? mysqli_real_escape_string($conn, $_POST['edit_event_session']) : '';
    $event_date     = isset($_POST['edit_event_date']) ? mysqli_real_escape_string($conn, $_POST['edit_event_date']) : '';
    $staff_id       = isset($_POST['edit_staff_id']) ? trim($_POST['edit_staff_id']) : '';

    // CRITICAL PHP BLOCK: Drop anything that isn't exactly string '1'
    if ($staff_id !== '1') {
        echo "<script>
                alert('Access Denied! Your Staff ID ($staff_id) does not have permission to edit events.');
                window.location.href = 'adminEventPage.php';
              </script>";
        exit();
    }

    if (empty($venue_id) || empty($event_desc) || empty($event_session) || empty($event_date)) {
        echo "<script>alert('Please fill in all event details!');</script>";
    } else {
        $sql = "UPDATE event
                SET VENUE_ID = '$venue_id',
                    EVENT_DESC = '$event_desc',
                    EVENT_SESSION = '$event_session',
                    EVENT_DATE = '$event_date'
                WHERE EVENT_CODE = '$old_event_code'";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Event updated successfully!');
                    window.location.href = 'adminEventPage.php';
                  </script>";
            exit();
        } else {
            echo "Query Failed: " . mysqli_error($conn);
        }
    }
}

/* DELETE EVENT */
if (isset($_POST['delete_event'])) {
    $event_code = isset($_POST['delete_event_code']) ? mysqli_real_escape_string($conn, $_POST['delete_event_code']) : '';
    $staff_id   = isset($_POST['delete_staff_id']) ? trim($_POST['delete_staff_id']) : '';

    // SERVER-SIDE SECURITY CHECK: Restricted to Staff ID 1 for events management
    if ($staff_id !== '1') {
        echo "<script>
                alert('Access Denied! Your Staff ID does not have permission to delete events.');
                window.location.href = 'adminEventPage.php';
              </script>";
        exit();
    }

    $sql = "DELETE FROM event WHERE EVENT_CODE = '$event_code'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Event deleted successfully!');
                window.location.href = 'adminEventPage.php';
              </script>";
        exit();
    } else {
        echo "Query Failed: " . mysqli_error($conn);
    }
}

/* DISPLAY EVENTS */
$sql = "SELECT 
            event.EVENT_CODE,
            event.VENUE_ID,
            event.EVENT_DESC,
            event.EVENT_SESSION,
            event.EVENT_DATE,
            event.STAFF_ID,
            venue.VENUE_NAME
        FROM event
        LEFT JOIN venue
        ON event.VENUE_ID = venue.VENUE_ID";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<html>
<head>
    <link rel="stylesheet" href="adminEventStyle.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="nobgLogo.png">
            <span>WARISAN NORMAN</span>
        </div>
        <div class="right-section">
            <a href="adminPage.php"><button class="btn">DASHBOARD</button></a>
        </div>
    </header>

    <div class="admin-layout">
        <div class="admin-sidebar">
            <h3>ADMIN PANEL</h3>
            <a href="adminPage.php">OVERVIEW</a>
            <a href="adminBookPage.php">BOOKINGS</a>
            <a href="adminVenuePage.php">VENUE</a>
            <a class="active" href="adminEventPage.php">EVENTS</a>
            <a href="adminPackPage.php">PACKAGE</a>
            <a href="adminPayPage.php">PAYMENT</a>
            <a href="adminReportPage.php">REPORTS</a>
        </div>

        <div class="admin-content">
            <div class="admin-title-row">
                <h1>MANAGE EVENTS</h1>
                <button class="btn add-btn" onclick="addEvent()">+ ADD EVENT</button>
            </div>

            <form action="adminEventPage.php" method="post" id="addEventForm">
                <input type="hidden" name="event_code" id="event_code">
                <input type="hidden" name="venue_id" id="venue_id">
                <input type="hidden" name="event_desc" id="event_desc">
                <input type="hidden" name="event_session" id="event_session">
                <input type="hidden" name="event_date" id="event_date">
                <input type="hidden" name="staff_id" id="staff_id">
                <input type="hidden" name="add_event" value="1">
            </form>

            <form action="adminEventPage.php" method="post" id="editEventForm">
                <input type="hidden" name="old_event_code" id="old_event_code">
                <input type="hidden" name="edit_venue_id" id="edit_venue_id">
                <input type="hidden" name="edit_event_desc" id="edit_event_desc">
                <input type="hidden" name="edit_event_session" id="edit_event_session">
                <input type="hidden" name="edit_event_date" id="edit_event_date">
                <input type="hidden" name="edit_staff_id" id="edit_staff_id">
                <input type="hidden" name="edit_event" value="1">
            </form>

            <form action="adminEventPage.php" method="post" id="deleteEventForm">
                <input type="hidden" name="delete_event_code" id="delete_event_code">
                <input type="hidden" name="delete_staff_id" id="delete_staff_id">
                <input type="hidden" name="delete_event" value="1">
            </form>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $eventCode = htmlspecialchars($row['EVENT_CODE'], ENT_QUOTES);
                    $venueID = htmlspecialchars($row['VENUE_ID'], ENT_QUOTES);
                    $eventDesc = htmlspecialchars($row['EVENT_DESC'], ENT_QUOTES);
                    $eventSession = htmlspecialchars($row['EVENT_SESSION'], ENT_QUOTES);
                    $eventDate = htmlspecialchars($row['EVENT_DATE'], ENT_QUOTES);
                    $staffID = htmlspecialchars($row['STAFF_ID'], ENT_QUOTES);
                    $venueName = htmlspecialchars($row['VENUE_NAME'], ENT_QUOTES);

                    echo '<div class="form-card venue-admin-card">';
                    echo '<div>';
                    echo '<h2>' . $eventDesc . '</h2>';
                    echo '<p>📍 ' . $venueName . ' · 🗓️ ' . $eventDate . ' · 🕙 ' . $eventSession . '</p>';
                    echo '</div>';

                    echo '<div class="venue-actions">';
                    echo '<button class="edit-btn" onclick="editEvent(\''
                        . $eventCode . '\', \''
                        . $venueID . '\', \''
                        . $eventDesc . '\', \''
                        . $eventSession . '\', \''
                        . $eventDate . '\', \''
                        . $staffID . '\')">EDIT</button>';

                    // FIXED: Replaced open link reference with the safe js trigger function 
                    echo '<button class="delete-btn" onclick="deleteEvent(\'' . $eventCode . '\')">DELETE</button>';
                    
                    echo '<span class="event-code">' . $eventCode . '</span>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="form-card venue-admin-card"><div><h2>No events found.</h2></div></div>';
            }
            ?>
        </div>
    </div>

    <script>
        function addEvent() {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter your Staff ID code to unlock adding options:");
            if (staffID === null) return;
            
            if (staffID.trim() !== "1") {
                alert("Access Denied! This Staff ID does not have access to add Event.");
                return;
            }

            let eventCode = prompt("Enter new unique event code:");
            if (!eventCode) return;

            let venueID = prompt("Enter venue numerical ID:");
            if (!venueID || isNaN(venueID)) return;

            let eventDesc = prompt("Enter event description title:");
            if (!eventDesc) return;

            let eventSession = prompt("Enter session schedule (e.g., 10:00 AM - 10:00 PM):");
            if (!eventSession) return;

            let eventDate = prompt("Enter target date (YYYY-MM-DD):");
            if (!eventDate) return;

            document.getElementById("event_code").value = eventCode;
            document.getElementById("venue_id").value = venueID;
            document.getElementById("event_desc").value = eventDesc;
            document.getElementById("event_session").value = eventSession;
            document.getElementById("event_date").value = eventDate;
            document.getElementById("staff_id").value = staffID.trim();

            document.getElementById("addEventForm").submit();
        }

        function editEvent(currentCode, currentVenueID, currentDesc, currentSession, currentDate, currentStaff) {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter Staff ID authorizing this edit request:");
            if (staffID === null) return;
            
            if (staffID.trim() !== "1") {
                alert("Access Denied! Only Staff ID 1 can modify event files.");
                return;
            }

            let venueID = prompt("Modify venue tracking identification ID:", currentVenueID);
            if (!venueID || isNaN(venueID)) return;

            let eventDesc = prompt("Modify description details:", currentDesc);
            if (!eventDesc) return;

            let eventSession = prompt("Modify time window blocks:", currentSession);
            if (!eventSession) return;

            let eventDate = prompt("Modify calendar target date:", currentDate);
            if (!eventDate) return;

            document.getElementById("old_event_code").value = currentCode;
            document.getElementById("edit_venue_id").value = venueID;
            document.getElementById("edit_event_desc").value = eventDesc;
            document.getElementById("edit_event_session").value = eventSession;
            document.getElementById("edit_event_date").value = eventDate;
            document.getElementById("edit_staff_id").value = staffID.trim();

            document.getElementById("editEventForm").submit();
        }

        // SECURE UPGRADE: Delete authentication intercept logic handler
        function deleteEvent(code) {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter your Staff ID code to approve removal execution:");
            if (staffID === null) return;

            if (staffID.trim() !== "1") {
                alert("Access Denied! This Staff ID does not have access to delete events.");
                return;
            }

            if (confirm("Are you absolutely sure you want to permanently delete this event listing?")) {
                document.getElementById("delete_event_code").value = code;
                document.getElementById("delete_staff_id").value = staffID.trim();
                document.getElementById("deleteEventForm").submit();
            }
        }
    </script>
</body>
</html>