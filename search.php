
<?php
$page_title = 'Hasil Pencarian';
$base_path = '';
require_once 'includes/header.php';
require_once 'config/database.php';

$search_query = $_GET['q'] ?? '';
$results = [];

if (!empty($search_query)) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM arenas WHERE is_active = TRUE AND (arena_name LIKE ? OR description LIKE ? OR location LIKE ?) ORDER BY arena_name");
        $search_param = "%$search_query%";
        $stmt->execute([$search_param, $search_param, $search_param]);
        $results = $stmt->fetchAll();
    } catch(PDOException $e) {
        // Handle error
        $error = "Terjadi kesalahan saat mencari arena.";
    }
}
?>

<div class="page-content">
    <div class="container" style="padding: 40px 20px;">
        <h1 style="margin-bottom: 30px; color: #333;">
            <?php if (!empty($search_query)): ?>
                Hasil Pencarian: "<?= htmlspecialchars($search_query) ?>"
            <?php else: ?>
                Pencarian Arena
            <?php endif; ?>
        </h1>
        
        <!-- Search Form -->
        <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 40px;">
            <form method="GET" style="display: flex; gap: 15px; align-items: end;">
                <div class="form-group" style="flex: 1; margin-bottom: 0;">
                    <label for="q">Cari Arena</label>
                    <input type="text" id="q" name="q" class="form-control" 
                           placeholder="Nama arena, lokasi, atau deskripsi..." 
                           value="<?= htmlspecialchars($search_query) ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Cari
                </button>
            </form>
        </div>

        <!-- Search Results -->
        <?php if (!empty($search_query)): ?>
            <?php if (isset($error)): ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 4rem; color: #ef4444; margin-bottom: 20px;"></i>
                    <h3 style="color: #666; margin-bottom: 10px;">Terjadi Kesalahan</h3>
                    <p style="color: #999;"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php elseif (empty($results)): ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <i class="fas fa-search" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                    <h3 style="color: #666; margin-bottom: 10px;">Tidak ada arena ditemukan</h3>
                    <p style="color: #999;">Coba gunakan kata kunci yang berbeda atau lihat semua arena yang tersedia</p>
                    <a href="arenas.php" class="btn btn-primary" style="margin-top: 20px;">Lihat Semua Arena</a>
                </div>
            <?php else: ?>
                <div style="margin-bottom: 20px;">
                    <p style="color: #666;">Ditemukan <?= count($results) ?> arena yang sesuai dengan pencarian Anda</p>
                </div>
                
                <div class="arena-grid">
                    <?php foreach ($results as $arena): ?>
                        <div class="arena-card">
                            <div class="arena-image">
                                <?php if ($arena['image_url']): ?>
                                    <img src="<?= htmlspecialchars($arena['image_url']) ?>" alt="<?= htmlspecialchars($arena['arena_name']) ?>">
                                <?php else: ?>
                                    <div class="arena-placeholder">
                                        <i class="fas fa-futbol"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="arena-info">
                                <h3><?= htmlspecialchars($arena['arena_name']) ?></h3>
                                <p class="arena-location">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <?= htmlspecialchars($arena['location']) ?>
                                </p>
                                <p class="arena-description"><?= htmlspecialchars($arena['description']) ?></p>
                                <div class="arena-price">
                                    <span class="price">Rp <?= number_format($arena['price_per_hour'], 0, ',', '.') ?></span>
                                    <span class="per-hour">/jam</span>
                                </div>
                                <?php if (isLoggedIn()): ?>
                                    <a href="booking.php?arena_id=<?= $arena['arena_id'] ?>" class="btn btn-book">Book Sekarang</a>
                                <?php else: ?>
                                    <a href="signin.php" class="btn btn-book">Login untuk Book</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px;">
                <i class="fas fa-search" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                <h3 style="color: #666; margin-bottom: 10px;">Masukkan kata kunci pencarian</h3>
                <p style="color: #999;">Gunakan form di atas untuk mencari arena berdasarkan nama, lokasi, atau deskripsi</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .container {
        padding: 20px 10px !important;
    }
    
    h1 {
        font-size: 1.8rem !important;
    }
    
    form {
        flex-direction: column !important;
        gap: 15px !important;
    }
    
    .arena-grid {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
    
    .arena-card {
        margin: 0 10px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 15px 5px !important;
    }
    
    h1 {
        font-size: 1.5rem !important;
        margin-bottom: 20px !important;
    }
    
    .arena-card {
        margin: 0 5px;
    }
    
    .arena-info {
        padding: 20px !important;
    }
    
    .arena-info h3 {
        font-size: 1.3rem !important;
    }
}
</style>

<script src="assets/js/main.js"></script>
</body>
</html>
