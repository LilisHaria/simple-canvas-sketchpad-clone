
<?php
// Konfigurasi database Hostinger
$host = 'localhost';
$dbname = 'u985354573_arenakuy';
$username = 'u985354573_arenakuy_lilis';
$password = '0909loqweA@#$';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch(PDOException $e) {
    // Log error untuk debugging tapi jangan tampilkan ke user
    error_log("Database connection failed: " . $e->getMessage());
    die("Koneksi database gagal. Silakan coba lagi nanti.");
}

// Fungsi untuk membersihkan input
function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Start session jika belum dimulai
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fungsi untuk cek login
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Fungsi untuk cek admin
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Fungsi untuk redirect jika belum login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . getBaseUrl() . 'auth/login.php');
        exit;
    }
}

// Fungsi untuk redirect jika bukan admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ' . getBaseUrl() . 'dashboard.php');
        exit;
    }
}

// Fungsi untuk mendapatkan base URL
function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script);
    return $protocol . $host . ($path == '/' ? '/' : $path . '/');
}

// Update session dengan role ketika login
function setUserSession($user_data) {
    $_SESSION['user_id'] = $user_data['user_id'] ?? $user_data['id'];
    $_SESSION['user_name'] = $user_data['full_name'] ?? $user_data['name'] ?? $user_data['username'];
    $_SESSION['user_email'] = $user_data['email'];
    $_SESSION['user_role'] = $user_data['role'] ?? 'user';
    $_SESSION['username'] = $user_data['username'] ?? $user_data['name'];
}

// Fungsi untuk logout
function logout() {
    session_start();
    session_destroy();
    header('Location: ' . getBaseUrl() . 'index.php');
    exit;
}

// Fungsi untuk menampilkan pesan sukses/error
function showMessage($type, $message) {
    if (isset($_SESSION[$type])) {
        echo '<div class="alert alert-' . $type . '">' . htmlspecialchars($_SESSION[$type]) . '</div>';
        unset($_SESSION[$type]);
    }
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');

// Error reporting untuk development (ubah ke 0 untuk production)
error_reporting(E_ALL);
ini_set('display_errors', 0); // Jangan tampilkan error ke user di production
ini_set('log_errors', 1); // Log error ke file
?>
