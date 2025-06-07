
<?php
// Konfigurasi database untuk Hostinger
// Sesuaikan dengan detail database Hostinger Anda
$host = 'localhost'; // Biasanya localhost di Hostinger
$dbname = 'u985354573_arenakuy'; // Sesuaikan dengan nama database Anda
$username = 'u985354573_arenakuy_lilis'; // Username database Hostinger
$password = '0909loqweA@#$'; // Password database Hostinger

// Set error reporting untuk production
error_reporting(0);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

try {
    // Buat koneksi PDO dengan error handling yang lebih baik
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        PDO::ATTR_TIMEOUT => 30
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Test koneksi
    $pdo->query("SELECT 1");
    
} catch(PDOException $e) {
    // Log error untuk debugging
    error_log("Database connection failed: " . $e->getMessage());
    
    // Tampilkan pesan user-friendly
    if (strpos($e->getMessage(), 'Access denied') !== false) {
        die("Error: Username atau password database salah. Silakan periksa konfigurasi database.");
    } elseif (strpos($e->getMessage(), 'Unknown database') !== false) {
        die("Error: Database tidak ditemukan. Silakan periksa nama database.");
    } elseif (strpos($e->getMessage(), "Can't connect") !== false) {
        die("Error: Tidak dapat terhubung ke server database. Silakan periksa host database.");
    } else {
        die("Error: Gagal terhubung ke database. Silakan coba lagi nanti.");
    }
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

// Start session dengan konfigurasi yang aman
if (session_status() == PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']),
        'use_strict_mode' => true
    ]);
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

// Fungsi untuk mendapatkan base URL dengan deteksi otomatis
function getBaseUrl() {
    $protocol = 'http://';
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $protocol = 'https://';
    } elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
        $protocol = 'https://';
    } elseif (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) {
        $protocol = 'https://';
    }
    
    $host = $_SERVER['HTTP_HOST'];
    $script = $_SERVER['SCRIPT_NAME'];
    $path = dirname($script);
    
    // Pastikan path berakhir dengan slash
    if ($path === '/' || $path === '\\') {
        $path = '/';
    } else {
        $path = rtrim($path, '/\\') . '/';
    }
    
    return $protocol . $host . $path;
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
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
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

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk test koneksi database (untuk debugging)
function testDatabaseConnection() {
    global $pdo;
    try {
        $stmt = $pdo->query("SELECT 1");
        return true;
    } catch(PDOException $e) {
        error_log("Database test failed: " . $e->getMessage());
        return false;
    }
}

// Fungsi untuk mengecek apakah tabel exists
function tableExists($tableName) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SHOW TABLES LIKE ?");
        $stmt->execute([$tableName]);
        return $stmt->rowCount() > 0;
    } catch(PDOException $e) {
        error_log("Table check failed: " . $e->getMessage());
        return false;
    }
}
?>
