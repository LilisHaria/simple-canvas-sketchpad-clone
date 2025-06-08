
<?php
require_once 'config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$page_title = 'History Booking';

// Ambil data booking user yang sudah selesai (tanggal dan waktu sudah lewat)
try {
    $stmt = $pdo->prepare("
        SELECT b.*, a.arena_name, a.location, a.price_per_hour
        FROM bookings b
        JOIN arenas a ON b.arena_id = a.arena_id
        WHERE b.user_id = ? AND (
            b.booking_date < CURDATE() OR 
            (b.booking_date = CURDATE() AND b.end_time < CURTIME())
        )
        ORDER BY b.booking_date DESC, b.start_time DESC
    ");
    $stmt->execute([$user_id]);
    $history_bookings = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error loading history: " . $e->getMessage();
    $history_bookings = [];
}

include 'includes/user_header.php';
?>

<div class="container" style="padding-top: 50px; max-width: 1200px; margin: 0 auto; padding-left: 1rem; padding-right: 1rem;">
    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #2D7298, #5db2c5); color: white; text-align: center; padding: 60px 20px; border-radius: 15px; margin-bottom: 40px;">
        <h1 style="font-size: 3rem; margin-bottom: 15px;">
            <i class="fas fa-history"></i> History Booking
        </h1>
        <p style="font-size: 1.2rem; margin-bottom: 30px;">Riwayat semua pemesanan arena yang telah selesai</p>
        <a href="user_dashboard.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.3s;">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <!-- Action Buttons -->
    <div style="text-align: center; margin-bottom: 40px;">
        <a href="user_mybooking.php" style="background: #2D7298; color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; margin-right: 15px; transition: transform 0.3s;">
            <i class="fas fa-calendar-check"></i> Booking Aktif
        </a>
        <a href="user_arenas.php" style="background: rgba(45, 114, 152, 0.1); color: #2D7298; padding: 15px 30px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; border: 2px solid #2D7298; transition: transform 0.3s;">
            <i class="fas fa-plus"></i> Booking Baru
        </a>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #2D7298; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <i class="fas fa-calendar-check" style="font-size: 1.5rem;"></i>
            </div>
            <h3 style="color: #333; margin-bottom: 5px;"><?= count($history_bookings) ?></h3>
            <p style="color: #666;">Total Booking</p>
        </div>
        
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #10b981; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <i class="fas fa-clock" style="font-size: 1.5rem;"></i>
            </div>
            <h3 style="color: #333; margin-bottom: 5px;">
                <?php 
                $total_hours = 0;
                foreach($history_bookings as $booking) {
                    $start = new DateTime($booking['start_time']);
                    $end = new DateTime($booking['end_time']);
                    $total_hours += $start->diff($end)->h;
                }
                echo $total_hours;
                ?>
            </h3>
            <p style="color: #666;">Total Jam Main</p>
        </div>
        
        <div style="background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); text-align: center;">
            <div style="background: #059669; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <i class="fas fa-money-bill" style="font-size: 1.5rem;"></i>
            </div>
            <h3 style="color: #333; margin-bottom: 5px;">
                Rp <?= number_format(array_sum(array_column($history_bookings, 'total_price')), 0, ',', '.') ?>
            </h3>
            <p style="color: #666;">Total Pengeluaran</p>
        </div>
    </div>

    <?php if (isset($error)): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (empty($history_bookings)): ?>
        <div style="background: white; border-radius: 15px; padding: 60px 20px; text-align: center; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
            <i class="fas fa-history" style="font-size: 5rem; color: #ccc; margin-bottom: 20px;"></i>
            <h3 style="color: #666; margin-bottom: 15px; font-size: 1.8rem;">Belum Ada History</h3>
            <p style="color: #999; margin-bottom: 30px; font-size: 1.1rem;">Anda belum memiliki riwayat booking yang selesai.</p>
            <a href="user_arenas.php" style="background: #2D7298; color: white; padding: 15px 30px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; font-size: 1.1rem;">
                <i class="fas fa-search"></i> Mulai Booking
            </a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px;">
            <?php foreach ($history_bookings as $booking): ?>
                <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div style="background: linear-gradient(135deg, #6b7280, #9ca3af); color: white; padding: 20px;">
                        <h4 style="margin: 0 0 8px 0; font-size: 1.3rem;"><?= htmlspecialchars($booking['arena_name']) ?></h4>
                        <p style="margin: 0; opacity: 0.9;">
                            <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($booking['location']) ?>
                        </p>
                    </div>
                    <div style="padding: 25px;">
                        <div style="margin-bottom: 15px;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-calendar" style="color: #6b7280; margin-right: 10px; width: 20px;"></i>
                                <strong><?= date('d F Y', strtotime($booking['booking_date'])) ?></strong>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-clock" style="color: #6b7280; margin-right: 10px; width: 20px;"></i>
                                <?= substr($booking['start_time'], 0, 5) ?> - <?= substr($booking['end_time'], 0, 5) ?>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <i class="fas fa-money-bill" style="color: #059669; margin-right: 10px; width: 20px;"></i>
                                Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                            </div>
                        </div>
                        
                        <div style="margin-top: 20px;">
                            <span style="background: #f3f4f6; color: #6b7280; padding: 6px 12px; border-radius: 15px; font-size: 0.9rem; font-weight: 500;">Selesai</span>
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
