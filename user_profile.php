
<?php
require_once 'config/database.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$page_title = 'Profile User';

// Handle profile picture upload
if (isset($_POST['upload_profile'])) {
    $target_dir = "uploads/profiles/";
    
    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES["profile_picture"]["name"], PATHINFO_EXTENSION);
    $new_filename = "profile_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    
    if (in_array(strtolower($file_extension), $allowed_types)) {
        if ($_FILES["profile_picture"]["size"] < 5000000) { // 5MB limit
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                try {
                    $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                    $stmt->execute([$new_filename, $user_id]);
                    $_SESSION['success'] = "Profile picture berhasil diupdate!";
                } catch(PDOException $e) {
                    $error = "Error updating profile picture: " . $e->getMessage();
                }
            } else {
                $error = "Error uploading file.";
            }
        } else {
            $error = "File terlalu besar. Maximum 5MB.";
        }
    } else {
        $error = "Hanya file JPG, JPEG, PNG & GIF yang diizinkan.";
    }
}

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_POST['upload_profile'])) {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    
    if (!empty($full_name) && !empty($email)) {
        try {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone_number = ?, address = ?, birth_date = ?, gender = ?, updated_at = NOW() WHERE id = ?");
            $stmt->execute([$full_name, $email, $phone_number, $address, $birth_date ?: null, $gender ?: null, $user_id]);
            
            $_SESSION['user_name'] = $full_name;
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
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
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
            <div style="position: relative; display: inline-block;">
                <?php if (!empty($user['profile_picture']) && $user['profile_picture'] !== 'default_profile.jpg' && file_exists('uploads/profiles/' . $user['profile_picture'])): ?>
                    <img src="uploads/profiles/<?= htmlspecialchars($user['profile_picture']) ?>" 
                         style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid #2D7298;" 
                         alt="Profile Picture">
                <?php else: ?>
                    <div style="background: #2D7298; color: white; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto; font-size: 3rem;">
                        <i class="fas fa-user"></i>
                    </div>
                <?php endif; ?>
                
                <!-- Upload Profile Picture -->
                <form method="POST" enctype="multipart/form-data" style="margin-top: 10px;">
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display: none;" onchange="this.form.submit();">
                    <label for="profile_picture" style="background: #2D7298; color: white; padding: 8px 16px; border-radius: 20px; cursor: pointer; font-size: 0.9rem; display: inline-block; margin-top: 10px;">
                        <i class="fas fa-camera"></i> Upload Foto
                    </label>
                    <input type="hidden" name="upload_profile" value="1">
                </form>
            </div>
            
            <h2 style="color: #333; margin-bottom: 5px; margin-top: 20px;"><?= htmlspecialchars($user['full_name'] ?? $user['username'] ?? 'User') ?></h2>
            <p style="color: #666;">Member sejak <?= date('F Y', strtotime($user['created_at'] ?? 'now')) ?></p>
        </div>

        <!-- Profile Form -->
        <form method="POST" style="max-width: 600px; margin: 0 auto;">
            <div style="display: grid; gap: 20px;">
                <div>
                    <label for="full_name" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div>
                    <label for="email" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Email</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div>
                    <label for="phone_number" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">No. Telepon</label>
                    <input type="tel" id="phone_number" name="phone_number" value="<?= htmlspecialchars($user['phone_number'] ?? '') ?>"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;"
                           placeholder="Masukkan nomor telepon">
                </div>
                
                <div>
                    <label for="birth_date" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Tanggal Lahir</label>
                    <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($user['birth_date'] ?? '') ?>"
                           style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                </div>
                
                <div>
                    <label for="gender" style="display: block; margin-bottom: 8px; font-weight: 500; color: #333;">Jenis Kelamin</label>
                    <select id="gender" name="gender" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Laki-laki</option>
                        <option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Perempuan</option>
                        <option value="other" <?= ($user['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Lainnya</option>
                    </select>
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
