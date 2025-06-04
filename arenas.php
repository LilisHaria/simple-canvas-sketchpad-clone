
<?php
require_once 'config/database.php';

// Ambil data arena dengan filter
$search = $_GET['search'] ?? '';
$price_filter = $_GET['price_filter'] ?? '';

$sql = "SELECT * FROM arenas WHERE is_active = TRUE";
$params = [];

if (!empty($search)) {
    $sql .= " AND (arena_name LIKE ? OR description LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if (!empty($price_filter)) {
    switch ($price_filter) {
        case 'low':
            $sql .= " AND price_per_hour < 100000";
            break;
        case 'medium':
            $sql .= " AND price_per_hour BETWEEN 100000 AND 150000";
            break;
        case 'high':
            $sql .= " AND price_per_hour > 150000";
            break;
    }
}

$sql .= " ORDER BY arena_name";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$arenas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Arena - ArenaKuy</title>
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
                    <li><a href="index.php"><i class="fas fa-home"></i> Beranda</a></li>
                    <li><a href="arenas.php" class="active"><i class="fas fa-map-marker-alt"></i> Pilih Arena</a></li>
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
            <div class="container" style="padding: 40px 20px;">
                <h1 style="text-align: center; margin-bottom: 30px; color: #333;">Pilih Arena Favorit Anda</h1>
                
                <!-- Search & Filter -->
                <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 40px;">
                    <form method="GET" style="display: grid; grid-template-columns: 1fr 200px 120px; gap: 15px; align-items: end;">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="search">Cari Arena</label>
                            <input type="text" id="search" name="search" class="form-control" 
                                   placeholder="Nama arena atau deskripsi..." value="<?= htmlspecialchars($search) ?>">
                        </div>
                        
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="price_filter">Filter Harga</label>
                            <select id="price_filter" name="price_filter" class="form-control">
                                <option value="">Semua Harga</option>
                                <option value="low" <?= $price_filter == 'low' ? 'selected' : '' ?>>< Rp 100.000</option>
                                <option value="medium" <?= $price_filter == 'medium' ? 'selected' : '' ?>>Rp 100.000 - 150.000</option>
                                <option value="high" <?= $price_filter == 'high' ? 'selected' : '' ?>>> Rp 150.000</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari
                        </button>
                    </form>
                </div>

                <!-- Arena Grid -->
                <?php if (empty($arenas)): ?>
                    <div style="text-align: center; padding: 60px 20px;">
                        <i class="fas fa-search" style="font-size: 4rem; color: #ccc; margin-bottom: 20px;"></i>
                        <h3 style="color: #666; margin-bottom: 10px;">Arena tidak ditemukan</h3>
                        <p style="color: #999;">Coba ubah kata kunci pencarian atau filter harga</p>
                    </div>
                <?php else: ?>
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
                                    <?php if (isLoggedIn()): ?>
                                        <a href="booking.php?arena_id=<?= $arena['arena_id'] ?>" class="btn btn-book">Book Sekarang</a>
                                    <?php else: ?>
                                        <a href="auth/login.php" class="btn btn-book">Login untuk Book</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>
