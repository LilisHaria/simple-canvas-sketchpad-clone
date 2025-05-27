
<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search = sanitize_input($_POST['search']);
    
    if (empty($search)) {
        echo json_encode([]);
        exit;
    }
    
    try {
        // Search in lapangan table
        $stmt = $pdo->prepare("SELECT * FROM lapangan WHERE nama_lapangan LIKE ? OR lokasi LIKE ? ORDER BY nama_lapangan");
        $searchTerm = "%$search%";
        $stmt->execute([$searchTerm, $searchTerm]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($results);
    } catch(PDOException $e) {
        echo json_encode(['error' => 'Gagal mencari data: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'Method not allowed']);
}
?>
