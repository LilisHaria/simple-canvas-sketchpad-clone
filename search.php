
<?php
require_once 'config/koneksi.php';

// Handle search
$search = $_GET['search'] ?? '';
$tipe = $_GET['tipe'] ?? '';

$sql = "SELECT * FROM lapangan WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (nama_lapangan LIKE ? OR tipe LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($tipe)) {
    $sql .= " AND tipe = ?";
    $params[] = $tipe;
}

$sql .= " ORDER BY nama_lapangan";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$lapangan_list = $stmt->fetchAll();

// Get available jadwal
$jadwal_stmt = $pdo->prepare("SELECT * FROM jadwal WHERE status = 'available' ORDER BY jam_mulai");
$jadwal_stmt->execute();
$jadwal_list = $jadwal_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari Arena - ArenaKuy!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container mt-5 pt-5">
        <!-- Search Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-search me-2"></i>Cari Arena</h5>
            </div>
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Cari nama lapangan..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" name="tipe">
                            <option value="">Semua Tipe</option>
                            <option value="Indoor" <?= $tipe == 'Indoor' ? 'selected' : '' ?>>Indoor</option>
                            <option value="Outdoor" <?= $tipe == 'Outdoor' ? 'selected' : '' ?>>Outdoor</option>
                            <option value="Synthetic" <?= $tipe == 'Synthetic' ? 'selected' : '' ?>>Synthetic</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results -->
        <div class="row">
            <?php if (empty($lapangan_list)): ?>
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search text-muted fa-3x mb-3"></i>
                        <h4>Arena tidak ditemukan</h4>
                        <p class="text-muted">Coba ubah kata kunci pencarian Anda</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($lapangan_list as $lapangan): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-futbol fa-3x text-muted"></i>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($lapangan['nama_lapangan']) ?></h5>
                                <p class="card-text">
                                    <span class="badge bg-primary"><?= htmlspecialchars($lapangan['tipe']) ?></span><br>
                                    <strong>Harga:</strong> Rp <?= number_format($lapangan['harga'], 0, ',', '.') ?>/jam
                                </p>
                                <?php if (isLoggedIn()): ?>
                                    <button class="btn btn-primary w-100" onclick="showBookingModal(<?= $lapangan['id_lapangan'] ?>, '<?= htmlspecialchars($lapangan['nama_lapangan']) ?>', <?= $lapangan['harga'] ?>)">
                                        <i class="fas fa-calendar-plus me-2"></i>Book Sekarang
                                    </button>
                                <?php else: ?>
                                    <a href="auth/login.php" class="btn btn-outline-primary w-100">
                                        Login untuk Booking
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Booking Modal -->
    <?php if (isLoggedIn()): ?>
    <div class="modal fade" id="bookingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Booking Arena</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="bookingForm" method="POST" action="process-booking.php">
                    <div class="modal-body">
                        <input type="hidden" name="id_lapangan" id="modal_id_lapangan">
                        
                        <div class="mb-3">
                            <label class="form-label">Lapangan</label>
                            <input type="text" class="form-control" id="modal_nama_lapangan" readonly>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" 
                                   min="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Waktu</label>
                            <select class="form-select" name="id_jadwal" required>
                                <option value="">Pilih Waktu</option>
                                <?php foreach ($jadwal_list as $jadwal): ?>
                                    <option value="<?= $jadwal['id_jadwal'] ?>">
                                        <?= substr($jadwal['jam_mulai'], 0, 5) ?> - <?= substr($jadwal['jam_selesai'], 0, 5) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Durasi (jam)</label>
                            <select class="form-select" name="durasi" required>
                                <option value="1">1 Jam</option>
                                <option value="2">2 Jam</option>
                                <option value="3">3 Jam</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea class="form-control" name="catatan" rows="3"></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Total Harga:</strong> <span id="total_harga">Rp 0</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentHarga = 0;
        
        function showBookingModal(id, nama, harga) {
            currentHarga = harga;
            document.getElementById('modal_id_lapangan').value = id;
            document.getElementById('modal_nama_lapangan').value = nama;
            updateTotalHarga();
            
            const modal = new bootstrap.Modal(document.getElementById('bookingModal'));
            modal.show();
        }
        
        function updateTotalHarga() {
            const durasi = document.querySelector('select[name="durasi"]').value || 1;
            const total = currentHarga * durasi;
            document.getElementById('total_harga').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
        
        // Update total harga ketika durasi berubah
        document.addEventListener('change', function(e) {
            if (e.target.name === 'durasi') {
                updateTotalHarga();
            }
        });
    </script>
</body>
</html>
