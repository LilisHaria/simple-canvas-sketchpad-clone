
<?php
require_once 'config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $email = sanitize_input($_POST['email']);
        $password = $_POST['password'];
        
        if (empty($email) || empty($password)) {
            $error = "Email dan password harus diisi!";
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                setUserSession($user);
                
                // Redirect berdasarkan role
                if ($user['role'] === 'admin') {
                    header('Location: admin/dashboard.php');
                } else {
                    header('Location: dashboard.php');
                }
                exit;
            } else {
                $error = "Email atau password salah!";
            }
        }
    } catch(PDOException $e) {
        $error = "Terjadi kesalahan database: " . $e->getMessage();
    } catch(Exception $e) {
        $error = "Terjadi kesalahan: " . $e->getMessage();
    }
}

// Tampilkan session success jika ada
if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ArenaKuy</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center;">
    <div class="form-container">
        <div style="text-align: center; margin-bottom: 30px;">
            <i class="fas fa-futbol" style="font-size: 3rem; color: #667eea; margin-bottom: 15px;"></i>
            <h2 style="color: #333; margin-bottom: 10px;">Login ke ArenaKuy</h2>
            <p style="color: #666;">Masuk untuk melakukan booking</p>
        </div>
        
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width: 100%; margin-bottom: 20px;">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
        </form>
        
        <div style="text-align: center;">
            <p>Belum punya akun? <a href="register.php" style="color: #667eea; text-decoration: none;">Daftar di sini</a></p>
            <a href="index.php" style="color: #666; text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
