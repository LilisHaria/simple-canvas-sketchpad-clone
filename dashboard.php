
<?php
require_once 'config/database.php';

// Redirect admin ke dashboard admin
if (isAdmin()) {
    header('Location: admin/dashboard.php');
    exit;
}

// Require login untuk user biasa
requireLogin();

$page_title = 'Dashboard';
$stats = [
    'total_bookings' => 0,
    'pending_bookings' => 0,
    'completed_bookings' => 0
];

try {
    // Total bookings user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['total_bookings'] = $stmt->fetchColumn();
    
    // Pending bookings
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['pending_bookings'] = $stmt->fetchColumn();
    
    // Completed bookings
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND status = 'confirmed'");
    $stmt->execute([$_SESSION['user_id']]);
    $stats['completed_bookings'] = $stmt->fetchColumn();
} catch(PDOException $e) {
    $error = "Error loading dashboard: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ArenaKuy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header untuk User -->
    <header class="main-header">
        <div class="header-container">
            <a href="dashboard.php" class="logo">
                <i class="fas fa-futbol"></i>
                ArenaKuy
            </a>
            
            <nav class="main-nav">
                <a href="dashboard.php" class="nav-link">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
                <a href="arenas.php" class="nav-link">
                    <i class="fas fa-futbol"></i>
                    Cari Arena
                </a>
                <a href="logout.php" class="nav-link sign-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </nav>
            
            <div class="mobile-dropdown" id="mobileDropdown">
                <button class="mobile-dropdown-toggle">
                    <i class="fas fa-user"></i>
                    <?= htmlspecialchars($_SESSION['user_name']) ?>
                </button>
                <div class="mobile-dropdown-menu" id="mobileDropdownMenu">
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                    <a href="logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="main-content">
        <div class="container" style="padding-top: 100px;">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <!-- Selamat Datang -->
            <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 30px; text-align: center;">
                <h2 style="color: #333; margin-bottom: 10px;">Selamat Datang, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h2>
                <p style="color: #666;">Platform booking lapangan futsal terbaik di Indonesia</p>
            </div>
            
            <!-- Stats untuk User -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin-bottom: 30px;">
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-calendar-check" style="font-size: 3rem; color: #10b981; margin-bottom: 15px;"></i>
                    <h3 style="margin: 0; font-size: 2rem; color: #333;"><?= $stats['total_bookings'] ?></h3>
                    <p style="color: #666; margin: 5px 0 0 0;">Total Booking</p>
                </div>
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-clock" style="font-size: 3rem; color: #f59e0b; margin-bottom: 15px;"></i>
                    <h3 style="margin: 0; font-size: 2rem; color: #333;"><?= $stats['pending_bookings'] ?></h3>
                    <p style="color: #666; margin: 5px 0 0 0;">Pending</p>
                </div>
                <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
                    <i class="fas fa-check-circle" style="font-size: 3rem; color: #059669; margin-bottom: 15px;"></i>
                    <h3 style="margin: 0; font-size: 2rem; color: #333;"><?= $stats['completed_bookings'] ?></h3>
                    <p style="color: #666; margin: 5px 0 0 0;">Selesai</p>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <h3 style="margin: 0 0 20px 0; color: #333;">Aksi Cepat</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <a href="arenas.php" style="display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 10px; transition: transform 0.3s;">
                        <i class="fas fa-search" style="font-size: 2rem;"></i>
                        <span>Cari Arena</span>
                    </a>
                    <a href="my-booking.php" style="display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; text-decoration: none; border-radius: 10px; transition: transform 0.3s;">
                        <i class="fas fa-calendar-check" style="font-size: 2rem;"></i>
                        <span>My Booking</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="assets/js/main.js"></script>
</body>
</html>
