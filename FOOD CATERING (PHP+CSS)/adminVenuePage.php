<?php
include 'db_connect.php';

/* ADD VENUE */
if (isset($_POST['add_venue'])) {
    $venue_name     = isset($_POST['venue_name']) ? mysqli_real_escape_string($conn, $_POST['venue_name']) : '';
    $venue_location = isset($_POST['venue_location']) ? mysqli_real_escape_string($conn, $_POST['venue_location']) : '';
    $venue_capacity = isset($_POST['venue_capacity']) ? mysqli_real_escape_string($conn, $_POST['venue_capacity']) : '';
    $staff_id       = isset($_POST['staff_id']) ? trim($_POST['staff_id']) : '';

    // SERVER-SIDE SECURITY CHECK: Restricted to Staff ID 3 with an anonymous alert
    if ($staff_id !== '3') {
        echo "<script>
                alert('Access Denied! This Staff ID does not have access to add venues.');
                window.location.href = 'adminVenuePage.php';
              </script>";
        exit();
    }

    if (empty($venue_name) || empty($venue_location) || empty($venue_capacity)) {
        echo "<script>alert('Please fill in all venue details!');</script>";
    } else {
        $sql = "INSERT INTO venue 
                (VENUE_NAME, VENUE_LOCATION, VENUE_CAPACITY, STAFF_ID)
                VALUES
                ('$venue_name', '$venue_location', '$venue_capacity', '3')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Venue added successfully!');
                    window.location.href = 'adminVenuePage.php';
                  </script>";
            exit();
        } else {
            echo "Query Failed: " . mysqli_error($conn);
        }
    }
}

/* EDIT VENUE */
if (isset($_POST['edit_venue'])) {
    $venue_id       = isset($_POST['edit_venue_id']) ? mysqli_real_escape_string($conn, $_POST['edit_venue_id']) : '';
    $venue_name     = isset($_POST['edit_venue_name']) ? mysqli_real_escape_string($conn, $_POST['edit_venue_name']) : '';
    $venue_location = isset($_POST['edit_venue_location']) ? mysqli_real_escape_string($conn, $_POST['edit_venue_location']) : '';
    $venue_capacity = isset($_POST['edit_venue_capacity']) ? mysqli_real_escape_string($conn, $_POST['edit_venue_capacity']) : '';
    $staff_id       = isset($_POST['edit_staff_id']) ? trim($_POST['edit_staff_id']) : '';

    // SERVER-SIDE SECURITY CHECK: Restricted to Staff ID 3 with an anonymous alert
    if ($staff_id !== '3') {
        echo "<script>
                alert('Access Denied! This Staff ID does not have access to edit venues.');
                window.location.href = 'adminVenuePage.php';
              </script>";
        exit();
    }

    if (empty($venue_name) || empty($venue_location) || empty($venue_capacity)) {
        echo "<script>alert('Please fill in all venue details!');</script>";
    } else {
        $sql = "UPDATE venue
                SET VENUE_NAME = '$venue_name',
                    VENUE_LOCATION = '$venue_location',
                    VENUE_CAPACITY = '$venue_capacity'
                WHERE VENUE_ID = '$venue_id'";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Venue updated successfully!');
                    window.location.href = 'adminVenuePage.php';
                  </script>";
            exit();
        } else {
            echo "Query Failed: " . mysqli_error($conn);
        }
    }
}

/* DELETE VENUE */
if (isset($_POST['delete_venue'])) {
    $venue_id = isset($_POST['delete_venue_id']) ? mysqli_real_escape_string($conn, $_POST['delete_venue_id']) : '';
    $staff_id = isset($_POST['delete_staff_id']) ? trim($_POST['delete_staff_id']) : '';

    // SERVER-SIDE SECURITY CHECK: Restricted to Staff ID 3 with an anonymous alert
    if ($staff_id !== '3') {
        echo "<script>
                alert('Access Denied! This Staff ID does not have access to delete venues.');
                window.location.href = 'adminVenuePage.php';
              </script>";
        exit();
    }

    $sql = "DELETE FROM venue WHERE VENUE_ID = '$venue_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Venue deleted successfully!');
                window.location.href = 'adminVenuePage.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Cannot delete this venue because it may be used in a booking or event.');
                window.location.href = 'adminVenuePage.php';
              </script>";
        exit();
    }
}

/* DISPLAY VENUES */
$sql = "SELECT * FROM venue";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<html>
<head>
    <link rel="stylesheet" href="adminVenueStyle.css">
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
            <a class="active" href="adminVenuePage.php">VENUE</a>
            <a href="adminEventPage.php">EVENTS</a>
            <a href="adminPackPage.php">PACKAGE</a>
            <a href="adminPayPage.php">PAYMENT</a>
            <a href="adminReportPage.php">REPORTS</a>
        </div>

        <div class="admin-content">
            <div class="admin-title-row">
                <h1>MANAGE VENUES</h1>
                <button class="add-btn" onclick="addVenue()">+ ADD VENUE</button>
            </div>

            <form action="adminVenuePage.php" method="post" id="addVenueForm">
                <input type="hidden" name="venue_name" id="venue_name">
                <input type="hidden" name="venue_location" id="venue_location">
                <input type="hidden" name="venue_capacity" id="venue_capacity">
                <input type="hidden" name="staff_id" id="staff_id">
                <input type="hidden" name="add_venue" value="1">
            </form>

            <form action="adminVenuePage.php" method="post" id="editVenueForm">
                <input type="hidden" name="edit_venue_id" id="edit_venue_id">
                <input type="hidden" name="edit_venue_name" id="edit_venue_name">
                <input type="hidden" name="edit_venue_location" id="edit_venue_location">
                <input type="hidden" name="edit_venue_capacity" id="edit_venue_capacity">
                <input type="hidden" name="edit_staff_id" id="edit_staff_id">
                <input type="hidden" name="edit_venue" value="1">
            </form>

            <form action="adminVenuePage.php" method="post" id="deleteVenueForm">
                <input type="hidden" name="delete_venue_id" id="delete_venue_id">
                <input type="hidden" name="delete_staff_id" id="delete_staff_id">
                <input type="hidden" name="delete_venue" value="1">
            </form>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    $venueID = $row['VENUE_ID'];
                    $venueName = htmlspecialchars($row['VENUE_NAME'], ENT_QUOTES);
                    $venueLocation = htmlspecialchars($row['VENUE_LOCATION'], ENT_QUOTES);
                    $venueCapacity = htmlspecialchars($row['VENUE_CAPACITY'], ENT_QUOTES);

                    echo '<div class="form-card venue-admin-card">';

                    echo '<div>';
                    echo '<h2>' . $venueName . '</h2>';
                    echo '<p>📍 ' . $venueLocation . ' · 👥 ' . $venueCapacity . ' pax</p>';
                    echo '</div>';

                    echo '<div class="venue-actions">';

                    echo '<button class="edit-btn" onclick="editVenue(' 
                        . $venueID . ', \'' 
                        . $venueName . '\', \'' 
                        . $venueLocation . '\', \'' 
                        . $venueCapacity . '\')">EDIT</button>';

                    echo '<button class="delete-btn" onclick="deleteVenue(' . $venueID . ')">DELETE</button>';

                    echo '</div>';

                    echo '</div>';
                }
            } else {
                echo '<div class="form-card venue-admin-card">';
                echo '<div>';
                echo '<h2>No venues found.</h2>';
                echo '<p>Click + ADD VENUE to add your first venue.</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script>
        function addVenue() {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter your Staff ID code to unlock modifications:");
            if (staffID === null) return;

            if (staffID.trim() !== "3") {
                alert("Access Denied! This Staff ID does not have access to add venues.");
                return;
            }

            let venueName = prompt("Enter venue name:");
            if (venueName === null || venueName.trim() === "") {
                alert("Venue name cannot be empty!");
                return;
            }

            let venueLocation = prompt("Enter venue location:");
            if (venueLocation === null || venueLocation.trim() === "") {
                alert("Venue location cannot be empty!");
                return;
            }

            let venueCapacity = prompt("Enter venue capacity:");
            if (venueCapacity === null || venueCapacity.trim() === "") {
                alert("Venue capacity cannot be empty!");
                return;
            }

            if (isNaN(venueCapacity)) {
                alert("Venue capacity must be a number!");
                return;
            }

            document.getElementById("venue_name").value = venueName;
            document.getElementById("venue_location").value = venueLocation;
            document.getElementById("venue_capacity").value = venueCapacity;
            document.getElementById("staff_id").value = staffID.trim();

            document.getElementById("addVenueForm").submit();
        }

        function editVenue(id, currentName, currentLocation, currentCapacity) {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter your Staff ID code to unlock modifications:");
            if (staffID === null) return;

            if (staffID.trim() !== "3") {
                alert("Access Denied! This Staff ID does not have access to edit venues.");
                return;
            }

            let venueName = prompt("Edit venue name:", currentName);
            if (venueName === null || venueName.trim() === "") {
                alert("Venue name cannot be empty!");
                return;
            }

            let venueLocation = prompt("Edit venue location:", currentLocation);
            if (venueLocation === null || venueLocation.trim() === "") {
                alert("Venue location cannot be empty!");
                return;
            }

            let venueCapacity = prompt("Edit venue capacity:", currentCapacity);
            if (venueCapacity === null || venueCapacity.trim() === "") {
                alert("Venue capacity cannot be empty!");
                return;
            }

            if (isNaN(venueCapacity)) {
                alert("Venue capacity must be a number!");
                return;
            }

            document.getElementById("edit_venue_id").value = id;
            document.getElementById("edit_venue_name").value = venueName;
            document.getElementById("edit_venue_location").value = venueLocation;
            document.getElementById("edit_venue_capacity").value = venueCapacity;
            document.getElementById("edit_staff_id").value = staffID.trim();

            document.getElementById("editVenueForm").submit();
        }

        function deleteVenue(id) {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter your Staff ID code to approve removal execution:");
            if (staffID === null) return;

            if (staffID.trim() !== "3") {
                alert("Access Denied! This Staff ID does not have access to delete venues.");
                return;
            }

            if (confirm("Are you absolutely sure you want to permanently delete this venue?")) {
                document.getElementById("delete_venue_id").value = id;
                document.getElementById("delete_staff_id").value = staffID.trim();
                document.getElementById("deleteVenueForm").submit();
            }
        }
    </script>
</body>
</html>