
<?php
require_once 'config/koneksi.php';
requireLogin();

$user_id = $_SESSION['user_id'];

// Ambil data history booking (booking yang sudah lewat)
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
    WHERE b.id_pelanggan = ? AND b.tanggal < CURDATE()
    ORDER BY b.tanggal DESC, j.jam_mulai DESC
");
$stmt->execute([$user_id]);
$history = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History - ArenaKuy!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-history me-2"></i>Riwayat Booking</h2>
            <a href="my-booking.php" class="btn btn-outline-primary">
                <i class="fas fa-calendar-check me-2"></i>Booking Aktif
            </a>
        </div>

        <?php if (empty($history)): ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-history text-muted fa-4x mb-4"></i>
                    <h4>Belum Ada Riwayat</h4>
                    <p class="text-muted mb-4">Anda belum memiliki riwayat booking yang selesai.</p>
                    <a href="search.php" class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Mulai Booking
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Lapangan</th>
                                    <th>Tipe</th>
                                    <th>Tanggal</th>
                                    <th>Waktu</th>
                                    <th>Durasi</th>
                                    <th>Total Harga</th>
                                    <th>Waktu Booking</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $item): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($item['nama_lapangan']) ?></strong>
                                            <?php if ($item['catatan']): ?>
                                                <br><small class="text-muted"><?= htmlspecialchars($item['catatan']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $item['tipe'] == 'Indoor' ? 'primary' : ($item['tipe'] == 'Outdoor' ? 'success' : 'warning') ?>">
                                                <?= htmlspecialchars($item['tipe']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('d/m/Y', strtotime($item['tanggal'])) ?></td>
                                        <td><?= substr($item['jam_mulai'], 0, 5) ?> - <?= substr($item['jam_selesai'], 0, 5) ?></td>
                                        <td><?= $item['durasi'] ?? 1 ?> Jam</td>
                                        <td>
                                            <strong class="text-success">
                                                Rp <?= number_format(($item['harga'] * ($item['durasi'] ?? 1)), 0, ',', '.') ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= date('d/m/Y H:i', strtotime($item['waktu_booking'])) ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer text-muted">
                    <small>Total <?= count($history) ?> riwayat booking ditemukan</small>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
