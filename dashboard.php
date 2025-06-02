
<?php
require_once 'config/koneksi.php';
requireLogin();

// Ambil data statistik user
$user_id = $_SESSION['user_id'];

// Total booking
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM booking WHERE id_pelanggan = ?");
$stmt->execute([$user_id]);
$total_booking = $stmt->fetch()['total'];

// Booking hari ini
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM booking WHERE id_pelanggan = ? AND tanggal = CURDATE()");
$stmt->execute([$user_id]);
$booking_today = $stmt->fetch()['total'];

// Booking mendatang
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM booking WHERE id_pelanggan = ? AND tanggal > CURDATE()");
$stmt->execute([$user_id]);
$booking_upcoming = $stmt->fetch()['total'];

// Booking terbaru
$stmt = $pdo->prepare("
    SELECT b.*, l.nama_lapangan, l.tipe, j.jam_mulai, j.jam_selesai
    FROM booking b
    JOIN lapangan l ON b.id_lapangan = l.id_lapangan
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    WHERE b.id_pelanggan = ?
    ORDER BY b.tanggal DESC, b.created_at DESC
    LIMIT 5
");
$stmt->execute([$user_id]);
$recent_bookings = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - ArenaKuy!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>Dashboard</h2>
                        <p class="text-muted">Selamat datang, <?= htmlspecialchars($_SESSION['user_name']) ?>!</p>
                    </div>
                    <a href="search.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Booking Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <i class="fas fa-calendar-check text-primary fa-2x mb-3"></i>
                        <h3 class="text-primary"><?= $total_booking ?></h3>
                        <p class="text-muted">Total Booking</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <i class="fas fa-calendar-day text-success fa-2x mb-3"></i>
                        <h3 class="text-success"><?= $booking_today ?></h3>
                        <p class="text-muted">Booking Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <i class="fas fa-clock text-warning fa-2x mb-3"></i>
                        <h3 class="text-warning"><?= $booking_upcoming ?></h3>
                        <p class="text-muted">Booking Mendatang</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Booking Terbaru</h5>
                        <a href="my-booking.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recent_bookings)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times text-muted fa-3x mb-3"></i>
                                <p class="text-muted">Belum ada booking. <a href="search.php">Booking sekarang!</a></p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Lapangan</th>
                                            <th>Tipe</th>
                                            <th>Tanggal</th>
                                            <th>Waktu</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_bookings as $booking): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($booking['nama_lapangan']) ?></td>
                                                <td><span class="badge bg-secondary"><?= htmlspecialchars($booking['tipe']) ?></span></td>
                                                <td><?= date('d/m/Y', strtotime($booking['tanggal'])) ?></td>
                                                <td><?= substr($booking['jam_mulai'], 0, 5) ?> - <?= substr($booking['jam_selesai'], 0, 5) ?></td>
                                                <td>
                                                    <?php if ($booking['tanggal'] > date('Y-m-d')): ?>
                                                        <span class="badge bg-success">Akan Datang</span>
                                                    <?php elseif ($booking['tanggal'] == date('Y-m-d')): ?>
                                                        <span class="badge bg-warning">Hari Ini</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-secondary">Selesai</span>
                                                    <?php endif; ?>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
