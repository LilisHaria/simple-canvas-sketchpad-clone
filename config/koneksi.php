
<?php
// Konfigurasi database
$host = 'localhost';
$dbname = 'arenakuy';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fungsi untuk membersihkan input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Start session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk cek login pelanggan
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && 
           isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'pelanggan';
}

// Fungsi untuk cek login admin
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']) && 
           isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

// Fungsi untuk redirect jika belum login (pelanggan)
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: auth/login_pelanggan.php');
        exit;
    }
}

// Fungsi untuk redirect jika belum login (admin)
function requireAdminLogin() {
    if (!isAdminLoggedIn()) {
        header('Location: ../auth/login_admin.php');
        exit;
    }
}
?>
