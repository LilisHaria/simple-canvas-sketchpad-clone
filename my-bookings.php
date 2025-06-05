
<?php
$page_title = "My Booking";
require_once 'config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Ambil data booking user yang belum selesai/aktif
$stmt = $pdo->prepare("
    SELECT b.*, a.arena_name, a.location, a.price_per_hour,
           DATE_FORMAT(b.booking_date, '%d %M %Y') as formatted_date,
           DATE_FORMAT(b.start_time, '%H:%i') as start_formatted,
           DATE_FORMAT(b.end_time, '%H:%i') as end_formatted,
           TIMESTAMPDIFF(HOUR, b.start_time, b.end_time) as duration_hours,
           (TIMESTAMPDIFF(HOUR, b.start_time, b.end_time) * a.price_per_hour) as total_price
    FROM bookings b
    JOIN arenas a ON b.arena_id = a.arena_id
    WHERE b.user_id = ? AND (b.booking_date >= CURDATE() OR b.status IN ('pending', 'confirmed'))
    ORDER BY b.booking_date ASC, b.start_time ASC
");
$stmt->execute([$user_id]);
$active_bookings = $stmt->fetchAll();

include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/header.css">
<link rel="stylesheet" href="assets/css/booking-pages.css">

<div class="page-container">
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-calendar-check"></i> My Booking</h1>
            <p>Kelola semua pemesanan arena Anda</p>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="container">
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="arenas.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Booking Baru
            </a>
            <a href="history.php" class="btn btn-outline">
                <i class="fas fa-history"></i> Lihat History
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Booking Cards -->
        <div class="booking-grid">
            <?php if (empty($active_bookings)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-calendar-times"></i>
                    </div>
                    <h3>Belum Ada Booking Aktif</h3>
                    <p>Anda belum memiliki booking yang sedang aktif atau akan datang.</p>
                    <a href="arenas.php" class="btn btn-primary">
                        <i class="fas fa-search"></i> Cari Arena Sekarang
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($active_bookings as $booking): ?>
                    <div class="booking-card">
                        <div class="booking-header">
                            <h3><?= htmlspecialchars($booking['arena_name']) ?></h3>
                            <span class="status-badge status-<?= strtolower($booking['status']) ?>">
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        </div>
                        
                        <div class="booking-details">
                            <div class="detail-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span><?= htmlspecialchars($booking['location']) ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <span><?= $booking['formatted_date'] ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span><?= $booking['start_formatted'] ?> - <?= $booking['end_formatted'] ?></span>
                            </div>
                            
                            <div class="detail-item">
                                <i class="fas fa-hourglass-half"></i>
                                <span><?= $booking['duration_hours'] ?> Jam</span>
                            </div>
                            
                            <div class="detail-item price">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Rp <?= number_format($booking['total_price'], 0, ',', '.') ?></span>
                            </div>
                        </div>
                        
                        <div class="booking-actions">
                            <?php if ($booking['status'] == 'pending'): ?>
                                <button class="btn btn-sm btn-danger" onclick="cancelBooking(<?= $booking['booking_id'] ?>)">
                                    <i class="fas fa-times"></i> Batalkan
                                </button>
                            <?php endif; ?>
                            
                            <button class="btn btn-sm btn-outline" onclick="viewDetails(<?= $booking['booking_id'] ?>)">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </div>
                        
                        <div class="booking-footer">
                            <small>
                                <i class="fas fa-clock"></i>
                                Dipesan: <?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?>
                            </small>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
<script src="assets/js/booking-actions.js"></script>

<?php include 'includes/footer.php'; ?>
