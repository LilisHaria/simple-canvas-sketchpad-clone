
<?php
// Error reporting untuk development/debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

// Konfigurasi database untuk Hostinger
$host = 'localhost';
$dbname = 'u985354573_arenakuy';
$username = 'u985354573_arenakuy_lilis';
$password = '0909loqweA@#$';

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
        PDO::ATTR_TIMEOUT => 30
    ];
    
    $pdo = new PDO($dsn, $username, $password, $options);
    
} catch(PDOException $e) {
    // Tampilkan error untuk debugging
    echo "<div style='background: #ff4444; color: white; padding: 20px; margin: 20px; border-radius: 5px;'>";
    echo "<h3>Database Connection Error:</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . __FILE__ . "</p>";
    echo "<p><strong>Line:</strong> " . __LINE__ . "</p>";
    echo "</div>";
    die();
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
        header('Location: login.php');
        exit;
    }
}

// Fungsi untuk redirect jika bukan admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: user_dashboard.php');
        exit;
    }
}

// Update session dengan role ketika login - Disesuaikan dengan kolom database baru
function setUserSession($user_data) {
    $_SESSION['user_id'] = $user_data['user_id'] ?? $user_data['id'];
    
    // Perbaikan untuk field nama - cek beberapa kemungkinan field
    if (isset($user_data['full_name']) && !empty($user_data['full_name'])) {
        $_SESSION['user_name'] = $user_data['full_name'];
    } elseif (isset($user_data['name']) && !empty($user_data['name'])) {
        $_SESSION['user_name'] = $user_data['name'];
    } elseif (isset($user_data['username']) && !empty($user_data['username'])) {
        $_SESSION['user_name'] = $user_data['username'];
    } else {
        $_SESSION['user_name'] = 'User'; // Default fallback
    }
    
    $_SESSION['user_email'] = $user_data['email'] ?? '';
    $_SESSION['user_role'] = $user_data['role'] ?? 'user';
    $_SESSION['username'] = $user_data['username'] ?? $_SESSION['user_name'];
    $_SESSION['user_phone'] = $user_data['phone_number'] ?? '';
    $_SESSION['user_profile_picture'] = $user_data['profile_picture'] ?? 'default_profile.jpg';
}

// Fungsi untuk logout
function logout() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    session_destroy();
    header('Location: index.php');
    exit;
}

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Test koneksi database
try {
    $test = $pdo->query("SELECT 1");
    // echo "<p style='color: green;'>Database connected successfully!</p>";
} catch(PDOException $e) {
    echo "<div style='background: #ff4444; color: white; padding: 10px; margin: 10px;'>";
    echo "Database test failed: " . htmlspecialchars($e->getMessage());
    echo "</div>";
}
?>
