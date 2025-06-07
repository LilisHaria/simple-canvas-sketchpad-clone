
<?php
require_once '../config/database.php';
requireAdmin();

$error = '';

// Pastikan user_name ada dalam session untuk admin
if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    try {
        $stmt = $pdo->prepare("SELECT full_name, username FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_data = $stmt->fetch();
        if ($user_data) {
            $_SESSION['user_name'] = $user_data['full_name'] ?: $user_data['username'] ?: 'Admin';
        } else {
            $_SESSION['user_name'] = 'Admin';
        }
    } catch(PDOException $e) {
        $_SESSION['user_name'] = 'Admin';
    }
}

// Get statistics
try {
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'user'");
    $total_users = $stmt->fetch()['total'];
    
    // Total arenas
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM arenas WHERE status = 'active'");
    $total_arenas = $stmt->fetch()['total'];
    
    // Today's revenue
    $stmt = $pdo->prepare("SELECT SUM(total_price) as revenue FROM bookings WHERE DATE(created_at) = CURDATE() AND status = 'confirmed'");
    $stmt->execute();
    $today_revenue = $stmt->fetch()['revenue'] ?? 0;
    
    // This month's revenue
    $stmt = $pdo->prepare("SELECT SUM(total_price) as revenue FROM bookings WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND status = 'confirmed'");
    $stmt->execute();
    $month_revenue = $stmt->fetch()['revenue'] ?? 0;
    
    // Pending bookings
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM bookings WHERE status = 'pending'");
    $pending_bookings = $stmt->fetch()['total'];
    
    // Recent bookings for notifications
    $stmt = $pdo->prepare("SELECT b.*, u.full_name, a.arena_name FROM bookings b JOIN users u ON b.user_id = u.user_id JOIN arenas a ON b.arena_id = a.arena_id WHERE b.status = 'pending' ORDER BY b.created_at DESC LIMIT 5");
    $stmt->execute();
    $recent_bookings = $stmt->fetchAll();
    
    // Monthly booking stats for chart
    $stmt = $pdo->prepare("SELECT MONTH(booking_date) as month, COUNT(*) as total FROM bookings WHERE YEAR(booking_date) = YEAR(CURDATE()) GROUP BY MONTH(booking_date) ORDER BY month");
    $stmt->execute();
    $monthly_stats = $stmt->fetchAll();
    
} catch(PDOException $e) {
    $error = "Error loading dashboard: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ArenaKuy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>
    
    <div class="main-content">
        <div class="container" style="padding-top: 100px;">
            <h1><i class="fas fa-tachometer-alt"></i> Dashboard Admin</h1>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($total_users) ?></h3>
                        <p>Total User</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-futbol"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($total_arenas) ?></h3>
                        <p>Lapangan Aktif</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rp <?= number_format($today_revenue) ?></h3>
                        <p>Pendapatan Hari Ini</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Rp <?= number_format($month_revenue) ?></h3>
                        <p>Pendapatan Bulan Ini</p>
                    </div>
                </div>
            </div>
            
            <!-- Charts and Notifications -->
            <div class="dashboard-grid">
                <div class="chart-container">
                    <h3><i class="fas fa-chart-bar"></i> Booking per Bulan</h3>
                    <canvas id="monthlyChart"></canvas>
                </div>
                
                <div class="notifications-container">
                    <h3><i class="fas fa-bell"></i> Notifikasi</h3>
                    <?php if (count($recent_bookings) > 0): ?>
                        <?php foreach ($recent_bookings as $booking): ?>
                            <div class="notification-item">
                                <div class="notification-content">
                                    <strong><?= htmlspecialchars($booking['full_name']) ?></strong>
                                    <p>Booking <?= htmlspecialchars($booking['arena_name']) ?></p>
                                    <small><?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?></small>
                                </div>
                                <a href="admin_bookings.php?id=<?= $booking['booking_id'] ?>" class="btn btn-primary btn-sm">
                                    Konfirmasi
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Tidak ada booking baru</p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="quick-actions">
                <h3><i class="fas fa-bolt"></i> Aksi Cepat</h3>
                <div class="action-buttons">
                    <a href="admin_arenas.php" class="action-btn">
                        <i class="fas fa-futbol"></i>
                        <span>Kelola Lapangan</span>
                    </a>
                    <a href="admin_bookings.php" class="action-btn">
                        <i class="fas fa-calendar-check"></i>
                        <span>Kelola Booking</span>
                    </a>
                    <a href="admin_users.php" class="action-btn">
                        <i class="fas fa-users-cog"></i>
                        <span>Kelola User</span>
                    </a>
                    <a href="admin_reports.php" class="action-btn">
                        <i class="fas fa-chart-pie"></i>
                        <span>Laporan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        // Monthly booking chart
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyData = <?= json_encode($monthly_stats) ?>;
        
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthlyData.map(item => 'Bulan ' + item.month),
                datasets: [{
                    label: 'Jumlah Booking',
                    data: monthlyData.map(item => item.total),
                    backgroundColor: 'rgba(102, 126, 234, 0.8)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
