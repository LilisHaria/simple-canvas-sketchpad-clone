// Navigation functions
function goToSignIn() {
    window.location.href = 'signin.html';
}

function goToSignUp() {
    window.location.href = 'signup.html';
}

// Sidebar functions
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

// Search functionality
function handleSearch(event) {
    event.preventDefault();
    const searchTerm = event.target.querySelector('input').value.toLowerCase();
    
    if (!searchTerm) {
        alert('Masukkan kata kunci pencarian');
        return;
    }
    
    // Send search request to PHP backend
    fetch('search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'search=' + encodeURIComponent(searchTerm)
    })
    .then(response => response.json())
    .then(data => {
        displaySearchResults(data);
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat pencarian');
    });
}

function displaySearchResults(results) {
    const arenaResults = document.getElementById('arenaResults');
    
    if (results.length === 0) {
        arenaResults.innerHTML = '<div class="col-12 text-center"><p>Tidak ada lapangan yang ditemukan</p></div>';
        return;
    }
    
    arenaResults.innerHTML = results.map(arena => `
        <div class="col-md-4 mb-4">
            <div class="card arena-card h-100">
                <img src="https://images.unsplash.com/photo-1431324155629-1a6deb1dec8d?w=400&h=300&fit=crop" class="card-img-top" alt="${arena.nama_lapangan}">
                <div class="card-body">
                    <h5 class="card-title">${arena.nama_lapangan}</h5>
                    <p class="card-text">Lokasi: ${arena.lokasi || 'Tidak tersedia'}</p>
                    <p class="card-text">Harga per jam: Rp ${arena.harga_per_jam || '0'}</p>
                    <button class="btn btn-primary" onclick="bookArena('${arena.nama_lapangan}')">Book Now</button>
                </div>
            </div>
        </div>
    `).join('');
}

function bookArena(arenaName) {
    alert(`Anda memilih ${arenaName}! Silakan login terlebih dahulu untuk melakukan booking.`);
    goToSignIn();
}

// Form handling with PHP backend
function handleSignIn(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    fetch('auth.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Login berhasil!');
            localStorage.setItem('user', JSON.stringify(data.user));
            window.location.href = 'index.html';
        } else {
            alert(data.message || 'Login gagal');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

function handleSignUp(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const password = formData.get('password');
    const confirmPassword = formData.get('confirmPassword');
    
    if (password !== confirmPassword) {
        alert('Password tidak cocok');
        return;
    }
    
    if (password.length < 6) {
        alert('Password minimal 6 karakter');
        return;
    }
    
    fetch('register.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Registrasi berhasil! Silakan login.');
            window.location.href = 'signin.html';
        } else {
            alert(data.message || 'Registrasi gagal');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
}

// Document ready functions
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    
    // Add scroll effect to navbar
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            if (window.scrollY > 100) {
                navbar.style.background = 'rgba(255, 255, 255, 0.98)';
            } else {
                navbar.style.background = 'rgba(255, 255, 255, 0.95)';
            }
        }
    });
    
    // Arena card interactions
    const arenaCards = document.querySelectorAll('.arena-card');
    arenaCards.forEach(card => {
        card.addEventListener('click', function() {
            const arenaName = this.querySelector('h5').textContent;
            alert(`You selected ${arenaName}! (This is a demo - booking functionality would go here)`);
        });
    });
    
    // Form animations
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            if (!this.value) {
                this.parentElement.classList.remove('focused');
            }
        });
    });
    
    // Close sidebar when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (sidebar && sidebar.classList.contains('active') && 
            !sidebar.contains(event.target) && 
            !sidebarToggle.contains(event.target)) {
            toggleSidebar();
        }
    });
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.getElementById('mobileMenu');
        const mobileToggle = document.querySelector('.mobile-menu-toggle');
        
        if (mobileMenu && mobileMenu.classList.contains('active') && 
            !mobileMenu.contains(event.target) && 
            !mobileToggle.contains(event.target)) {
            toggleMobileMenu();
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        const mobileMenu = document.getElementById('mobileMenu');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        
        if (window.innerWidth > 768) {
            if (mobileMenu) mobileMenu.classList.remove('active');
        }
        
        if (window.innerWidth > 1024) {
            if (sidebar) sidebar.classList.remove('active');
            if (overlay) overlay.classList.remove('active');
        }
    });
});
