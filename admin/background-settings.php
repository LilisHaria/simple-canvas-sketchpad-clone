
<?php
require_once '../config/database.php';

// Cek apakah user adalah admin (untuk keamanan)
// Untuk sementara kita skip check admin, tapi di production harus ada

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] == 0) {
        $uploadDir = '../uploads/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        $fileType = $_FILES['background_image']['type'];
        $fileSize = $_FILES['background_image']['size'];
        
        if (in_array($fileType, $allowedTypes) && $fileSize <= $maxSize) {
            $fileName = 'auth_background_' . time() . '.' . pathinfo($_FILES['background_image']['name'], PATHINFO_EXTENSION);
            $uploadPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['background_image']['tmp_name'], $uploadPath)) {
                // Simpan path ke database atau file konfigurasi
                try {
                    // Cek apakah sudah ada setting
                    $stmt = $pdo->prepare("SELECT * FROM site_settings WHERE setting_key = 'auth_background'");
                    $stmt->execute();
                    $existing = $stmt->fetch();
                    
                    if ($existing) {
                        // Update existing
                        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = 'auth_background'");
                        $stmt->execute(['uploads/' . $fileName]);
                    } else {
                        // Insert new
                        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES ('auth_background', ?)");
                        $stmt->execute(['uploads/' . $fileName]);
                    }
                    
                    $success = "Background berhasil diupload!";
                } catch(PDOException $e) {
                    // Jika tabel belum ada, buat file konfigurasi sederhana
                    file_put_contents('../config/background.txt', 'uploads/' . $fileName);
                    $success = "Background berhasil diupload!";
                }
            } else {
                $error = "Gagal mengupload file.";
            }
        } else {
            $error = "File harus berupa gambar (JPG, PNG, GIF) dan maksimal 5MB.";
        }
    }
}

// Ambil background yang tersimpan
$currentBackground = '';
try {
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'auth_background'");
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
        $currentBackground = $result['setting_value'];
    }
} catch(PDOException $e) {
    // Coba ambil dari file
    if (file_exists('../config/background.txt')) {
        $currentBackground = file_get_contents('../config/background.txt');
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Background - ArenaKuy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center;">
    <div class="form-container">
        <div style="text-align: center; margin-bottom: 30px;">
            <i class="fas fa-image" style="font-size: 3rem; color: #667eea; margin-bottom: 15px;"></i>
            <h2 style="color: #333; margin-bottom: 10px;">Pengaturan Background</h2>
            <p style="color: #666;">Upload gambar untuk latar belakang halaman login</p>
        </div>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        
        <?php if ($currentBackground): ?>
            <div style="margin-bottom: 20px; text-align: center;">
                <p style="margin-bottom: 10px; color: #666;">Background saat ini:</p>
                <img src="../<?= htmlspecialchars($currentBackground) ?>" alt="Current Background" style="max-width: 100%; max-height: 200px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="background_image">Pilih Gambar Background</label>
                <input type="file" id="background_image" name="background_image" class="form-control" accept="image/*" required>
                <small style="color: #666; font-size: 0.9rem;">Format: JPG, PNG, GIF. Maksimal 5MB.</small>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 20px;">
                <i class="fas fa-upload"></i> Upload Background
            </button>
        </form>
        
        <div style="text-align: center;">
            <a href="../dashboard.php" style="color: #666; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>
