
<?php
// Start session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include functions jika ada
if (file_exists(__DIR__ . '/../config/database.php')) {
    require_once __DIR__ . '/../config/database.php';
}

// Function to check if user is admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title . ' - ArenaKuy' : 'ArenaKuy' ?></title>
    <link rel="stylesheet" href="<?= isset($css_path) ? $css_path : 'assets/css/' ?>style.css">
    <link rel="stylesheet" href="<?= isset($css_path) ? $css_path : 'assets/css/' ?>header.css">
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
            <a href="<?= isset($base_path) ? $base_path : '' ?>dashboard.php" class="logo">
                <i class="fas fa-futbol"></i>
                <span>ArenaKuy!</span>
            </a>
            
            <!-- Search and Mobile Dropdown -->
            <div class="header-actions">
                <!-- Search Button -->
                <button class="search-btn" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>
                
                <!-- Mobile Dropdown -->
                <div class="mobile-dropdown">
                    <button class="mobile-dropdown-toggle" id="mobileDropdown">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="mobile-dropdown-menu" id="mobileDropdownMenu">
                        <?php if (isAdmin()): ?>
                            <a href="<?= isset($base_path) ? $base_path : '' ?>admin/kelola-lapangan.php">
                                <i class="fas fa-building"></i>
                                <span>Kelola Lapangan</span>
                            </a>
                            <a href="<?= isset($base_path) ? $base_path : '' ?>admin/kelola-booking.php">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Kelola Booking</span>
                            </a>
                            <a href="<?= isset($base_path) ? $base_path : '' ?>admin/kelola-user.php">
                                <i class="fas fa-users"></i>
                                <span>Kelola User</span>
                            </a>
                            <a href="<?= isset($base_path) ? $base_path : '' ?>admin/laporan.php">
                                <i class="fas fa-chart-bar"></i>
                                <span>Laporan</span>
                            </a>
                        <?php else: ?>
                            <a href="<?= isset($base_path) ? $base_path : '' ?>arenas.php">
                                <i class="fas fa-calendar-check"></i>
                                <span>Booking</span>
                            </a>
                        <?php endif; ?>
                        
                        <?php if (isLoggedIn()): ?>
                            <?php if (!isAdmin()): ?>
                                <a href="<?= isset($base_path) ? $base_path : '' ?>my-bookings.php">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>Booking Saya</span>
                                </a>
                            <?php endif; ?>
                            <a href="<?= isset($base_path) ? $base_path : '' ?>auth/logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </a>
                        <?php else: ?>
                            <a href="<?= isset($base_path) ? $base_path : '' ?>auth/login.php">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>Login</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Menu (Desktop) -->
            <nav class="main-nav">
                <a href="<?= isset($base_path) ? $base_path : '' ?>dashboard.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                
                <?php if (isAdmin()): ?>
                    <a href="<?= isset($base_path) ? $base_path : '' ?>admin/kelola-lapangan.php" class="nav-link">
                        <i class="fas fa-building"></i>
                        <span>Kelola Lapangan</span>
                    </a>
                    <a href="<?= isset($base_path) ? $base_path : '' ?>admin/kelola-booking.php" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Kelola Booking</span>
                    </a>
                    <a href="<?= isset($base_path) ? $base_path : '' ?>admin/laporan.php" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                <?php else: ?>
                    <a href="<?= isset($base_path) ? $base_path : '' ?>arenas.php" class="nav-link">
                        <i class="fas fa-calendar-check"></i>
                        <span>Booking</span>
                    </a>
                <?php endif; ?>
                
                <?php if (isLoggedIn()): ?>
                    <?php if (!isAdmin()): ?>
                        <a href="<?= isset($base_path) ? $base_path : '' ?>my-bookings.php" class="nav-link dashboard-btn">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Booking Saya</span>
                        </a>
                    <?php endif; ?>
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

    <!-- Search Modal -->
    <div class="search-modal" id="searchModal">
        <div class="search-modal-content">
            <div class="search-header">
                <h3>Cari Arena</h3>
                <button class="search-close" id="searchClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form class="search-form" action="search.php" method="GET">
                <div class="search-input-group">
                    <input type="text" name="q" placeholder="Nama arena, lokasi..." class="search-input">
                    <button type="submit" class="search-submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                <li><a href="<?= isset($base_path) ? $base_path : '' ?>dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                
                <?php if (isAdmin()): ?>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>admin/kelola-lapangan.php"><i class="fas fa-building"></i> Kelola Lapangan</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>admin/kelola-booking.php"><i class="fas fa-calendar-alt"></i> Kelola Booking</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>admin/kelola-user.php"><i class="fas fa-users"></i> Kelola User</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>admin/laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a></li>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>my-bookings.php"><i class="fas fa-calendar-check"></i> Booking Saya</a></li>
                <?php else: ?>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>arenas.php"><i class="fas fa-map-marker-alt"></i> Pilih Arena</a></li>
                <?php endif; ?>
                
                <?php if (isLoggedIn()): ?>
                    <li><a href="<?= isset($base_path) ? $base_path : '' ?>auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                <?php else: ?>
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
