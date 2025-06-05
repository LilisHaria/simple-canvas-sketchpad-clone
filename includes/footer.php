
    </div> <!-- End main-wrapper -->
    
    <!-- Footer -->
    <footer class="main-footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-futbol"></i>
                        <span>ArenaKuy</span>
                    </div>
                    <p>Platform booking lapangan futsal terpercaya untuk semua kebutuhan olahraga Anda.</p>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="arenas.php">Pilih Arena</a></li>
                        <li><a href="about.php">Tentang Kami</a></li>
                        <li><a href="contact.php">Kontak</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>Kontak Info</h4>
                    <div class="contact-info">
                        <p><i class="fas fa-phone"></i> +62 123 456 789</p>
                        <p><i class="fas fa-envelope"></i> info@arenakuy.com</p>
                        <p><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 ArenaKuy. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <style>
    .main-footer {
        background: linear-gradient(135deg, #2D7298 0%, #1a5a7a 100%);
        color: white;
        margin-top: 50px;
    }
    
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px 20px;
    }
    
    .footer-content {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
        margin-bottom: 30px;
    }
    
    .footer-logo {
        display: flex;
        align-items: center;
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 15px;
    }
    
    .footer-logo i {
        margin-right: 10px;
        font-size: 1.8rem;
    }
    
    .footer-section h4 {
        margin-bottom: 15px;
        color: #ffd700;
    }
    
    .footer-section ul {
        list-style: none;
        padding: 0;
    }
    
    .footer-section ul li {
        margin-bottom: 8px;
    }
    
    .footer-section ul li a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s;
    }
    
    .footer-section ul li a:hover {
        color: #ffd700;
    }
    
    .contact-info p {
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    
    .contact-info i {
        margin-right: 10px;
        width: 20px;
    }
    
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding-top: 20px;
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
    }
    </style>
</body>
</html>
