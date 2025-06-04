
<?php
require_once 'config/database.php';
requireLogin();

// Ambil data user
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Ambil booking terbaru user
$stmt = $pdo->prepare("
    SELECT b.*, a.arena_name, a.location 
    FROM bookings b 
    JOIN arenas a ON b.arena_id = a.arena_id 
    WHERE b.user_id = ? 
    ORDER BY b.created_at DESC 
    LIMIT 5
");
$stmt->execute([$_SESSION['user_id']]);
$recent_bookings = $stmt->fetchAll();

// Hitung total booking
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM bookings WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$total_bookings = $stmt->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ArenaKuy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-futbol"></i>
                    <span>ArenaKuy</span>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <li><a href="dashboard.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="arenas.php"><i class="fas fa-map-marker-alt"></i> Pilih Arena</a></li>
                    <li><a href="my-bookings.php"><i class="fas fa-calendar-check"></i> Booking Saya</a></li>
                    <li><a href="history.php"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li><a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container">
                <div class="dashboard-header">
                    <h1>Dashboard</h1>
                    <p>Selamat datang, <?= htmlspecialchars($user['full_name']) ?>!</p>
                </div>

                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <div class="stat-info">
                            <h3><?= $total_bookings ?></h3>
                            <p>Total Booking</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="recent-bookings">
                    <h2>Booking Terbaru</h2>
                    <?php if (empty($recent_bookings)): ?>
                        <div class="empty-state">
                            <i class="fas fa-calendar-times"></i>
                            <p>Belum ada booking</p>
                            <a href="arenas.php" class="btn btn-primary">Mulai Booking</a>
                        </div>
                    <?php else: ?>
                        <div class="booking-list">
                            <?php foreach ($recent_bookings as $booking): ?>
                                <div class="booking-card">
                                    <div class="booking-info">
                                        <h3><?= htmlspecialchars($booking['arena_name']) ?></h3>
                                        <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($booking['location']) ?></p>
                                        <p><i class="fas fa-calendar"></i> <?= date('d M Y', strtotime($booking['booking_date'])) ?></p>
                                        <p><i class="fas fa-clock"></i> <?= date('H:i', strtotime($booking['start_time'])) ?> - <?= date('H:i', strtotime($booking['end_time'])) ?></p>
                                    </div>
                                    <div class="booking-status">
                                        <span class="status-badge status-<?= strtolower($booking['status']) ?>">
                                            <?= ucfirst($booking['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
