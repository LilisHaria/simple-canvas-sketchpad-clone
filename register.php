
<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($username) || empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Semua field harus diisi']);
        exit;
    }
    
    // Check if user already exists
    $stmt = $pdo->prepare("SELECT id_pelanggan FROM pelanggan WHERE email = ? OR nama = ?");
    $stmt->execute([$email, $username]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email atau username sudah terdaftar']);
        exit;
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    try {
        // Insert new user
        $stmt = $pdo->prepare("INSERT INTO pelanggan (nama, email, no_telepon) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $email]); // Using email as phone number placeholder
        
        echo json_encode(['success' => true, 'message' => 'Registrasi berhasil']);
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
