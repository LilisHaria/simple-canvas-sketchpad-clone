
<?php
require_once '../config/database.php';
requireAdmin();

$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                try {
                    $stmt = $pdo->prepare("INSERT INTO arenas (arena_name, location, price_per_hour, type, facilities, description, status) VALUES (?, ?, ?, ?, ?, ?, 'active')");
                    $stmt->execute([
                        sanitize_input($_POST['arena_name']),
                        sanitize_input($_POST['location']),
                        floatval($_POST['price_per_hour']),
                        sanitize_input($_POST['type']),
                        sanitize_input($_POST['facilities']),
                        sanitize_input($_POST['description'])
                    ]);
                    $success = "Lapangan berhasil ditambahkan!";
                } catch(PDOException $e) {
                    $error = "Error: " . $e->getMessage();
                }
                break;
                
            case 'edit':
                try {
                    $stmt = $pdo->prepare("UPDATE arenas SET arena_name = ?, location = ?, price_per_hour = ?, type = ?, facilities = ?, description = ? WHERE arena_id = ?");
                    $stmt->execute([
                        sanitize_input($_POST['arena_name']),
                        sanitize_input($_POST['location']),
                        floatval($_POST['price_per_hour']),
                        sanitize_input($_POST['type']),
                        sanitize_input($_POST['facilities']),
                        sanitize_input($_POST['description']),
                        intval($_POST['arena_id'])
                    ]);
                    $success = "Lapangan berhasil diupdate!";
                } catch(PDOException $e) {
                    $error = "Error: " . $e->getMessage();
                }
                break;
                
            case 'toggle_status':
                try {
                    $current_status = $_POST['current_status'] === 'active' ? 'inactive' : 'active';
                    $stmt = $pdo->prepare("UPDATE arenas SET status = ? WHERE arena_id = ?");
                    $stmt->execute([$current_status, intval($_POST['arena_id'])]);
                    $success = "Status lapangan berhasil diubah!";
                } catch(PDOException $e) {
                    $error = "Error: " . $e->getMessage();
                }
                break;
                
            case 'archive':
                try {
                    $stmt = $pdo->prepare("UPDATE arenas SET status = 'archived' WHERE arena_id = ?");
                    $stmt->execute([intval($_POST['arena_id'])]);
                    $success = "Lapangan berhasil diarsipkan!";
                } catch(PDOException $e) {
                    $error = "Error: " . $e->getMessage();
                }
                break;
        }
    }
}

// Get all arenas
try {
    $stmt = $pdo->query("SELECT * FROM arenas ORDER BY created_at DESC");
    $arenas = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error loading arenas: " . $e->getMessage();
    $arenas = [];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lapangan - ArenaKuy Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/header.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>
    
    <div class="main-content">
        <div class="container" style="padding-top: 100px;">
            <div class="admin-header">
                <h1><i class="fas fa-futbol"></i> Kelola Lapangan</h1>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Tambah Lapangan
                </button>
            </div>
            
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            
            <!-- Arenas Table -->
            <div class="admin-table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Lapangan</th>
                            <th>Lokasi</th>
                            <th>Harga/Jam</th>
                            <th>Tipe</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arenas as $arena): ?>
                        <tr>
                            <td><?= $arena['arena_id'] ?></td>
                            <td><?= htmlspecialchars($arena['arena_name']) ?></td>
                            <td><?= htmlspecialchars($arena['location']) ?></td>
                            <td>Rp <?= number_format($arena['price_per_hour']) ?></td>
                            <td><?= htmlspecialchars($arena['type']) ?></td>
                            <td>
                                <span class="status-badge status-<?= $arena['status'] ?>">
                                    <?= ucfirst($arena['status']) ?>
                                </span>
                            </td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-info" onclick="editArena(<?= htmlspecialchars(json_encode($arena)) ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <?php if ($arena['status'] !== 'archived'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle_status">
                                    <input type="hidden" name="arena_id" value="<?= $arena['arena_id'] ?>">
                                    <input type="hidden" name="current_status" value="<?= $arena['status'] ?>">
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        <i class="fas fa-<?= $arena['status'] === 'active' ? 'pause' : 'play' ?>"></i>
                                    </button>
                                </form>
                                <form method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin arsipkan lapangan ini?')">
                                    <input type="hidden" name="action" value="archive">
                                    <input type="hidden" name="arena_id" value="<?= $arena['arena_id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-archive"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add Arena Modal -->
    <div class="modal" id="addArenaModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Tambah Lapangan Baru</h3>
                <span class="modal-close" onclick="closeAddModal()">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label for="arena_name">Nama Lapangan</label>
                    <input type="text" id="arena_name" name="arena_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input type="text" id="location" name="location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="price_per_hour">Harga per Jam</label>
                    <input type="number" id="price_per_hour" name="price_per_hour" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="type">Tipe Lapangan</label>
                    <select id="type" name="type" class="form-control" required>
                        <option value="indoor">Indoor</option>
                        <option value="outdoor">Outdoor</option>
                        <option value="synthetic">Synthetic</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="facilities">Fasilitas</label>
                    <textarea id="facilities" name="facilities" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Lapangan</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Arena Modal -->
    <div class="modal" id="editArenaModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Lapangan</h3>
                <span class="modal-close" onclick="closeEditModal()">&times;</span>
            </div>
            <form method="POST">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" id="edit_arena_id" name="arena_id">
                <div class="form-group">
                    <label for="edit_arena_name">Nama Lapangan</label>
                    <input type="text" id="edit_arena_name" name="arena_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_location">Lokasi</label>
                    <input type="text" id="edit_location" name="location" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_price_per_hour">Harga per Jam</label>
                    <input type="number" id="edit_price_per_hour" name="price_per_hour" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="edit_type">Tipe Lapangan</label>
                    <select id="edit_type" name="type" class="form-control" required>
                        <option value="indoor">Indoor</option>
                        <option value="outdoor">Outdoor</option>
                        <option value="synthetic">Synthetic</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit_facilities">Fasilitas</label>
                    <textarea id="edit_facilities" name="facilities" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="edit_description">Deskripsi</label>
                    <textarea id="edit_description" name="description" class="form-control"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Lapangan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        function openAddModal() {
            document.getElementById('addArenaModal').style.display = 'block';
        }
        
        function closeAddModal() {
            document.getElementById('addArenaModal').style.display = 'none';
        }
        
        function editArena(arena) {
            document.getElementById('edit_arena_id').value = arena.arena_id;
            document.getElementById('edit_arena_name').value = arena.arena_name;
            document.getElementById('edit_location').value = arena.location;
            document.getElementById('edit_price_per_hour').value = arena.price_per_hour;
            document.getElementById('edit_type').value = arena.type;
            document.getElementById('edit_facilities').value = arena.facilities;
            document.getElementById('edit_description').value = arena.description;
            document.getElementById('editArenaModal').style.display = 'block';
        }
        
        function closeEditModal() {
            document.getElementById('editArenaModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>
