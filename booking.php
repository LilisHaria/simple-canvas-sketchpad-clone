
<?php
header('Content-Type: application/json');
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action == 'create') {
        $id_pelanggan = sanitize_input($_POST['id_pelanggan']);
        $id_lapangan = sanitize_input($_POST['id_lapangan']);
        $tanggal_booking = sanitize_input($_POST['tanggal_booking']);
        $jam_mulai = sanitize_input($_POST['jam_mulai']);
        $jam_selesai = sanitize_input($_POST['jam_selesai']);
        
        if (empty($id_pelanggan) || empty($id_lapangan) || empty($tanggal_booking) || empty($jam_mulai) || empty($jam_selesai)) {
            echo json_encode(['success' => false, 'message' => 'Semua field harus diisi']);
            exit;
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO booking (id_pelanggan, id_lapangan, tanggal_booking, jam_mulai, jam_selesai, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$id_pelanggan, $id_lapangan, $tanggal_booking, $jam_mulai, $jam_selesai]);
            
            echo json_encode(['success' => true, 'message' => 'Booking berhasil dibuat']);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal membuat booking: ' . $e->getMessage()]);
        }
    }
    
    if ($action == 'get_bookings') {
        $id_pelanggan = sanitize_input($_POST['id_pelanggan']);
        
        try {
            $stmt = $pdo->prepare("
                SELECT b.*, l.nama_lapangan, l.lokasi, l.harga_per_jam 
                FROM booking b 
                JOIN lapangan l ON b.id_lapangan = l.id_lapangan 
                WHERE b.id_pelanggan = ? 
                ORDER BY b.tanggal_booking DESC
            ");
            $stmt->execute([$id_pelanggan]);
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo json_encode(['success' => true, 'bookings' => $bookings]);
        } catch(PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Gagal mengambil data booking: ' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>
