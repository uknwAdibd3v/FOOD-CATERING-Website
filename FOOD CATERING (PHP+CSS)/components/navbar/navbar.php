<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['cus_id']);
?>

<link rel="stylesheet" href="/264PROJECTS_BACKUP/components/navbar/navbar.css">

<header>
    <div class="logo">
        <img src="nobgLogo.png" alt="Warisan Norman Logo">
        <span>WARISAN NORMAN</span>
    </div>

    <button class="hamburger" id="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="right-section" id="right-section">
        <nav>
            <a href="<?php echo $is_logged_in ? 'homePage.php' : 'frontPage.php'; ?>">HOME</a>
            
            <a href="<?php echo $is_logged_in ? 'venuePage.php' : 'loginPage.php'; ?>">VENUE</a>
            <a href="<?php echo $is_logged_in ? 'eventPage.php' : 'loginPage.php'; ?>">EVENTS</a>
            <a href="<?php echo $is_logged_in ? 'packagePage.php' : 'loginPage.php'; ?>">PACKAGE</a>
            <a href="<?php echo $is_logged_in ? 'bookingPage.php' : 'loginPage.php'; ?>">BOOKING</a>
        </nav>

        <?php if (!$is_logged_in): ?>
            <!-- Show these ONLY if the user is a guest -->
            <a href="loginPage.php">
                <button class="btn">LOGIN</button>
            </a>
            <a href="registerPage.php">
                <button class="btn">REGISTER</button>
            </a>
        <?php else: ?>
            
            <a href="logout.php">
                <button class="btn logout-btn">LOGOUT</button>
            </a>
        <?php endif; ?>
    </div>
</header>

<script>
    const hamburger = document.getElementById('hamburger');
    const rightSection = document.getElementById('right-section');

    hamburger.addEventListener('click', () => {
        rightSection.classList.toggle('active');
        hamburger.classList.toggle('open');
    });
</script>