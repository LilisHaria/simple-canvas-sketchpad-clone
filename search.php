<?php
$page_title = 'Pencarian Arena';
$base_path = '';
require_once 'config/database.php';
require_once 'includes/header.php';

$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$arenas = [];

if (!empty($search_query)) {
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM arenas 
            WHERE is_active = TRUE 
            AND (arena_name LIKE ? OR location LIKE ? OR description LIKE ?)
            ORDER BY arena_name
        ");
        $search_param = '%' . $search_query . '%';
        $stmt->execute([$search_param, $search_param, $search_param]);
        $arenas = $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Search error: " . $e->getMessage());
    }
}
?>

<div class="search-results-page">
    <div class="container">
        <div class="search-header-section">
            <h1>Hasil Pencarian</h1>
            <?php if (!empty($search_query)): ?>
                <p>Menampilkan hasil untuk: "<strong><?= htmlspecialchars($search_query) ?></strong>"</p>
                <p class="results-count"><?= count($arenas) ?> arena ditemukan</p>
            <?php else: ?>
                <p>Masukkan kata kunci untuk mencari arena</p>
            <?php endif; ?>
        </div>

        <?php if (!empty($arenas)): ?>
            <div class="arena-grid">
                <?php foreach ($arenas as $arena): ?>
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
                            <a href="booking.php?arena_id=<?= $arena['arena_id'] ?>" class="btn btn-book">Book Sekarang</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (!empty($search_query)): ?>
            <div class="no-results">
                <div class="no-results-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3>Tidak ada arena ditemukan</h3>
                <p>Coba gunakan kata kunci yang berbeda atau lihat semua arena tersedia</p>
                <a href="arenas.php" class="btn btn-primary">Lihat Semua Arena</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.search-results-page {
    padding: 100px 20px 60px;
    min-height: calc(100vh - 70px);
}

.search-header-section {
    text-align: center;
    margin-bottom: 50px;
}

.search-header-section h1 {
    font-size: 2.5rem;
    color: #333;
    margin-bottom: 20px;
}

.search-header-section p {
    font-size: 1.1rem;
    color: #666;
    margin-bottom: 10px;
}

.results-count {
    color: #2D7298 !important;
    font-weight: 600;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    max-width: 500px;
    margin: 0 auto;
}

.no-results-icon {
    font-size: 4rem;
    color: #ddd;
    margin-bottom: 30px;
}

.no-results h3 {
    font-size: 1.5rem;
    color: #333;
    margin-bottom: 15px;
}

.no-results p {
    color: #666;
    margin-bottom: 30px;
    line-height: 1.6;
}

.arena-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
}

.arena-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s, box-shadow 0.3s;
}

.arena-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.arena-image {
    height: 200px;
    background: linear-gradient(45deg, #f0f0f0, #e0e0e0);
    position: relative;
    overflow: hidden;
}

.arena-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.arena-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    font-size: 4rem;
    color: #999;
}

.arena-info {
    padding: 25px;
}

.arena-info h3 {
    font-size: 1.5rem;
    margin-bottom: 10px;
    color: #333;
}

.arena-location {
    color: #666;
    margin-bottom: 15px;
    font-size: 0.9rem;
}

.arena-location i {
    margin-right: 5px;
}

.arena-description {
    color: #777;
    margin-bottom: 20px;
    line-height: 1.5;
}

.arena-price {
    display: flex;
    align-items: baseline;
    margin-bottom: 20px;
}

.price {
    font-size: 1.5rem;
    font-weight: bold;
    color: #10b981;
}

.per-hour {
    margin-left: 5px;
    color: #666;
    font-size: 0.9rem;
}

.btn {
    display: inline-block;
    padding: 12px 30px;
    border: none;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    cursor: pointer;
    text-align: center;
}

.btn-primary {
    background: #ffd700;
    color: #333;
}

.btn-primary:hover {
    background: #ffed4e;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,215,0,0.4);
}

.btn-book {
    background: #10b981;
    color: white;
    width: 100%;
}

.btn-book:hover {
    background: #059669;
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .search-results-page {
        padding: 90px 15px 40px;
    }
    
    .search-header-section h1 {
        font-size: 2rem;
    }
    
    .arena-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
