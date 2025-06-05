
<?php
// Start session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include functions jika ada
if (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ArenaKuy' : 'ArenaKuy' ?></title>
    <link rel="stylesheet" href="<?= isset($css_path) ? $css_path : 'assets/css/' ?>style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Navigation -->
    <header class="main-header">
        <div class="header-container">
            <!-- Hamburger Menu -->
            <button class="sidebar-toggle" id="sidebarToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <!-- Logo -->
            <div class="logo">
                <i class="fas fa-futbol"></i>
                <span>ArenaKuy!</span>
            </div>
            
            <!-- Search Button -->
            <button class="search-btn">
                <i class="fas fa-search"></i>
            </button>
            
            <!-- Navigation Menu -->
            <nav class="main-nav">
                <a href="<?= isset($base_path) ? $base_path : '' ?>index.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Home</span>
                </a>
                <a href="<?= isset($base_path) ? $base_path : '' ?>arenas.php" class="nav-link">
                    <i class="fas fa-calendar-check"></i>
                    <span>Booking</span>
                </a>
                <a href="<?= isset($base_path) ? $base_path : '' ?>history.php" class="nav-link">
                    <i class="fas fa-history"></i>
                    <span>History</span>
                </a>
                
                <?php if (isLoggedIn()): ?>
                    <a href="<?= isset($base_path) ? $base_path : '' ?>dashboard.php" class="nav-link dashboard-btn">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?= isset($base_path) ? $base_path : '' ?>auth/logout.php" class="nav-link sign-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                <?php else: ?>
                    <a href="<?= isset($base_path) ? $base_path : '' ?>auth/login.php" class="nav-link sign-btn">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <i class="fas fa-futbol"></i>
                <span>ArenaKuy</span>
            </div>
            <button class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <?php if (isLoggedIn()): ?>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>arenas.php"><i class="fas fa-map-marker-alt"></i> Pilih Arena</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>my-bookings.php"><i class="fas fa-calendar-check"></i> My Booking</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>history.php"><i class="fas fa-history"></i> History</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>arenas.php"><i class="fas fa-search"></i> Cari Arena</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>auth/register.php"><i class="fas fa-user-plus"></i> Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content Wrapper -->
    <div class="main-wrapper">
