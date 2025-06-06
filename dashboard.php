
<?php
$page_title = 'Dashboard';
$base_path = '';
require_once 'includes/header.php';
require_once 'config/database.php';

// Statistik untuk dashboard (jika user login)
$stats = [
    'total_bookings' => 0,
    'pending_bookings' => 0,
    'completed_bookings' => 0
];

if (isLoggedIn()) {
    try {
        // Total bookings
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $stats['total_bookings'] = $stmt->fetchColumn();
        
        // Pending bookings
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND status = 'pending'");
        $stmt->execute([$_SESSION['user_id']]);
        $stats['pending_bookings'] = $stmt->fetchColumn();
        
        // Completed bookings
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM bookings WHERE user_id = ? AND status = 'completed'");
        $stmt->execute([$_SESSION['user_id']]);
        $stats['completed_bookings'] = $stmt->fetchColumn();
    } catch(PDOException $e) {
        // Ignore errors for now
    }
}
?>

<div class="page-content">
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Selamat Datang di ArenaKuy!</h1>
                <p>Platform booking lapangan futsal terbaik di Indonesia. Temukan dan booking lapangan favorit Anda dengan mudah.</p>
                <a href="arenas.php" class="btn btn-primary">Pilih Arena Sekarang</a>
            </div>
            <div class="hero-image">
                <i class="fas fa-futbol"></i>
            </div>
        </div>
    </section>

    <?php if (isLoggedIn()): ?>
    <!-- Dashboard Stats -->
    <section style="padding: 60px 0; background: #f8fafc;">
        <div class="container">
            <h2 style="text-align: center; margin-bottom: 40px;">Dashboard Anda</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                <div class="feature-card">
                    <i class="fas fa-calendar-check" style="color: #10b981;"></i>
                    <h3>Total Booking</h3>
                    <p style="font-size: 2rem; font-weight: bold; color: #10b981;"><?= $stats['total_bookings'] ?></p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-clock" style="color: #f59e0b;"></i>
                    <h3>Pending</h3>
                    <p style="font-size: 2rem; font-weight: bold; color: #f59e0b;"><?= $stats['pending_bookings'] ?></p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-check-circle" style="color: #059669;"></i>
                    <h3>Selesai</h3>
                    <p style="font-size: 2rem; font-weight: bold; color: #059669;"><?= $stats['completed_bookings'] ?></p>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Mengapa Pilih ArenaKuy?</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-search"></i>
                    <h3>Mudah Dicari</h3>
                    <p>Temukan lapangan terdekat dengan fitur pencarian yang canggih dan filter yang lengkap.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-mobile-alt"></i>
                    <h3>Booking Online</h3>
                    <p>Booking kapan saja, dimana saja melalui smartphone atau komputer Anda.</p>
                </div>
                <div class="feature-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Aman & Terpercaya</h3>
                    <p>Transaksi aman dengan sistem pembayaran yang terpercaya dan customer service 24/7.</p>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
@media (max-width: 768px) {
    .hero {
        flex-direction: column;
        text-align: center;
        padding: 40px 20px;
    }
    
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .hero-content p {
        font-size: 1rem;
    }
    
    .hero-image {
        margin-top: 20px;
    }
    
    .hero-image i {
        font-size: 80px;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .feature-card {
        padding: 30px 15px;
    }
    
    .feature-card h3 {
        font-size: 1.1rem;
    }
}

@media (max-width: 480px) {
    .hero {
        padding: 30px 15px;
    }
    
    .hero-content h1 {
        font-size: 1.8rem;
    }
    
    .hero-image i {
        font-size: 60px;
    }
    
    .feature-card {
        padding: 25px 10px;
    }
    
    .feature-card i {
        font-size: 2.5rem;
    }
}
</style>

<script src="assets/js/main.js"></script>
</body>
</html>
