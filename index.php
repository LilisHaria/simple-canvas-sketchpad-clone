
<?php require_once 'config/koneksi.php'; ?>
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
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">
                <i class="fas fa-futbol me-2"></i>ArenaKuy!
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#arenas">Arena</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Cari Arena</a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="auth/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-primary me-2" href="auth/login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary" href="auth/register.php">Daftar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center min-vh-100">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">
                        Booking Lapangan Futsal <span class="text-primary">Mudah & Cepat</span>
                    </h1>
                    <p class="lead mb-4">
                        Temukan dan booking lapangan futsal terbaik di sekitar Anda dengan ArenaKuy! 
                        Sistem booking online yang mudah dan terpercaya.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="search.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>Cari Arena
                        </a>
                        <?php if (!isLoggedIn()): ?>
                            <a href="auth/register.php" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Daftar Sekarang
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=600&h=400&fit=crop" 
                         alt="Futsal Arena" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </section>

    <!-- Arena Types Section -->
    <section id="arenas" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">Pilih Tipe Arena Favorit Anda</h2>
                <p class="text-muted">Berbagai pilihan arena berkualitas untuk pengalaman bermain terbaik</p>
            </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=400&h=250&fit=crop" 
                             class="card-img-top" alt="Indoor Arena">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-building text-primary me-2"></i>Arena Indoor
                            </h5>
                            <p class="card-text">Lapangan indoor ber-AC dengan fasilitas lengkap dan kenyamanan maksimal.</p>
                            <a href="search.php?tipe=Indoor" class="btn btn-primary">Lihat Arena</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop" 
                             class="card-img-top" alt="Outdoor Arena">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-tree text-success me-2"></i>Arena Outdoor
                            </h5>
                            <p class="card-text">Lapangan outdoor dengan rumput alami untuk pengalaman bermain autentik.</p>
                            <a href="search.php?tipe=Outdoor" class="btn btn-success">Lihat Arena</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1517466787929-bc90951d0974?w=400&h=250&fit=crop" 
                             class="card-img-top" alt="Synthetic Arena">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fas fa-leaf text-warning me-2"></i>Arena Synthetic
                            </h5>
                            <p class="card-text">Lapangan sintetis modern dengan kualitas profesional dan perawatan mudah.</p>
                            <a href="search.php?tipe=Synthetic" class="btn btn-warning">Lihat Arena</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-futbol me-2"></i>ArenaKuy!</h5>
                    <p class="text-muted">Platform booking lapangan futsal terpercaya di Indonesia.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted">&copy; 2024 ArenaKuy! All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
