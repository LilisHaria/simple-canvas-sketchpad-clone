<?php
$page_title = 'Dashboard';
$base_path = '';
require_once 'config/database.php';
require_once 'includes/header.php';

// Ambil data arena
$stmt = $pdo->prepare("SELECT * FROM arenas WHERE is_active = TRUE ORDER BY arena_name LIMIT 6");
$stmt->execute();
$arenas = $stmt->fetchAll();

// Ambil statistik jika user login
$stats = null;
if (isLoggedIn()) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM bookings WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $stats = $stmt->fetch();
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Selamat Datang di ArenaKuy!</h1>
            <p>Platform booking lapangan futsal terpercaya dengan berbagai pilihan arena berkualitas</p>
            <?php if (isLoggedIn()): ?>
                <div class="stats-card">
                    <h3>Total Booking Anda: <?= $stats['total'] ?></h3>
                </div>
            <?php endif; ?>
            <a href="arenas.php" class="btn btn-primary">Pilih Arena Sekarang</a>
        </div>
    </div>
</section>

<!-- Arena Grid -->
<section class="arena-section">
    <div class="container">
        <h2>Pilihan Arena Kami</h2>
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
        <div class="text-center mt-4">
            <a href="arenas.php" class="btn btn-secondary">Lihat Semua Arena</a>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2>Mengapa Pilih ArenaKuy?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <i class="fas fa-clock"></i>
                <h3>Booking 24/7</h3>
                <p>Sistem booking online yang tersedia 24 jam setiap hari</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-shield-alt"></i>
                <h3>Aman & Terpercaya</h3>
                <p>Keamanan data dan transaksi yang terjamin</p>
            </div>
            <div class="feature-card">
                <i class="fas fa-star"></i>
                <h3>Kualitas Terbaik</h3>
                <p>Lapangan berkualitas dengan fasilitas lengkap</p>
            </div>
        </div>
    </div>
</section>

<style>
.hero-section {
    background: linear-gradient(135deg, #2D7298 0%, #1a5a7a 100%);
    color: white;
    padding: 120px 20px 80px;
    margin-top: 70px;
}

.hero-content {
    text-align: center;
    max-width: 800px;
    margin: 0 auto;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 20px;
    font-weight: 700;
}

.hero-content p {
    font-size: 1.2rem;
    margin-bottom: 30px;
    opacity: 0.9;
}

.stats-card {
    background: rgba(255, 255, 255, 0.1);
    padding: 20px;
    border-radius: 10px;
    margin: 20px 0;
    backdrop-filter: blur(10px);
}

.arena-section {
    padding: 80px 20px;
}

.arena-section h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 50px;
    color: #333;
}

.arena-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 30px;
    margin-bottom: 40px;
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

.features-section {
    background: #f8fafc;
    padding: 80px 20px;
}

.features-section h2 {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 50px;
    color: #333;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 40px;
}

.feature-card {
    text-align: center;
    padding: 40px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.feature-card i {
    font-size: 3rem;
    color: #2D7298;
    margin-bottom: 20px;
}

.feature-card h3 {
    font-size: 1.3rem;
    margin-bottom: 15px;
    color: #333;
}

.feature-card p {
    color: #666;
    line-height: 1.6;
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

.btn-secondary {
    background: #2D7298;
    color: white;
}

.btn-secondary:hover {
    background: #1a5a7a;
    transform: translateY(-2px);
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

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.text-center {
    text-align: center;
}

.mt-4 {
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .arena-grid {
        grid-template-columns: 1fr;
    }
    
    .features-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?>
