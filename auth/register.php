
<?php
require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $full_name = sanitize_input($_POST['full_name']);
    $phone_number = sanitize_input($_POST['phone_number']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validasi input
    if (empty($username)) $errors[] = "Username harus diisi";
    if (empty($email)) $errors[] = "Email harus diisi";
    if (empty($full_name)) $errors[] = "Nama lengkap harus diisi";
    if (empty($phone_number)) $errors[] = "No HP harus diisi";
    if (empty($password)) $errors[] = "Password harus diisi";
    if ($password !== $confirm_password) $errors[] = "Konfirmasi password tidak cocok";
    if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter";
    
    if (empty($errors)) {
        try {
            // Cek username dan email sudah ada atau belum
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                $errors[] = "Username atau email sudah terdaftar";
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert ke database
                $stmt = $pdo->prepare("INSERT INTO users (username, email, full_name, phone_number, password) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $full_name, $phone_number, $hashed_password]);
                
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header('Location: login.php');
                exit;
            }
        } catch(PDOException $e) {
            $errors[] = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - ArenaKuy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; padding: 20px 0;">
    <div class="form-container">
        <div style="text-align: center; margin-bottom: 30px;">
            <i class="fas fa-futbol" style="font-size: 3rem; color: #667eea; margin-bottom: 15px;"></i>
            <h2 style="color: #333; margin-bottom: 10px;">Daftar ke ArenaKuy</h2>
            <p style="color: #666;">Buat akun untuk mulai booking</p>
        </div>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <div><?= $error ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" 
                       value="<?= $_POST['username'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?= $_POST['email'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="full_name">Nama Lengkap</label>
                <input type="text" id="full_name" name="full_name" class="form-control" 
                       value="<?= $_POST['full_name'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phone_number">No HP</label>
                <input type="text" id="phone_number" name="phone_number" class="form-control" 
                       value="<?= $_POST['phone_number'] ?? '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 20px;">
                <i class="fas fa-user-plus"></i> Daftar
            </button>
        </form>
        
        <div style="text-align: center;">
            <p>Sudah punya akun? <a href="login.php" style="color: #667eea; text-decoration: none;">Login di sini</a></p>
            <a href="../dashboard.php" style="color: #666; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
