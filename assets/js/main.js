
// Main JavaScript untuk ArenaKuy

document.addEventListener('DOMContentLoaded', function() {
    // Header and Sidebar functionality
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Search modal functionality
    const searchToggle = document.getElementById('searchToggle');
    const searchModal = document.getElementById('searchModal');
    const searchClose = document.getElementById('searchClose');
    
    // Mobile dropdown functionality
    const mobileDropdown = document.getElementById('mobileDropdown');
    const mobileDropdownMenu = document.getElementById('mobileDropdownMenu');
    
    // Toggle sidebar
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        });
    }
    
    // Close sidebar
    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', closeSidebar);
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', closeSidebar);
    }
    
    // Search modal functionality
    if (searchToggle && searchModal) {
        searchToggle.addEventListener('click', function() {
            searchModal.classList.add('active');
            const searchInput = searchModal.querySelector('.search-input');
            if (searchInput) {
                setTimeout(() => searchInput.focus(), 100);
            }
        });
    }
    
    if (searchClose && searchModal) {
        searchClose.addEventListener('click', function() {
            searchModal.classList.remove('active');
        });
    }
    
    if (searchModal) {
        searchModal.addEventListener('click', function(e) {
            if (e.target === searchModal) {
                searchModal.classList.remove('active');
            }
        });
    }
    
    // Mobile dropdown functionality
    if (mobileDropdown && mobileDropdownMenu) {
        mobileDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
            mobileDropdownMenu.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            mobileDropdownMenu.classList.remove('active');
        });
        
        mobileDropdownMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
            if (searchModal) {
                searchModal.classList.remove('active');
            }
            if (mobileDropdownMenu) {
                mobileDropdownMenu.classList.remove('active');
            }
        }
    });
    
    // Highlight active menu item
    const currentPage = window.location.pathname.split('/').pop() || 'dashboard.php';
    const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
    const navLinks = document.querySelectorAll('.nav-link');
    
    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && (href.includes(currentPage) || 
            (currentPage === 'dashboard.php' && href.includes('dashboard.php')) ||
            (currentPage === 'index.php' && href.includes('dashboard.php')))) {
            link.classList.add('active');
        }
    });
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && (href.includes(currentPage) || 
            (currentPage === 'dashboard.php' && href.includes('dashboard.php')) ||
            (currentPage === 'index.php' && href.includes('dashboard.php')))) {
            link.style.background = 'rgba(255, 215, 0, 0.2)';
            link.style.borderRadius = '20px';
        }
    });
    
    // Smooth scrolling untuk anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);

    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            const requiredFields = form.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = '#ef4444';
                } else {
                    field.style.borderColor = '#e1e5e9';
                }
            });
            
            if (!valid) {
                event.preventDefault();
                alert('Mohon lengkapi semua field yang required');
            }
        });
    });

    // Enhanced responsive sidebar handling
    function handleResize() {
        const isMobile = window.innerWidth <= 768;
        const isSmallMobile = window.innerWidth <= 480;
        
        if (!isMobile) {
            // Desktop: close mobile dropdown and ensure sidebar behaves correctly
            if (mobileDropdownMenu) {
                mobileDropdownMenu.classList.remove('active');
            }
            // Reset sidebar width for desktop
            if (sidebar) {
                sidebar.style.width = '';
            }
        } else {
            // Mobile: adjust sidebar width based on screen size
            if (sidebar) {
                if (isSmallMobile) {
                    sidebar.style.width = '220px';
                } else {
                    sidebar.style.width = '250px';
                }
            }
        }
        
        // Close sidebar if resizing from mobile to desktop while open
        if (!isMobile && sidebar && sidebar.classList.contains('active')) {
            closeSidebar();
        }
    }
    
    window.addEventListener('resize', handleResize);
    handleResize(); // Call once on load
    
    // Touch/swipe handling for mobile
    if (window.innerWidth <= 768) {
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;
            
            // Swipe right to open sidebar (from left edge)
            if (swipeDistance > swipeThreshold && touchStartX < 50 && !sidebar.classList.contains('active')) {
                sidebar.classList.add('active');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            // Swipe left to close sidebar
            if (swipeDistance < -swipeThreshold && sidebar.classList.contains('active')) {
                closeSidebar();
            }
        }
    }
});

// Utility functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Loading state untuk buttons
function showLoading(button) {
    const original = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    button.disabled = true;
    
    return function() {
        button.innerHTML = original;
        button.disabled = false;
    };
}

// Konfirmasi aksi
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// Enhanced mobile navigation
function initMobileNavigation() {
    const mainNav = document.querySelector('.main-nav');
    const mobileToggle = document.querySelector('.mobile-dropdown-toggle');
    
    if (window.innerWidth <= 768 && mainNav) {
        mainNav.style.display = 'none';
    } else if (window.innerWidth > 768 && mainNav) {
        mainNav.style.display = 'flex';
    }
}

// Initialize mobile navigation on load and resize
document.addEventListener('DOMContentLoaded', initMobileNavigation);
window.addEventListener('resize', initMobileNavigation);
