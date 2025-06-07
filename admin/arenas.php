
<?php
require_once '../config/database.php';
requireAdmin();

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'add':
                    $stmt = $pdo->prepare("INSERT INTO arenas (arena_name, location, description, price_per_hour, type, status) VALUES (?, ?, ?, ?, ?, 'active')");
                    $stmt->execute([
                        sanitize_input($_POST['arena_name']),
                        sanitize_input($_POST['location']),
                        sanitize_input($_POST['description']),
                        $_POST['price_per_hour'],
                        sanitize_input($_POST['type'])
                    ]);
                    $success = "Lapangan berhasil ditambahkan!";
                    break;
                    
                case 'edit':
                    $stmt = $pdo->prepare("UPDATE arenas SET arena_name = ?, location = ?, description = ?, price_per_hour = ?, type = ?, status = ? WHERE arena_id = ?");
                    $stmt->execute([
                        sanitize_input($_POST['arena_name']),
                        sanitize_input($_POST['location']),
                        sanitize_input($_POST['description']),
                        $_POST['price_per_hour'],
                        sanitize_input($_POST['type']),
                        sanitize_input($_POST['status']),
                        $_POST['arena_id']
                    ]);
                    $success = "Lapangan berhasil diupdate!";
                    break;
                    
                case 'delete':
                    $stmt = $pdo->prepare("UPDATE arenas SET status = 'archived' WHERE arena_id = ?");
                    $stmt->execute([$_POST['arena_id']]);
                    $success = "Lapangan berhasil diarsipkan!";
                    break;
            }
        } catch(PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Get arenas
try {
    $stmt = $pdo->query("SELECT * FROM arenas WHERE status != 'archived' ORDER BY created_at DESC");
    $arenas = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = "Error loading arenas: " . $e->getMessage();
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>
    
    <div class="main-content">
        <div class="container" style="padding-top: 100px;">
            <div class="page-header">
                <h1><i class="fas fa-futbol"></i> Kelola Lapangan</h1>
                <button class="btn btn-primary" onclick="showAddModal()">
                    <i class="fas fa-plus"></i> Tambah Lapangan
                </button>
            </div>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>
            
            <!-- Arenas Table -->
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Lapangan</th>
                            <th>Lokasi</th>
                            <th>Tipe</th>
                            <th>Harga/Jam</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($arenas as $arena): ?>
                            <tr>
                                <td><?= htmlspecialchars($arena['arena_name']) ?></td>
                                <td><?= htmlspecialchars($arena['location']) ?></td>
                                <td><?= htmlspecialchars($arena['type']) ?></td>
                                <td>Rp <?= number_format($arena['price_per_hour']) ?></td>
                                <td>
                                    <span class="badge badge-<?= $arena['status'] == 'active' ? 'success' : 'warning' ?>">
                                        <?= ucfirst($arena['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="editArena(<?= htmlspecialchars(json_encode($arena)) ?>)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteArena(<?= $arena['arena_id'] ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Modal -->
    <div class="modal" id="arenaModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Tambah Lapangan</h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form method="POST" id="arenaForm">
                <input type="hidden" name="action" id="formAction" value="add">
                <input type="hidden" name="arena_id" id="arenaId">
                
                <div class="form-group">
                    <label for="arena_name">Nama Lapangan</label>
                    <input type="text" name="arena_name" id="arena_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input type="text" name="location" id="location" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="type">Tipe Lapangan</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="indoor">Indoor</option>
                        <option value="outdoor">Outdoor</option>
                        <option value="synthetic">Synthetic</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="price_per_hour">Harga per Jam</label>
                    <input type="number" name="price_per_hour" id="price_per_hour" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                </div>
                
                <div class="form-group" id="statusGroup" style="display: none;">
                    <label for="status">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script>
        function showAddModal() {
            document.getElementById('modalTitle').textContent = 'Tambah Lapangan';
            document.getElementById('formAction').value = 'add';
            document.getElementById('statusGroup').style.display = 'none';
            document.getElementById('arenaForm').reset();
            document.getElementById('arenaModal').style.display = 'flex';
        }
        
        function editArena(arena) {
            document.getElementById('modalTitle').textContent = 'Edit Lapangan';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('arenaId').value = arena.arena_id;
            document.getElementById('arena_name').value = arena.arena_name;
            document.getElementById('location').value = arena.location;
            document.getElementById('type').value = arena.type;
            document.getElementById('price_per_hour').value = arena.price_per_hour;
            document.getElementById('description').value = arena.description;
            document.getElementById('status').value = arena.status;
            document.getElementById('statusGroup').style.display = 'block';
            document.getElementById('arenaModal').style.display = 'flex';
        }
        
        function deleteArena(id) {
            if (confirm('Yakin ingin mengarsipkan lapangan ini?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="arena_id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        function closeModal() {
            document.getElementById('arenaModal').style.display = 'none';
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('arenaModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>
</html>
