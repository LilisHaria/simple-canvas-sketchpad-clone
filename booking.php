
<?php
require_once 'config/database.php';
requireLogin();

$arena_id = $_GET['arena_id'] ?? 0;

// Ambil data arena
$stmt = $pdo->prepare("SELECT * FROM arenas WHERE arena_id = ? AND is_active = TRUE");
$stmt->execute([$arena_id]);
$arena = $stmt->fetch();

if (!$arena) {
    header('Location: arenas.php');
    exit;
}

// Process booking
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_date = sanitize_input($_POST['booking_date']);
    $start_time = sanitize_input($_POST['start_time']);
    $duration = intval($_POST['duration']);
    
    // Hitung end_time dan total_price
    $end_time = date('H:i:s', strtotime($start_time) + ($duration * 3600));
    $total_price = $arena['price_per_hour'] * $duration;
    
    try {
        $pdo->beginTransaction();
        
        // Check availability
        $check_stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM bookings 
            WHERE arena_id = ? AND booking_date = ? 
            AND ((start_time <= ? AND end_time > ?) OR (start_time < ? AND end_time >= ?))
            AND status NOT IN ('cancelled')
        ");
        $check_stmt->execute([$arena_id, $booking_date, $start_time, $start_time, $end_time, $end_time]);
        
        if ($check_stmt->fetch()['count'] > 0) {
            throw new Exception("Waktu tersebut sudah dibooking");
        }
        
        // Insert booking
        $booking_stmt = $pdo->prepare("
            INSERT INTO bookings (user_id, arena_id, booking_date, start_time, end_time, duration, total_price, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'confirmed')
        ");
        $booking_stmt->execute([$_SESSION['user_id'], $arena_id, $booking_date, $start_time, $end_time, $duration, $total_price]);
        $booking_id = $pdo->lastInsertId();
        
        // Insert log
        $log_stmt = $pdo->prepare("
            INSERT INTO logbooking (booking_id, action, new_status, changed_by, change_note) 
            VALUES (?, 'created', 'confirmed', ?, 'Booking created by user')
        ");
        $log_stmt->execute([$booking_id, $_SESSION['username']]);
        
        $pdo->commit();
        
        $_SESSION['success'] = "Booking berhasil! Arena {$arena['arena_name']} telah dibooking.";
        header('Location: my-bookings.php');
        exit;
        
    } catch (Exception $e) {
        $pdo->rollback();
        $error = "Booking gagal: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking <?= htmlspecialchars($arena['arena_name']) ?> - ArenaKuy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-futbol"></i>
                    <span>ArenaKuy</span>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <li><a href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                    <li><a href="arenas.php"><i class="fas fa-map-marker-alt"></i> Pilih Arena</a></li>
                    <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                    <li><a href="my-bookings.php"><i class="fas fa-calendar-check"></i> Booking Saya</a></li>
                    <li><a href="history.php"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li><a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <div class="container" style="padding: 40px 20px;">
                <div style="display: grid; grid-template-columns: 1fr 400px; gap: 40px; max-width: 1200px; margin: 0 auto;">
                    <!-- Arena Info -->
                    <div>
                        <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px;">
                            <div class="arena-image" style="height: 300px;">
                                <?php if ($arena['image_url']): ?>
                                    <img src="<?= htmlspecialchars($arena['image_url']) ?>" alt="<?= htmlspecialchars($arena['arena_name']) ?>">
                                <?php else: ?>
                                    <div class="arena-placeholder">
                                        <i class="fas fa-futbol"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div style="padding: 30px;">
                                <h1 style="color: #333; margin-bottom: 15px;"><?= htmlspecialchars($arena['arena_name']) ?></h1>
                                <p style="color: #666; margin-bottom: 15px;">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($arena['location']) ?>
                                </p>
                                <p style="color: #777; line-height: 1.6; margin-bottom: 20px;"><?= htmlspecialchars($arena['description']) ?></p>
                                <div style="display: flex; align-items: baseline;">
                                    <span style="font-size: 2rem; font-weight: bold; color: #10b981;">
                                        Rp <?= number_format($arena['price_per_hour'], 0, ',', '.') ?>
                                    </span>
                                    <span style="margin-left: 8px; color: #666;">/jam</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Form -->
                    <div>
                        <div style="background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                            <h2 style="color: #333; margin-bottom: 25px; text-align: center;">
                                <i class="fas fa-calendar-plus"></i> Book Arena
                            </h2>
                            
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            
                            <form method="POST" id="bookingForm">
                                <div class="form-group">
                                    <label for="booking_date">Tanggal Booking</label>
                                    <input type="date" id="booking_date" name="booking_date" class="form-control" 
                                           min="<?= date('Y-m-d') ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="start_time">Jam Mulai</label>
                                    <select id="start_time" name="start_time" class="form-control" required>
                                        <option value="">Pilih Jam</option>
                                        <?php for ($i = 6; $i <= 22; $i++): ?>
                                            <option value="<?= sprintf('%02d:00:00', $i) ?>"><?= sprintf('%02d:00', $i) ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="duration">Durasi (jam)</label>
                                    <select id="duration" name="duration" class="form-control" required>
                                        <option value="">Pilih Durasi</option>
                                        <option value="1">1 Jam</option>
                                        <option value="2">2 Jam</option>
                                        <option value="3">3 Jam</option>
                                        <option value="4">4 Jam</option>
                                    </select>
                                </div>
                                
                                <div style="background: #f8fafc; padding: 20px; border-radius: 8px; margin: 20px 0;">
                                    <h4 style="color: #333; margin-bottom: 10px;">Ringkasan Booking</h4>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Harga per jam:</span>
                                        <span>Rp <?= number_format($arena['price_per_hour'], 0, ',', '.') ?></span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <span>Durasi:</span>
                                        <span id="selected-duration">- jam</span>
                                    </div>
                                    <hr style="margin: 15px 0;">
                                    <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.1rem;">
                                        <span>Total:</span>
                                        <span id="total-price" style="color: #10b981;">Rp 0</span>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary" style="width: 100%;">
                                    <i class="fas fa-check"></i> Konfirmasi Booking
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="assets/js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const durationSelect = document.getElementById('duration');
            const selectedDurationSpan = document.getElementById('selected-duration');
            const totalPriceSpan = document.getElementById('total-price');
            const pricePerHour = <?= $arena['price_per_hour'] ?>;
            
            durationSelect.addEventListener('change', function() {
                const duration = parseInt(this.value) || 0;
                const totalPrice = pricePerHour * duration;
                
                selectedDurationSpan.textContent = duration + ' jam';
                totalPriceSpan.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
            });
        });
    </script>
</body>
</html>
