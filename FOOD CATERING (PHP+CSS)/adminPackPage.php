<?php
include 'db_connect.php';

/* ADD PACKAGE */
if (isset($_POST['add_package'])) {
    $package_desc  = isset($_POST['package_desc']) ? mysqli_real_escape_string($conn, $_POST['package_desc']) : '';
    $package_price = isset($_POST['package_price']) ? mysqli_real_escape_string($conn, $_POST['package_price']) : '';
    $package_pax   = isset($_POST['package_pax']) ? mysqli_real_escape_string($conn, $_POST['package_pax']) : '';
    $staff_id      = isset($_POST['staff_id']) ? trim($_POST['staff_id']) : '';
    $package_name  = isset($_POST['package_name']) ? mysqli_real_escape_string($conn, $_POST['package_name']) : '';
    // SERVER-SIDE SECURITY CHECK: Secretly look for ID 2 without mentioning it in the error
    if ($staff_id !== '2') {
        echo "<script>
                alert('Access Denied! This Staff ID does not have access to add packages.');
                window.location.href = 'adminPackPage.php';
              </script>";
        exit();
    }

    if (empty($package_desc) || empty($package_price) || empty($package_pax)) {
        echo "<script>alert('Please fill in all package details!');</script>";
    } else {
        $sql = "INSERT INTO `package`
                (PACKAGE_DESC, PACKAGE_PRICE, PACKAGE_PAX, STAFF_ID, PACKAGE_NAME)
                VALUES
                ('$package_desc', '$package_price', '$package_pax', '2', '$package_name')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Package added successfully!');
                    window.location.href = 'adminPackPage.php';
                  </script>";
            exit();
        } else {
            echo "Query Failed: " . mysqli_error($conn);
        }
    }
}

/* EDIT PACKAGE */
if (isset($_POST['edit_package'])) {
    $package_id    = isset($_POST['edit_package_id']) ? mysqli_real_escape_string($conn, $_POST['edit_package_id']) : '';
    $package_desc  = isset($_POST['edit_package_desc']) ? mysqli_real_escape_string($conn, $_POST['edit_package_desc']) : '';
    $package_price = isset($_POST['edit_package_price']) ? mysqli_real_escape_string($conn, $_POST['edit_package_price']) : '';
    $package_pax   = isset($_POST['edit_package_pax']) ? mysqli_real_escape_string($conn, $_POST['edit_package_pax']) : '';
    $package_name   = isset($_POST['edit_package_name']) ? mysqli_real_escape_string($conn, $_POST['edit_package_name']) : '';
    $staff_id      = isset($_POST['edit_staff_id']) ? trim($_POST['edit_staff_id']) : '';

    // SERVER-SIDE SECURITY CHECK: Secretly look for ID 2 without mentioning it in the error
    if ($staff_id !== '2') {
        echo "<script>
                alert('Access Denied! This Staff ID does not have access to edit packages.');
                window.location.href = 'adminPackPage.php';
              </script>";
        exit();
    }

    if (empty($package_desc) || empty($package_price) || empty($package_pax) || empty($package_name)) {
        echo "<script>alert('Please fill in all package details!');</script>";
    } else {
        $sql = "UPDATE `package`
                SET PACKAGE_DESC = '$package_desc',
                    PACKAGE_PRICE = '$package_price',
                    PACKAGE_PAX = '$package_pax',
                    PACKAGE_NAME = '$package_name'
                WHERE PACKAGE_ID = '$package_id'";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                    alert('Package updated successfully!');
                    window.location.href = 'adminPackPage.php';
                  </script>";
            exit();
        } else {
            echo "Query Failed: " . mysqli_error($conn);
        }
    }
}

/* DELETE PACKAGE */
if (isset($_POST['delete_package'])) {
    $package_id = isset($_POST['delete_package_id']) ? mysqli_real_escape_string($conn, $_POST['delete_package_id']) : '';
    $staff_id   = isset($_POST['delete_staff_id']) ? trim($_POST['delete_staff_id']) : '';

    // SERVER-SIDE SECURITY CHECK: Restricted to Staff ID 2 with an anonymous alert
    if ($staff_id !== '2') {
        echo "<script>
                alert('Access Denied! This Staff ID does not have access to delete packages.');
                window.location.href = 'adminPackPage.php';
              </script>";
        exit();
    }

    $sql = "DELETE FROM `package` WHERE PACKAGE_ID = '$package_id'";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('Package deleted successfully!');
                window.location.href = 'adminPackPage.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Cannot delete this package because it may be used in an order.');
                window.location.href = 'adminPackPage.php';
              </script>";
        exit();
    }
}

/* DISPLAY PACKAGES */
$sql = "SELECT * FROM `package`";
$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Failed: " . mysqli_error($conn));
}
?>

<html>
<head>
    <link rel="stylesheet" href="adminPackStyle.css">
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
            <a class="active" href="adminPackPage.php">PACKAGE</a>
            <a href="adminPayPage.php">PAYMENT</a>
            <a href="adminReportPage.php">REPORTS</a>
        </div>

        <div class="admin-content">
            <div class="admin-title-row">
                <h1>MANAGE PACKAGES</h1>
                <button class="btn add-btn" onclick="addPackage()">+ ADD PACKAGE</button>
            </div>

            <form action="adminPackPage.php" method="post" id="addPackageForm">
                <input type="hidden" name="package_name" id="package_name">
                <input type="hidden" name="package_desc" id="package_desc">
                <input type="hidden" name="package_price" id="package_price">
                <input type="hidden" name="package_pax" id="package_pax">
                <input type="hidden" name="staff_id" id="staff_id">
                <input type="hidden" name="add_package" value="1">
            </form>

            <form action="adminPackPage.php" method="post" id="editPackageForm">
                <input type="hidden" name="edit_package_id" id="edit_package_id">
                <input type="hidden" name="edit_package_name" id="edit_package_name">
                <input type="hidden" name="edit_package_desc" id="edit_package_desc">
                <input type="hidden" name="edit_package_price" id="edit_package_price">
                <input type="hidden" name="edit_package_pax" id="edit_package_pax">
                <input type="hidden" name="edit_staff_id" id="edit_staff_id">
                <input type="hidden" name="edit_package" value="1">
            </form>

            <form action="adminPackPage.php" method="post" id="deletePackageForm">
                <input type="hidden" name="delete_package_id" id="delete_package_id">
                <input type="hidden" name="delete_staff_id" id="delete_staff_id">
                <input type="hidden" name="delete_package" value="1">
            </form>

            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {

                    $packageID = $row['PACKAGE_ID'];
                    $packageDesc = htmlspecialchars($row['PACKAGE_DESC'], ENT_QUOTES);
                    $packagePrice = htmlspecialchars($row['PACKAGE_PRICE'], ENT_QUOTES);
                    $packagePax = htmlspecialchars($row['PACKAGE_PAX'], ENT_QUOTES);
                    $packageName = htmlspecialchars($row['PACKAGE_NAME'], ENT_QUOTES);

                    echo '<div class="form-card venue-admin-card">';

                    echo '<div>';
                    echo '<p>' . $packageName . '</p>';
                    echo '<p>RM ' . number_format($packagePrice, 2) . ' · Up to ' . $packagePax . ' pax</p>';
                    echo '</div>';

                    echo '<div class="venue-actions">';

                    echo '<button class="edit-btn" onclick="editPackage('
                        . $packageID . ', \''
                        . $packageName . '\', \''
                        . $packageDesc . '\', \''
                        . $packagePrice . '\', \''
                        . $packagePax . '\')">EDIT</button>';

                    // FIXED: Replaced structural query link with clean JS function verification trigger
                    echo '<button class="delete-btn" onclick="deletePackage(' . $packageID . ')">DELETE</button>';

                    echo '</div>';

                    echo '</div>';
                }
            } else {
                echo '<div class="form-card venue-admin-card">';
                echo '<div>';
                echo '<h2>No packages found.</h2>';
                echo '<p>Click + ADD PACKAGE to add your first package.</p>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
    </div>

    <script>
        function addPackage() {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter your Staff ID code to unlock modifications:");
            if (staffID === null) return;

            if (staffID.trim() !== "2") {
                alert("Access Denied! This Staff ID does not have access to add packages.");
                return;
            }

            let packageName = prompt("Enter package name:");
            if (packageDesc === null || packageDesc.trim() === "") {
                alert("Package description cannot be empty!");
                return;
            }

            let packageDesc = prompt("Enter package description:");
            if (packageDesc === null || packageDesc.trim() === "") {
                alert("Package description cannot be empty!");
                return;
            }

            let packagePrice = prompt("Enter package price, example: 2500:");
            if (packagePrice === null || packagePrice.trim() === "") {
                alert("Package price cannot be empty!");
                return;
            }

            if (isNaN(packagePrice)) {
                alert("Package price must be a number!");
                return;
            }

            let packagePax = prompt("Enter package pax, example: 300:");
            if (packagePax === null || packagePax.trim() === "") {
                alert("Package pax cannot be empty!");
                return;
            }

            if (isNaN(packagePax)) {
                alert("Package pax must be a number!");
                return;
            }

            document.getElementById("package_name").value = packageName;
            document.getElementById("package_desc").value = packageDesc;
            document.getElementById("package_price").value = packagePrice;
            document.getElementById("package_pax").value = packagePax;
            document.getElementById("staff_id").value = staffID.trim();

            document.getElementById("addPackageForm").submit();
        }

        function editPackage(id, currentName, currentDesc, currentPrice, currentPax) {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter your Staff ID code to unlock modifications:");
            if (staffID === null) return;

            if (staffID.trim() !== "2") {
                alert("Access Denied! This Staff ID does not have access to edit packages.");
                return;
            }

            let packageName = prompt("Edit package name:", currentName);
            if (packageName === null || packageName.trim() === "") {
                alert("Package name cannot be empty!");
                return;
            }

            let packageDesc = prompt("Edit package description:", currentDesc);
            if (packageDesc === null || packageDesc.trim() === "") {
                alert("Package description cannot be empty!");
                return;
            }

            let packagePrice = prompt("Edit package price:", currentPrice);
            if (packagePrice === null || packagePrice.trim() === "") {
                alert("Package price cannot be empty!");
                return;
            }

            if (isNaN(packagePrice)) {
                alert("Package price must be a number!");
                return;
            }

            let packagePax = prompt("Edit package pax:", currentPax);
            if (packagePax === null || packagePax.trim() === "") {
                alert("Package pax cannot be empty!");
                return;
            }

            if (isNaN(packagePax)) {
                alert("Package pax must be a number!");
                return;
            }

            document.getElementById("edit_package_id").value = id;
            document.getElementById("edit_package_name").value = packageName;
            document.getElementById("edit_package_desc").value = packageDesc;
            document.getElementById("edit_package_price").value = packagePrice;
            document.getElementById("edit_package_pax").value = packagePax;
            document.getElementById("edit_staff_id").value = staffID.trim();

            document.getElementById("editPackageForm").submit();
        }

        // SECURE UPGRADE: Delete authentication gateway verification handler
        function deletePackage(id) {
            let staffID = prompt("🔒 ADMIN SECURITY GATEWAY\nEnter your Staff ID code to approve removal execution:");
            if (staffID === null) return;

            if (staffID.trim() !== "2") {
                alert("Access Denied! This Staff ID does not have access to delete packages.");
                return;
            }

            if (confirm("Are you absolutely sure you want to permanently delete this package selection?")) {
                document.getElementById("delete_package_id").value = id;
                document.getElementById("delete_staff_id").value = staffID.trim();
                document.getElementById("deletePackageForm").submit();
            }
        }
    </script>
</body>
</html>