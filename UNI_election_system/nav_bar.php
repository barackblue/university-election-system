<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$role = $_SESSION['role'];

// Check roles
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';
}

function isCandidate() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'candidate';
}

function isStudent() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'student';
}

function isLecture() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'lecture';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Navbar</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://unpkg.com/boxicons/css/boxicons.min.css" rel="stylesheet">

</head>
<body>
    <nav class="navbar">
        <!-- Logo and Site Name -->
        <div class="navbar-brand">
            <div class="logo-back">
                <img aria-hidden="true" src="https://www.iaa.ac.tz/storage/app/public/206/6658987f4f099_Untitled-1.png" alt="iaa-logo">
            </div>
            <span>Election System</span>
        </div>

        <!-- Hamburger Menu -->
        <button class="hamburger" aria-label="Toggle Menu">
            &#9776; <!-- Hamburger Icon -->
        </button>

        <!-- Navigation Links -->
        <?php 
            $currentPage = basename($_SERVER['PHP_SELF']); // Get the current file name
        ?>
        <ul class="nav-links">
            <li><a href="dashbord.php" class="<?= $currentPage == 'dashbord.php' ? 'active' : '' ?>">Dashboard</a></li>
            <li><a href="manifestor.php" class="<?= $currentPage == 'manifestor.php' ? 'active' : '' ?>">Manifestos</a></li>
            <li><a href="results.php" class="<?= $currentPage == 'results.php' ? 'active' : '' ?>">Live Results</a></li>
            <?php if($role == 'candidate'): ?>
                <li><a href="form.php" class="<?= $currentPage == 'form.php' ? 'active' : '' ?>">Forms</a></li>
            <?php endif; ?>
            <li><a href="contacts.php" class="<?= $currentPage == 'contacts.php' ? 'active' : '' ?>">Contact Us</a></li>
            <li><a href="about.php" class="<?= $currentPage == 'about.php' ? 'active' : '' ?>">About</a></li>
            <li><a href="logout.php" class="<?= $currentPage == 'logout.php' ? 'active' : '' ?>">Logout</a></li>
            <li><a href="notifications.php" class="<?= $currentPage == 'notifications.php' ? 'active' : '' ?>"><i class="bx bx-bell"></i></a></li>
            <li><a href="profile.php" class="<?= $currentPage == 'profile.php' ? 'active' : '' ?>"><i class="bx bx-user-circle"></i></a></li>
        </ul>
    </nav>
    <script src="script.js"></script>
</body>
</html>
