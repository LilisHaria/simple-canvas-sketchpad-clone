
<?php
require_once 'config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$page_title = 'Profile User';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    if (!empty($name) && !empty($email)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ?, phone = ?, address = ? WHERE user_id = ?");
            $stmt->execute([$name, $email, $phone, $address, $user_id]);
            
            $_SESSION['user_name'] = $name;
            $_SESSION['success'] = "Profile berhasil diupdate!";
            header('Location: user_profile.php');
            exit;
        } catch(PDOException $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }
    } else {
        $error = "Nama dan email harus diisi!";
    }
}

// Get user data
try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
} catch(PDOException $e) {
    $error = "Error loading profile: " . $e->getMessage();
    $user = [];
}

include 'includes/user_header.php';
?>

<div class="container" style="padding-top: 50px; max-width: 800px; margin: 0 auto; padding-left: 1rem; padding-right: 1rem;">
    <!-- Header Section -->
    <div style="background: linear-gradient(135deg, #2D7298, #5db2c5); color: white; text-align: center; padding: 60px 20px; border-radius: 15px; margin-bottom: 40px;">
        <h1 style="font-size: 3rem; margin-bottom: 15px;">
            <i class="fas fa-user"></i> Profile User
        </h1>
        <p style="font-size: 1.2rem; margin-bottom: 30px;">Kelola informasi profile Anda</p>
        <a href="user_dashboard.php" style="background: rgba(255,255,255,0.2); color: white; padding: 12px 24px; border-radius: 25px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; transition: background 0.3s;">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
        <div style="background: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($_SESSION['success']) ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <div style="background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div style="background: white; border-radius: 15px; padding: 40px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
        <!-- Profile Avatar -->
        <div style="text-align: center; margin-bottom: 40px;">
            <div style="background: #2D7298; color: white; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 3rem;">
                <i class="fas fa-user"></i>
            </div>
            <h2 style="color: #333; margin-bottom: 5px;"><?= htmlspecialchars($user['name'] ?? 'User') ?></h2>
            <p style="color: #666;">Member sejak <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?></p>
        </div>

        <!-- Profile Form -->
        <form method="POST" style="max-width: 600px; margin: 0 auto;">
            <div style="display: grid; gap: 20px;">
                <div>
                    <label for="name" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div>
                    <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div>
                    <label for="phone" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">No. Telepon</label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;"
                           placeholder="Masukkan nomor telepon">
                </div>
                
                <div>
                    <label for="address" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Alamat</label>
                    <textarea id="address" name="address" rows="3"
                              style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem; resize: vertical;"
                              placeholder="Masukkan alamat lengkap"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" style="background: #2D7298; color: white; padding: 15px 40px; border: none; border-radius: 25px; font-size: 1.1rem; font-weight: 500; cursor: pointer; transition: background 0.3s;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>

        <!-- Account Statistics -->
        <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #e9ecef;">
            <h3 style="color: #333; margin-bottom: 20px; text-align: center;">Statistik Akun</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <?php
                // Get user statistics
                try {
                    $stmt = $pdo->prepare("SELECT COUNT(*) as total_bookings FROM bookings WHERE user_id = ?");
                    $stmt->execute([$user_id]);
                    $total_bookings = $stmt->fetchColumn();
                    
                    $stmt = $pdo->prepare("SELECT COUNT(*) as active_bookings FROM bookings WHERE user_id = ? AND booking_date >= CURDATE()");
                    $stmt->execute([$user_id]);
                    $active_bookings = $stmt->fetchColumn();
                } catch(PDOException $e) {
                    $total_bookings = 0;
                    $active_bookings = 0;
                }
                ?>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center;">
                    <div style="color: #2D7298; font-size: 2rem; margin-bottom: 10px;">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <h4 style="color: #333; margin-bottom: 5px;"><?= $total_bookings ?></h4>
                    <p style="color: #666; font-size: 0.9rem;">Total Booking</p>
                </div>
                
                <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; text-align: center;">
                    <div style="color: #10b981; font-size: 2rem; margin-bottom: 10px;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h4 style="color: #333; margin-bottom: 5px;"><?= $active_bookings ?></h4>
                    <p style="color: #666; font-size: 0.9rem;">Booking Aktif</p>
                </div>
            </div>
        </div>
    </div>
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
    }
    
    form div {
        margin-bottom: 15px !important;
    }
}
</style>

</div> <!-- Close main-content -->

<script src="assets/js/user_header.js"></script>
</body>
</html>
