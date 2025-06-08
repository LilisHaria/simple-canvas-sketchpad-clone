
<?php
require_once 'config/database.php';

// Redirect admin ke dashboard admin
if (isAdmin()) {
    header('Location: admin/admin_dashboard.php');
    exit;
}

// Require login untuk user biasa
requireLogin();

$page_title = 'Pilih Arena';

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

$sql .= " ORDER BY arena_name LIMIT 6";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $arenas = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error loading arenas: " . $e->getMessage();
    $arenas = [];
}

// Include user header
include 'includes/user_header.php';
?>

<div class="container" style="padding-top: 50px; max-width: 1200px; margin: 0 auto; padding-left: 1rem; padding-right: 1rem;">
    <h1 style="text-align: center; margin-bottom: 30px; color: #333; font-size: 2.5rem;">Pilih Arena Favorit Anda</h1>
    
    <?php if (isset($error)): ?>
        <div style="background: #fee; border: 1px solid #fcc; color: #c66; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>
    
    <!-- Search & Filter -->
    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 40px;">
        <form method="GET" style="display: grid; grid-template-columns: 1fr 200px 120px; gap: 15px; align-items: end;">
            <div style="margin-bottom: 0;">
                <label for="search" style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Cari Arena</label>
                <input type="text" id="search" name="search" 
                       style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;"
                       placeholder="Nama arena atau deskripsi..." value="<?= htmlspecialchars($search) ?>">
            </div>
            
            <div style="margin-bottom: 0;">
                <label for="price_filter" style="display: block; margin-bottom: 5px; font-weight: 500; color: #333;">Filter Harga</label>
                <select id="price_filter" name="price_filter" 
                        style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; font-size: 1rem;">
                    <option value="">Semua Harga</option>
                    <option value="low" <?= $price_filter == 'low' ? 'selected' : '' ?>>< Rp 100.000</option>
                    <option value="medium" <?= $price_filter == 'medium' ? 'selected' : '' ?>>Rp 100.000 - 150.000</option>
                    <option value="high" <?= $price_filter == 'high' ? 'selected' : '' ?>>> Rp 150.000</option>
                </select>
            </div>
            
            <button type="submit" style="background: #DDA853; color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 500; cursor: pointer; transition: background 0.3s;">
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
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; margin-bottom: 40px;">
            <?php foreach ($arenas as $arena): ?>
                <div style="background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: transform 0.3s;">
                    <div style="height: 200px; background: linear-gradient(135deg, #2D7298, #DDA853); position: relative;">
                        <?php if ($arena['image_url']): ?>
                            <img src="<?= htmlspecialchars($arena['image_url']) ?>" alt="<?= htmlspecialchars($arena['arena_name']) ?>"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        <?php else: ?>
                            <div style="display: flex; align-items: center; justify-content: center; height: 100%; color: white;">
                                <i class="fas fa-futbol" style="font-size: 4rem;"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div style="padding: 25px;">
                        <h3 style="color: #333; margin-bottom: 10px; font-size: 1.4rem;"><?= htmlspecialchars($arena['arena_name']) ?></h3>
                        <p style="color: #666; margin-bottom: 10px; display: flex; align-items: center;">
                            <i class="fas fa-map-marker-alt" style="margin-right: 8px; color: #2D7298;"></i>
                            <?= htmlspecialchars($arena['location']) ?>
                        </p>
                        <p style="color: #777; line-height: 1.5; margin-bottom: 20px;"><?= htmlspecialchars($arena['description']) ?></p>
                        <div style="display: flex; align-items: baseline; margin-bottom: 20px;">
                            <span style="font-size: 2rem; font-weight: bold; color: #10b981;">
                                Rp <?= number_format($arena['price_per_hour'], 0, ',', '.') ?>
                            </span>
                            <span style="margin-left: 8px; color: #666;">/jam</span>
                        </div>
                        <a href="user_booking.php?arena_id=<?= $arena['arena_id'] ?>" 
                           style="display: block; background: #10b981; color: white; text-align: center; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: 500; transition: background 0.3s;">
                            Book Sekarang
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
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
        display: flex !important;
        flex-direction: column !important;
        gap: 15px !important;
    }
    
    div[style*="grid-template-columns"] {
        grid-template-columns: 1fr !important;
        gap: 20px !important;
    }
}
</style>

</div> <!-- Close main-content -->

<script src="assets/js/user_header.js"></script>
</body>
</html>
