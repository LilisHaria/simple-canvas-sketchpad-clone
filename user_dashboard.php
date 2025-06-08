
<?php
require_once 'config/database.php';

// Redirect admin ke dashboard admin
if (isAdmin()) {
    header('Location: admin/admin_dashboard.php');
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

// Pastikan user_name ada dalam session, jika tidak ambil dari database
if (!isset($_SESSION['user_name']) || empty($_SESSION['user_name'])) {
    try {
        $stmt = $pdo->prepare("SELECT full_name, username FROM users WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_data = $stmt->fetch();
        if ($user_data) {
            $_SESSION['user_name'] = $user_data['full_name'] ?: $user_data['username'] ?: 'User';
        } else {
            $_SESSION['user_name'] = 'User';
        }
    } catch(PDOException $e) {
        $_SESSION['user_name'] = 'User';
    }
}

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

// Include user header
include 'includes/user_header.php';
?>

<div class="container" style="padding-top: 50px; max-width: 1200px; margin: 0 auto; padding-left: 1rem; padding-right: 1rem;">
    <h1 style="color: #333; margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-tachometer-alt"></i> Dashboard
    </h1>
    
    <?php if (isset($error)): ?>
        <div style="background: #fee; border: 1px solid #fcc; color: #c66; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <!-- Selamat Datang -->
    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 30px; text-align: center;">
        <h2 style="color: #333; margin-bottom: 10px;">Selamat Datang, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>!</h2>
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
            <a href="user_arenas.php" style="display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px; background: rgba(45, 114, 152, 0.8); color: white; text-decoration: none; border-radius: 10px; transition: transform 0.3s;">
                <i class="fas fa-search" style="font-size: 2rem;"></i>
                <span>Cari Arena</span>
            </a>
            <a href="user_history.php" style="display: flex; flex-direction: column; align-items: center; gap: 10px; padding: 20px; background: rgba(221, 168, 83, 0.8); color: white; text-decoration: none; border-radius: 10px; transition: transform 0.3s;">
                <i class="fas fa-history" style="font-size: 2rem;"></i>
                <span>History</span>
            </a>
        </div>
    </div>
</div>

</div> <!-- Close main-content -->

<script src="assets/js/user_header.js"></script>
</body>
</html>
