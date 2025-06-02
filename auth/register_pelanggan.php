
<?php
require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = sanitize_input($_POST['nama']);
    $email = sanitize_input($_POST['email']);
    $no_hp = sanitize_input($_POST['no_hp']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    $errors = [];
    
    // Validasi input
    if (empty($nama)) $errors[] = "Nama harus diisi";
    if (empty($email)) $errors[] = "Email harus diisi";
    if (empty($no_hp)) $errors[] = "No HP harus diisi";
    if (empty($password)) $errors[] = "Password harus diisi";
    if ($password !== $confirm_password) $errors[] = "Konfirmasi password tidak cocok";
    if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter";
    
    if (empty($errors)) {
        try {
            // Cek email sudah ada atau belum
            $stmt = $pdo->prepare("SELECT id_pelanggan FROM pelanggan WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $errors[] = "Email sudah terdaftar";
            } else {
                // Hash password menggunakan password_hash()
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert ke database
                $stmt = $pdo->prepare("INSERT INTO pelanggan (nama, email, no_hp, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nama, $email, $no_hp, $hashed_password]);
                
                $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
                header('Location: login_pelanggan.php');
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
    <title>Daftar Pelanggan - ArenaKuy!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="auth-body">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2 class="text-primary">⚽ ArenaKuy!</h2>
                            <p class="text-muted">Daftar Akun Pelanggan</p>
                        </div>
                        
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <?php foreach ($errors as $error): ?>
                                    <div><?= $error ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" value="<?= $_POST['nama'] ?? '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">No HP</label>
                                <input type="text" class="form-control" name="no_hp" value="<?= $_POST['no_hp'] ?? '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password" class="form-control" name="confirm_password" required>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">Daftar</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <p>Sudah punya akun? <a href="login_pelanggan.php">Login di sini</a></p>
                            <a href="../index.php" class="text-muted">Kembali ke Beranda</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
