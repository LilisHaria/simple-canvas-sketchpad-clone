
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
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/user_header.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header Navigation -->
    <header class="user-header">
        <div class="header-container">
            <!-- Hamburger Menu - Always visible -->
            <button class="hamburger-menu" id="hamburgerToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <!-- Logo -->
            <a href="user_dashboard.php" class="logo">
                <i class="fas fa-futbol"></i>
                <span>ArenaKuy!</span>
            </a>
            
            <!-- Desktop Navigation -->
            <nav class="desktop-nav">
                <a href="user_dashboard.php" class="nav-item">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="user_arenas.php" class="nav-item">
                    <i class="fas fa-calendar-check"></i> Booking
                </a>
                <a href="user_mybooking.php" class="nav-item highlight">
                    <i class="fas fa-calendar-alt"></i> My Bookings
                </a>
            </nav>
            
            <!-- Header Actions -->
            <div class="header-actions">
                <!-- Search Button -->
                <button class="search-btn" id="searchToggle">
                    <i class="fas fa-search"></i>
                </button>
                
                <!-- User Menu -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="user-menu">
                        <button class="user-menu-toggle" id="userMenuToggle">
                            <i class="fas fa-user"></i>
                            <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="user_profile.php">
                                <i class="fas fa-user"></i>
                                Profile
                            </a>
                            <a href="logout.php">
                                <i class="fas fa-sign-out-alt"></i>
                                Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="sign-in-btn">Sign In</a>
                <?php endif; ?>
            </div>
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
            <form class="search-form" action="user_arenas.php" method="GET">
                <div class="search-input-group">
                    <input type="text" name="search" placeholder="Nama arena atau deskripsi..." class="search-input">
                    <button type="submit" class="search-submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar -->
    <nav class="user-sidebar" id="userSidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-futbol"></i>
                <span>ArenaKuy!</span>
            </div>
            <button class="sidebar-close" id="sidebarClose">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <ul class="sidebar-menu">
                <li>
                    <a href="user_dashboard.php" class="sidebar-item">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="user_arenas.php" class="sidebar-item">
                        <i class="fas fa-calendar-check"></i>
                        <span>Pilih Arena</span>
                    </a>
                </li>
                <li>
                    <a href="user_mybooking.php" class="sidebar-item">
                        <i class="fas fa-calendar-alt"></i>
                        <span>My Bookings</span>
                    </a>
                </li>
                <li>
                    <a href="user_history.php" class="sidebar-item">
                        <i class="fas fa-history"></i>
                        <span>History</span>
                    </a>
                </li>
                <li>
                    <a href="user_profile.php" class="sidebar-item">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                <li>
                    <a href="logout.php" class="sidebar-item">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </li>
                <?php else: ?>
                <li>
                    <a href="login.php" class="sidebar-item">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Sign In</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content Wrapper -->
    <div class="main-content">
