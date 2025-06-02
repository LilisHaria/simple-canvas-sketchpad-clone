
<?php
require_once '../config/koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../auth/login_admin.php');
    exit;
}

// Ambil statistik
$stmt = $pdo->query("SELECT COUNT(*) as total FROM pelanggan");
$total_pelanggan = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM lapangan");
$total_lapangan = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM booking");
$total_booking = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM booking WHERE tanggal = CURDATE()");
$booking_hari_ini = $stmt->fetch()['total'];

// Ambil data pelanggan terbaru
$stmt = $pdo->query("SELECT * FROM pelanggan ORDER BY id_pelanggan DESC LIMIT 10");
$pelanggan_terbaru = $stmt->fetchAll();

// Ambil booking terbaru
$stmt = $pdo->query("
    SELECT b.*, p.nama, l.nama_lapangan, j.jam_mulai, j.jam_selesai
    FROM booking b
    JOIN pelanggan p ON b.id_pelanggan = p.id_pelanggan
    JOIN lapangan l ON b.id_lapangan = l.id_lapangan
    JOIN jadwal j ON b.id_jadwal = j.id_jadwal
    ORDER BY b.id_booking DESC
    LIMIT 10
");
$booking_terbaru = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ArenaKuy!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">⚽ ArenaKuy! Admin</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($_SESSION['admin_name']) ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="pelanggan.php">Data Pelanggan</a></li>
                        <li><a class="dropdown-item" href="lapangan.php">Data Lapangan</a></li>
                        <li><a class="dropdown-item" href="booking.php">Data Booking</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../auth/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Dashboard Admin</h2>
        <p class="text-muted">Selamat datang, <?= htmlspecialchars($_SESSION['admin_name']) ?>!</p>

        <!-- Statistik Cards -->
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <i class="fas fa-users text-primary fa-2x mb-3"></i>
                        <h3 class="text-primary"><?= $total_pelanggan ?></h3>
                        <p class="text-muted">Total Pelanggan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-success">
                    <div class="card-body">
                        <i class="fas fa-futbol text-success fa-2x mb-3"></i>
                        <h3 class="text-success"><?= $total_lapangan ?></h3>
                        <p class="text-muted">Total Lapangan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-warning">
                    <div class="card-body">
                        <i class="fas fa-calendar-check text-warning fa-2x mb-3"></i>
                        <h3 class="text-warning"><?= $total_booking ?></h3>
                        <p class="text-muted">Total Booking</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center border-info">
                    <div class="card-body">
                        <i class="fas fa-calendar-day text-info fa-2x mb-3"></i>
                        <h3 class="text-info"><?= $booking_hari_ini ?></h3>
                        <p class="text-muted">Booking Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Data Pelanggan Terbaru -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Pelanggan Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>No HP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pelanggan_terbaru as $pelanggan): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($pelanggan['nama']) ?></td>
                                            <td><?= htmlspecialchars($pelanggan['email']) ?></td>
                                            <td><?= htmlspecialchars($pelanggan['no_hp']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="pelanggan.php" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
            </div>

            <!-- Booking Terbaru -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5>Booking Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Pelanggan</th>
                                        <th>Lapangan</th>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($booking_terbaru as $booking): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($booking['nama']) ?></td>
                                            <td><?= htmlspecialchars($booking['nama_lapangan']) ?></td>
                                            <td><?= date('d/m/Y', strtotime($booking['tanggal'])) ?></td>
                                            <td><?= substr($booking['jam_mulai'], 0, 5) ?>-<?= substr($booking['jam_selesai'], 0, 5) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="booking.php" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
