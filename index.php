
<?php
require_once 'config/database.php';

// Ambil data arena
$stmt = $pdo->prepare("SELECT * FROM arenas WHERE is_active = TRUE ORDER BY arena_name");
$stmt->execute();
$arenas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArenaKuy - Platform Booking Lapangan Futsal</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="logo">
                    <i class="fas fa-futbol"></i>
                    <span>ArenaKuy</span>
                </div>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <div class="sidebar-content">
                <ul class="sidebar-menu">
                    <li><a href="index.php" class="active"><i class="fas fa-home"></i> Beranda</a></li>
                    <li><a href="arenas.php"><i class="fas fa-map-marker-alt"></i> Pilih Arena</a></li>
                    <?php if (isLoggedIn()): ?>
                        <li><a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
                        <li><a href="my-bookings.php"><i class="fas fa-calendar-check"></i> Booking Saya</a></li>
                        <li><a href="history.php"><i class="fas fa-history"></i> Riwayat</a></li>
                        <li><a href="auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php"><i class="fas fa-sign-in-alt"></i> Login</a></li>
                        <li><a href="auth/register.php"><i class="fas fa-user-plus"></i> Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Hero Section -->
            <section class="hero">
                <div class="hero-content">
                    <h1>Selamat Datang di ArenaKuy!</h1>
                    <p>Platform booking lapangan futsal terpercaya dengan berbagai pilihan arena berkualitas</p>
                    <a href="arenas.php" class="btn btn-primary">Pilih Arena Sekarang</a>
                </div>
                <div class="hero-image">
                    <i class="fas fa-futbol"></i>
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
                </div>
            </section>

            <!-- Features Section -->
            <section class="features">
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
        </main>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
