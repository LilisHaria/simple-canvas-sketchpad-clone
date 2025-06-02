
<?php
require_once 'config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ArenaKuy! - Platform Booking Lapangan Futsal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="fas fa-futbol me-2"></i>ArenaKuy!
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">
                            <i class="fas fa-search me-1"></i>Cari Arena
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?= htmlspecialchars($_SESSION['user_name']) ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="dashboard.php">Dashboard</a></li>
                                <li><a class="dropdown-item" href="my-booking.php">My Booking</a></li>
                                <li><a class="dropdown-item" href="history.php">History</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="auth/logout.php">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/login_pelanggan.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-light ms-2" href="auth/register_pelanggan.php">Daftar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Booking Lapangan Futsal Jadi Mudah!</h1>
                    <p class="lead mb-4">Platform terpercaya untuk booking lapangan futsal dengan berbagai pilihan arena indoor, outdoor, dan synthetic.</p>
                    <div class="d-flex gap-3">
                        <a href="search.php" class="btn btn-warning btn-lg">
                            <i class="fas fa-search me-2"></i>Cari Arena
                        </a>
                        <?php if (!isLoggedIn()): ?>
                            <a href="auth/register_pelanggan.php" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Daftar Gratis
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-futbol fa-10x text-warning opacity-75"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur Section -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Mengapa Pilih ArenaKuy?</h2>
                <p class="lead text-muted">Kemudahan dan kenyamanan dalam satu platform</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                            <h5>Booking 24/7</h5>
                            <p class="text-muted">Booking kapan saja, di mana saja. Sistem online yang tersedia 24 jam.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-map-marker-alt fa-3x text-success mb-3"></i>
                            <h5>Berbagai Lokasi</h5>
                            <p class="text-muted">Pilihan lapangan indoor, outdoor, dan synthetic di berbagai lokasi.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 text-center">
                        <div class="card-body">
                            <i class="fas fa-shield-alt fa-3x text-warning mb-3"></i>
                            <h5>Aman & Terpercaya</h5>
                            <p class="text-muted">Sistem booking yang aman dengan konfirmasi otomatis.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-light py-5">
        <div class="container text-center">
            <h2 class="display-6 fw-bold mb-4">Siap Mulai Bermain?</h2>
            <p class="lead mb-4">Bergabunglah dengan ribuan pemain lainnya dan nikmati pengalaman booking yang mudah!</p>
            <a href="search.php" class="btn btn-primary btn-lg">
                <i class="fas fa-play me-2"></i>Mulai Booking Sekarang
            </a>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
