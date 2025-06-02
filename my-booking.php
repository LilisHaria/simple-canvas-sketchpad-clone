
<?php
require_once 'config/koneksi.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Ambil data booking user dengan JOIN
$stmt = $pdo->prepare("
    SELECT b.*, l.nama_lapangan, l.tipe, l.harga, 
           j.jam_mulai, j.jam_selesai,
           d.durasi, d.catatan,
           lb.waktu as waktu_booking
    FROM booking b
    JOIN lapangan l ON b.id_lapangan = l.id_lapangan
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    LEFT JOIN detail_booking d ON b.id_booking = d.id_booking
    LEFT JOIN log_booking lb ON b.id_booking = lb.id_booking
    WHERE b.id_pelanggan = ?
    ORDER BY b.tanggal DESC, j.jam_mulai DESC
");
$stmt->execute([$user_id]);
$bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Booking - ArenaKuy!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-calendar-check me-2"></i>My Booking</h2>
            <a href="search.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Booking Baru
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?= $_SESSION['success'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?= $_SESSION['error'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($bookings)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-calendar-times text-muted fa-4x mb-4"></i>
                    <h4>Belum Ada Booking</h4>
                    <p class="text-muted mb-4">Anda belum melakukan booking arena manapun.</p>
                    <a href="search.php" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Cari Arena Sekarang
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($bookings as $booking): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0"><?= htmlspecialchars($booking['nama_lapangan']) ?></h6>
                                <span class="badge bg-<?= $booking['tipe'] == 'Indoor' ? 'primary' : ($booking['tipe'] == 'Outdoor' ? 'success' : 'warning') ?>">
                                    <?= htmlspecialchars($booking['tipe']) ?>
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <i class="fas fa-calendar text-primary me-2"></i>
                                    <strong><?= date('d F Y', strtotime($booking['tanggal'])) ?></strong>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-clock text-success me-2"></i>
                                    <?= substr($booking['jam_mulai'], 0, 5) ?> - <?= substr($booking['jam_selesai'], 0, 5) ?>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-hourglass-half text-warning me-2"></i>
                                    <?= $booking['durasi'] ?? 1 ?> Jam
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-money-bill text-info me-2"></i>
                                    Rp <?= number_format(($booking['harga'] * ($booking['durasi'] ?? 1)), 0, ',', '.') ?>
                                </div>
                                <?php if ($booking['catatan']): ?>
                                    <div class="mb-2">
                                        <i class="fas fa-sticky-note text-secondary me-2"></i>
                                        <small><?= htmlspecialchars($booking['catatan']) ?></small>
                                    </div>
                                <?php endif; ?>
                                <div class="mt-3">
                                    <?php if ($booking['tanggal'] > date('Y-m-d')): ?>
                                        <span class="badge bg-success">Akan Datang</span>
                                    <?php elseif ($booking['tanggal'] == date('Y-m-d')): ?>
                                        <span class="badge bg-warning">Hari Ini</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Selesai</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="card-footer text-muted">
                                <small>
                                    <i class="fas fa-clock me-1"></i>
                                    Booked: <?= date('d/m/Y H:i', strtotime($booking['waktu_booking'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
