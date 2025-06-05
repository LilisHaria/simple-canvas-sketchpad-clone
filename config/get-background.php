
<?php
// Fungsi untuk mendapatkan background image
function getAuthBackground() {
    global $pdo;
    
    try {
        $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = 'auth_background'");
        $stmt->execute();
        $result = $stmt->fetch();
        
        if ($result && file_exists($result['setting_value'])) {
            return $result['setting_value'];
        }
    } catch(PDOException $e) {
        // Fallback ke file
        if (file_exists('config/background.txt')) {
            $bg = file_get_contents('config/background.txt');
            if ($bg && file_exists($bg)) {
                return $bg;
            }
        }
    }
    
    // Default background jika tidak ada
    return 'https://images.unsplash.com/photo-1649972904349-6e44c42644a7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80';
}
?>
