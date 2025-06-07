
<?php
// Start session
session_start();

// Include database configuration
require_once 'config/database.php';

// Redirect ke dashboard jika sudah login
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}
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
    <div class="landing-page">
        <!-- Header -->
        <header class="landing-header">
            <div class="container">
                <div class="logo">
                    <i class="fas fa-futbol"></i>
                    <span>ArenaKuy</span>
                </div>
                <nav class="nav-links">
                    <a href="auth/login.php" class="btn btn-outline">Login</a>
                    <a href="auth/register.php" class="btn btn-primary">Daftar</a>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1>Booking Lapangan Futsal Jadi Mudah!</h1>
                    <p>Platform terpercaya untuk booking lapangan futsal di seluruh Indonesia. Cari, pilih, dan booking lapangan favorit Anda dengan mudah.</p>
                    <div class="hero-buttons">
                        <a href="auth/register.php" class="btn btn-primary btn-large">
                            <i class="fas fa-user-plus"></i> Daftar Sekarang
                        </a>
                        <a href="auth/login.php" class="btn btn-outline btn-large">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <i class="fas fa-futbol"></i>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <h2>Mengapa Pilih ArenaKuy?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <i class="fas fa-search"></i>
                        <h3>Mudah Dicari</h3>
                        <p>Temukan lapangan terdekat dengan fitur pencarian yang canggih dan filter yang lengkap.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-mobile-alt"></i>
                        <h3>Booking Online</h3>
                        <p>Booking kapan saja, dimana saja melalui smartphone atau komputer Anda.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-shield-alt"></i>
                        <h3>Aman & Terpercaya</h3>
                        <p>Transaksi aman dengan sistem pembayaran yang terpercaya dan customer service 24/7.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="landing-footer">
            <div class="container">
                <div class="footer-content">
                    <div class="footer-logo">
                        <i class="fas fa-futbol"></i>
                        <span>ArenaKuy</span>
                    </div>
                    <p>&copy; 2024 ArenaKuy. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>

    <style>
    .landing-page {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .landing-header {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 1rem 0;
    }

    .landing-header .container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .logo {
        display: flex;
        align-items: center;
        font-size: 1.5rem;
        font-weight: bold;
        color: #10b981;
    }

    .logo i {
        margin-right: 0.5rem;
        font-size: 2rem;
    }

    .nav-links {
        display: flex;
        gap: 1rem;
    }

    .hero-section {
        flex: 1;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 4rem 0;
        display: flex;
        align-items: center;
    }

    .hero-section .container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .hero-content h1 {
        font-size: 3rem;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .hero-content p {
        font-size: 1.2rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .btn-large {
        padding: 1rem 2rem;
        font-size: 1.1rem;
    }

    .hero-image {
        text-align: center;
    }

    .hero-image i {
        font-size: 15rem;
        opacity: 0.3;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
        }
        40% {
            transform: translateY(-10px);
        }
        60% {
            transform: translateY(-5px);
        }
    }

    .features-section {
        padding: 4rem 0;
        background: #f8fafc;
    }

    .features-section .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .features-section h2 {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 3rem;
        color: #1f2937;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .feature-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .feature-card:hover {
        transform: translateY(-5px);
    }

    .feature-card i {
        font-size: 3rem;
        color: #10b981;
        margin-bottom: 1rem;
    }

    .feature-card h3 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
        color: #1f2937;
    }

    .feature-card p {
        color: #6b7280;
        line-height: 1.6;
    }

    .landing-footer {
        background: #1f2937;
        color: white;
        padding: 2rem 0;
        text-align: center;
    }

    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 2rem;
    }

    .footer-logo {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .footer-logo i {
        margin-right: 0.5rem;
        font-size: 2rem;
        color: #10b981;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .landing-header .container {
            padding: 0 1rem;
        }

        .nav-links {
            flex-direction: column;
            gap: 0.5rem;
        }

        .hero-section .container {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 2rem;
        }

        .hero-content h1 {
            font-size: 2rem;
        }

        .hero-buttons {
            justify-content: center;
        }

        .hero-image i {
            font-size: 8rem;
        }

        .features-section h2 {
            font-size: 2rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>

    <script src="assets/js/main.js"></script>
</body>
</html>
