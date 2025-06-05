
<?php
$page_title = "History";
require_once 'config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Ambil data history booking (booking yang sudah selesai)
$stmt = $pdo->prepare("
    SELECT b.*, a.arena_name, a.location, a.price_per_hour,
           DATE_FORMAT(b.booking_date, '%d %M %Y') as formatted_date,
           DATE_FORMAT(b.start_time, '%H:%i') as start_formatted,
           DATE_FORMAT(b.end_time, '%H:%i') as end_formatted,
           TIMESTAMPDIFF(HOUR, b.start_time, b.end_time) as duration_hours,
           (TIMESTAMPDIFF(HOUR, b.start_time, b.end_time) * a.price_per_hour) as total_price
    FROM bookings b
    JOIN arenas a ON b.arena_id = a.arena_id
    WHERE b.user_id = ? AND (b.booking_date < CURDATE() OR b.status = 'completed')
    ORDER BY b.booking_date DESC, b.start_time DESC
");
$stmt->execute([$user_id]);
$history_bookings = $stmt->fetchAll();

include 'includes/header.php';
?>

<link rel="stylesheet" href="assets/css/header.css">
<link rel="stylesheet" href="assets/css/booking-pages.css">

<div class="page-container">
    <div class="page-header">
        <div class="header-content">
            <h1><i class="fas fa-history"></i> History Booking</h1>
            <p>Riwayat semua pemesanan arena yang telah selesai</p>
            <a href="dashboard.php" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="container">
        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="my-bookings.php" class="btn btn-primary">
                <i class="fas fa-calendar-check"></i> Booking Aktif
            </a>
            <a href="arenas.php" class="btn btn-outline">
                <i class="fas fa-plus"></i> Booking Baru
            </a>
        </div>

        <!-- Filter Options -->
        <div class="filter-section">
            <div class="filter-group">
                <label for="monthFilter">Filter Bulan:</label>
                <select id="monthFilter" class="form-control">
                    <option value="">Semua Bulan</option>
                    <option value="01">Januari</option>
                    <option value="02">Februari</option>
                    <option value="03">Maret</option>
                    <option value="04">April</option>
                    <option value="05">Mei</option>
                    <option value="06">Juni</option>
                    <option value="07">Juli</option>
                    <option value="08">Agustus</option>
                    <option value="09">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="yearFilter">Filter Tahun:</label>
                <select id="yearFilter" class="form-control">
                    <option value="">Semua Tahun</option>
                    <?php for($year = date('Y'); $year >= 2023; $year--): ?>
                        <option value="<?= $year ?>"><?= $year ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-info">
                    <h3><?= count($history_bookings) ?></h3>
                    <p>Total Booking</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-info">
                    <h3><?= array_sum(array_column($history_bookings, 'duration_hours')) ?></h3>
                    <p>Total Jam Main</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="stat-info">
                    <h3>Rp <?= number_format(array_sum(array_column($history_bookings, 'total_price')), 0, ',', '.') ?></h3>
                    <p>Total Pengeluaran</p>
                </div>
            </div>
        </div>

        <!-- History Table -->
        <div class="history-table-container">
            <?php if (empty($history_bookings)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <h3>Belum Ada History</h3>
                    <p>Anda belum memiliki riwayat booking yang selesai.</p>
                    <a href="arenas.php" class="btn btn-primary">
                        <i class="fas fa-search"></i> Mulai Booking
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="history-table" id="historyTable">
                        <thead>
                            <tr>
                                <th>Arena</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Durasi</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history_bookings as $booking): ?>
                                <tr data-date="<?= date('Y-m', strtotime($booking['booking_date'])) ?>">
                                    <td>
                                        <div class="arena-info">
                                            <strong><?= htmlspecialchars($booking['arena_name']) ?></strong>
                                            <small><?= htmlspecialchars($booking['location']) ?></small>
                                        </div>
                                    </td>
                                    <td><?= $booking['formatted_date'] ?></td>
                                    <td><?= $booking['start_formatted'] ?> - <?= $booking['end_formatted'] ?></td>
                                    <td>
                                        <span class="duration-badge">
                                            <?= $booking['duration_hours'] ?> Jam
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="price-text">
                                            Rp <?= number_format($booking['total_price'], 0, ',', '.') ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?= strtolower($booking['status']) ?>">
                                            <?= ucfirst($booking['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline" onclick="viewBookingDetail(<?= $booking['booking_id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="assets/js/main.js"></script>
<script src="assets/js/history.js"></script>

<?php include 'includes/footer.php'; ?>
