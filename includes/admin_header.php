
<header class="main-header">
    <div class="header-container">
        <button class="sidebar-toggle" id="sidebarToggle">
            <span></span>
            <span></span>
            <span></span>
        </button>
        
        <a href="admin_dashboard.php" class="logo">
            <i class="fas fa-futbol"></i>
            ArenaKuy Admin
        </a>
        
        <div class="header-actions">
            <div class="mobile-dropdown" id="mobileDropdown">
                <button class="mobile-dropdown-toggle">
                    <i class="fas fa-user"></i>
                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?>
                </button>
                <div class="mobile-dropdown-menu" id="mobileDropdownMenu">
                    <a href="admin_dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                    <a href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
            
            <nav class="main-nav">
                <a href="admin_dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="../logout.php" class="nav-link sign-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </nav>
        </div>
    </div>
</header>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <i class="fas fa-futbol"></i>
            ArenaKuy Admin
        </div>
        <button class="sidebar-close" id="sidebarClose">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <div class="sidebar-content">
        <ul class="sidebar-menu">
            <li><a href="admin_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="admin_arenas.php"><i class="fas fa-futbol"></i> Kelola Lapangan</a></li>
            <li><a href="admin_bookings.php"><i class="fas fa-calendar-check"></i> Kelola Booking</a></li>
            <li><a href="admin_users.php"><i class="fas fa-users-cog"></i> Kelola User</a></li>
            <li><a href="admin_reports.php"><i class="fas fa-chart-pie"></i> Laporan & Analisis</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
</div>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
