
<?php
require_once 'config/database.php';
requireLogin();

$arena_id = $_GET['arena_id'] ?? 0;

// Ambil data arena
$stmt = $pdo->prepare("SELECT * FROM arenas WHERE arena_id = ? AND is_active = TRUE");
$stmt->execute([$arena_id]);
$arena = $stmt->fetch();

if (!$arena) {
    header('Location: user_arenas.php');
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
        
        $pdo->commit();
        
        $_SESSION['success'] = "Booking berhasil! Arena {$arena['arena_name']} telah dibooking.";
        header('Location: user_mybooking.php');
        exit;
        
    } catch (Exception $e) {
        $pdo->rollback();
        $error = "Booking gagal: " . $e->getMessage();
    }
}

$page_title = 'Booking ' . $arena['arena_name'];
include 'includes/user_header.php';
?>

<div class="container" style="padding-top: 50px; max-width: 1200px; margin: 0 auto; padding-left: 1rem; padding-right: 1rem;">
    <div style="display: grid; grid-template-columns: 1fr 400px; gap: 40px;">
        <!-- Arena Info -->
        <div>
            <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 30px;">
                <div style="height: 300px; background: linear-gradient(135deg, #2D7298, #DDA853);">
                    <?php if ($arena['image_url']): ?>
                        <img src="<?= htmlspecialchars($arena['image_url']) ?>" alt="<?= htmlspecialchars($arena['arena_name']) ?>"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: white;">
                            <i class="fas fa-futbol" style="font-size: 6rem;"></i>
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
                    <div style="background: #fee; border: 1px solid #fcc; color: #c66; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" id="bookingForm">
                    <div style="margin-bottom: 20px;">
                        <label for="booking_date" style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Tanggal Booking</label>
                        <input type="date" id="booking_date" name="booking_date" 
                               style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;"
                               min="<?= date('Y-m-d') ?>" required>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label for="start_time" style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Jam Mulai</label>
                        <select id="start_time" name="start_time" 
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;" required>
                            <option value="">Pilih Jam</option>
                            <?php for ($i = 6; $i <= 22; $i++): ?>
                                <option value="<?= sprintf('%02d:00:00', $i) ?>"><?= sprintf('%02d:00', $i) ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div style="margin-bottom: 20px;">
                        <label for="duration" style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Durasi (jam)</label>
                        <select id="duration" name="duration" 
                                style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;" required>
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
                    
                    <button type="submit" style="width: 100%; background: #10b981; color: white; border: none; padding: 15px; border-radius: 8px; font-size: 1.1rem; font-weight: 500; cursor: pointer; transition: background 0.3s;">
                        <i class="fas fa-check"></i> Konfirmasi Booking
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .container > div {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
}
</style>

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

</div> <!-- Close main-content -->

<script src="assets/js/user_header.js"></script>
</body>
</html>
