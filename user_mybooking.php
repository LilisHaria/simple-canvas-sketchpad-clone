
<?php
require_once 'config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$page_title = 'My Booking';

// Ambil data booking user dengan JOIN
try {
    $stmt = $pdo->prepare("
        SELECT b.*, a.arena_name, a.location, a.price_per_hour
        FROM bookings b
        JOIN arenas a ON b.arena_id = a.arena_id
        WHERE b.user_id = ?
        ORDER BY b.booking_date DESC, b.start_time DESC
    ");
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error loading bookings: " . $e->getMessage();
    $bookings = [];
}

include 'includes/user_header.php';
?>

<div class="container" style="padding-top: 50px; max-width: 1200px; margin: 0 auto; padding-left: 1rem; padding-right: 1rem;">
    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #2D7298, #5db2c5); color: white; text-align: center; padding: 60px 20px; border-radius: 15px; margin-bottom: 40px;">
        <h1 style="font-size: 3rem; margin-bottom: 15px;">
            <i class="fas fa-calendar-check"></i> My Booking
        </h1>
        <p style="font-size: 1.2rem; margin-bottom: 30px;">Kelola semua pemesanan arena Anda</p>
        <a href="user_dashboard.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.3s;">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Action Buttons -->
    <div style="text-align: center; margin-bottom: 40px;">
        <a href="user_arenas.php" style="background: #2D7298; color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; margin-right: 15px; transition: transform 0.3s;">
            <i class="fas fa-plus"></i> Booking Baru
        </a>
        <a href="user_history.php" style="background: rgba(45, 114, 152, 0.1); color: #2D7298; padding: 15px 30px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; border: 2px solid #2D7298; transition: transform 0.3s;">
            <i class="fas fa-history"></i> Lihat History
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($_SESSION['error']) ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <div style="background: white; border-radius: 15px; padding: 60px 20px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-calendar-times" style="font-size: 5rem; color: #ccc; margin-bottom: 20px;"></i>
            <h3 style="color: #666; margin-bottom: 15px; font-size: 1.8rem;">Belum Ada Booking Aktif</h3>
            <p style="color: #999; margin-bottom: 30px; font-size: 1.1rem;">Anda belum memiliki booking yang sedang aktif atau akan datang.</p>
            <a href="user_arenas.php" style="background: #2D7298; color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; font-size: 1.1rem;">
                <i class="fas fa-search"></i> Cari Arena Sekarang
            </a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <?php foreach ($bookings as $booking): ?>
                <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div style="background: linear-gradient(135deg, #2D7298, #DDA853); color: white; padding: 20px;">
                        <h4 style="margin: 0 0 8px 0; font-size: 1.3rem;"><?= htmlspecialchars($booking['arena_name']) ?></h4>
                        <p style="margin: 0; opacity: 0.9;">
                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($booking['location']) ?>
                        </p>
                    </div>
                    <div style="padding: 25px;">
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-calendar" style="color: #2D7298; margin-right: 10px; width: 20px;"></i>
                                <strong><?= date('d F Y', strtotime($booking['booking_date'])) ?></strong>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-clock" style="color: #10b981; margin-right: 10px; width: 20px;"></i>
                                <?= substr($booking['start_time'], 0, 5) ?> - <?= substr($booking['end_time'], 0, 5) ?>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-hourglass-half" style="color: #f59e0b; margin-right: 10px; width: 20px;"></i>
                                <?= $booking['duration'] ?? 1 ?> Jam
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-money-bill" style="color: #059669; margin-right: 10px; width: 20px;"></i>
                                Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                            </div>
                        </div>
                        
                        <div style="margin-top: 20px;">
                            <?php 
                            $booking_datetime = $booking['booking_date'] . ' ' . $booking['start_time'];
                            $now = date('Y-m-d H:i:s');
                            if ($booking_datetime > $now): ?>
                                <span style="background: #d4edda; color: #155724; padding: 6px 12px; border-radius: 15px; font-size: 0.9rem; font-weight: 500;">Akan Datang</span>
                            <?php elseif (date('Y-m-d', strtotime($booking['booking_date'])) == date('Y-m-d')): ?>
                                <span style="background: #fff3cd; color: #856404; padding: 6px 12px; border-radius: 15px; font-size: 0.9rem; font-weight: 500;">Hari Ini</span>
                            <?php else: ?>
                                <span style="background: #f8f9fa; color: #6c757d; padding: 6px 12px; border-radius: 15px; font-size: 0.9rem; font-weight: 500;">Selesai</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="background: #f8f9fa; padding: 15px; border-top: 1px solid #e9ecef;">
                        <small style="color: #6c757d;">
                            <i class="fas fa-clock"></i>
                            Booking ID: #<?= $booking['booking_id'] ?>
                        </small>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
@media (max-width: 768px) {
    .container {
        padding: 20px 10px !important;
    }
    
    h1 {
        font-size: 2rem !important;
    }
    
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
    
    div[style*="display: inline-flex"] {
        display: block !important;
        margin-bottom: 10px !important;
        text-align: center !important;
    }
}
</style>

</div> <!-- Close main-content -->

<script src="assets/js/user_header.js"></script>
</body>
</html>
