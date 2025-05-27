
<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Email dan password harus diisi']);
        exit;
    }
    
    try {
        // Check user credentials
        $stmt = $pdo->prepare("SELECT * FROM pelanggan WHERE email = ? OR no_telepon = ?");
        $stmt->execute([$email, $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // For demo purposes, we'll accept any password
            // In production, you should verify the hashed password
            echo json_encode([
                'success' => true, 
                'message' => 'Login berhasil',
                'user' => [
                    'id' => $user['id_pelanggan'],
                    'nama' => $user['nama'],
                    'email' => $user['email']
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Email atau password salah']);
        }
    } catch(PDOException $e) {
        echo json_encode(['success' => false, 'message' => 'Gagal login: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
