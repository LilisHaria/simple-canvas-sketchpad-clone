
<?php
require_once 'config/koneksi.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pelanggan = $_SESSION['user_id'];
    $id_lapangan = sanitize_input($_POST['id_lapangan']);
    $id_jadwal = sanitize_input($_POST['id_jadwal']);
    $tanggal = sanitize_input($_POST['tanggal']);
    $durasi = intval($_POST['durasi']);
    $catatan = sanitize_input($_POST['catatan']);
    
    try {
        $pdo->beginTransaction();
        
        // Cek apakah jadwal masih tersedia pada tanggal tersebut
        $check_stmt = $pdo->prepare("
            SELECT COUNT(*) as count 
            FROM booking 
            WHERE id_lapangan = ? AND id_jadwal = ? AND tanggal = ?
        ");
        $check_stmt->execute([$id_lapangan, $id_jadwal, $tanggal]);
        
        if ($check_stmt->fetch()['count'] > 0) {
            throw new Exception("Jadwal tidak tersedia pada tanggal tersebut");
        }
        
        // Insert booking
        $booking_stmt = $pdo->prepare("
            INSERT INTO booking (id_pelanggan, id_lapangan, id_jadwal, tanggal) 
            VALUES (?, ?, ?, ?)
        ");
        $booking_stmt->execute([$id_pelanggan, $id_lapangan, $id_jadwal, $tanggal]);
        $id_booking = $pdo->lastInsertId();
        
        // Insert detail booking
        $detail_stmt = $pdo->prepare("
            INSERT INTO detail_booking (id_booking, durasi, catatan) 
            VALUES (?, ?, ?)
        ");
        $detail_stmt->execute([$id_booking, $durasi, $catatan]);
        
        // Insert log booking
        $log_stmt = $pdo->prepare("
            INSERT INTO log_booking (id_booking, waktu) 
            VALUES (?, NOW())
        ");
        $log_stmt->execute([$id_booking]);
        
        $pdo->commit();
        
        $_SESSION['success'] = "Booking berhasil! Terima kasih telah menggunakan ArenaKuy.";
        header('Location: my-booking.php');
        exit;
        
    } catch (Exception $e) {
        $pdo->rollback();
        $_SESSION['error'] = "Booking gagal: " . $e->getMessage();
        header('Location: search.php');
        exit;
    }
}
?>
