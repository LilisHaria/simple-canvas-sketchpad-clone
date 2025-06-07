
// Main JavaScript untuk ArenaKuy dengan mobile responsive yang diperbaiki

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
    
    // Mobile menu toggle for landing page
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    
    // Initialize mobile sidebar functionality
    initMobileSidebar();
    
    // Toggle sidebar
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openSidebar();
        });
    }
    
    // Close sidebar
    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove('active');
            sidebar.classList.remove('show');
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            document.body.style.overflow = '';
            document.body.classList.remove('sidebar-open');
        }
    }
    
    // Open sidebar
    function openSidebar() {
        if (sidebar) {
            sidebar.classList.add('active');
            sidebar.classList.add('show');
            if (sidebarOverlay) {
                sidebarOverlay.classList.add('active');
            }
            document.body.style.overflow = 'hidden';
            document.body.classList.add('sidebar-open');
        }
    }
    
    if (sidebarClose) {
        sidebarClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeSidebar();
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function(e) {
            e.preventDefault();
            closeSidebar();
        });
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
    
    // Mobile dropdown functionality (header)
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
    
    // Mobile menu toggle for landing page
    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            mobileMenuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
                mobileMenuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        });
    }
    
    // Close all modals/menus on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
            if (searchModal) {
                searchModal.classList.remove('active');
            }
            if (mobileDropdownMenu) {
                mobileDropdownMenu.classList.remove('active');
            }
            if (mobileMenu) {
                mobileMenuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
            }
        }
    });
    
    // Highlight active menu item
    highlightActiveMenuItem();
    
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

    // Enhanced responsive handling
    handleResize();
    window.addEventListener('resize', handleResize);
    
    // Touch/swipe handling for mobile
    initTouchHandling();
});

// Initialize mobile sidebar with better responsive handling
function initMobileSidebar() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    
    if (!sidebar) return;
    
    // Ensure sidebar starts hidden on mobile
    if (window.innerWidth <= 768) {
        sidebar.classList.remove('active', 'show');
        document.body.classList.remove('sidebar-open');
    }
    
    // Add mobile-specific classes
    if (window.innerWidth <= 768) {
        sidebar.classList.add('mobile-sidebar');
    } else {
        sidebar.classList.remove('mobile-sidebar');
    }
}

// Enhanced responsive sidebar handling
function handleResize() {
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mobileDropdownMenu = document.getElementById('mobileDropdownMenu');
    const isMobile = window.innerWidth <= 768;
    const isSmallMobile = window.innerWidth <= 480;
    
    if (!isMobile) {
        // Desktop: close mobile dropdown and reset sidebar
        if (mobileDropdownMenu) {
            mobileDropdownMenu.classList.remove('active');
        }
        
        if (sidebar) {
            sidebar.classList.remove('mobile-sidebar', 'active', 'show');
            sidebar.style.width = '';
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            document.body.style.overflow = '';
            document.body.classList.remove('sidebar-open');
        }
    } else {
        // Mobile: setup mobile behavior
        if (sidebar) {
            sidebar.classList.add('mobile-sidebar');
            
            // Adjust sidebar width based on screen size
            if (isSmallMobile) {
                sidebar.style.width = '280px';
            } else {
                sidebar.style.width = '300px';
            }
            
            // Ensure sidebar is closed on resize to mobile
            if (!sidebar.classList.contains('active')) {
                sidebar.classList.remove('show');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('active');
                }
                document.body.style.overflow = '';
                document.body.classList.remove('sidebar-open');
            }
        }
    }
}

// Highlight active menu item
function highlightActiveMenuItem() {
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
}

// Touch/swipe handling for mobile
function initTouchHandling() {
    if (window.innerWidth <= 768) {
        let touchStartX = 0;
        let touchEndX = 0;
        let touchStartY = 0;
        let touchEndY = 0;
        
        const sidebar = document.getElementById('sidebar');
        
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
            touchStartY = e.changedTouches[0].screenY;
        }, { passive: true });
        
        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            touchEndY = e.changedTouches[0].screenY;
            handleSwipe();
        }, { passive: true });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;
            const verticalDistance = Math.abs(touchEndY - touchStartY);
            
            // Only handle horizontal swipes
            if (verticalDistance > 100) return;
            
            // Swipe right to open sidebar (from left edge)
            if (swipeDistance > swipeThreshold && touchStartX < 50 && 
                sidebar && !sidebar.classList.contains('active')) {
                sidebar.classList.add('active');
                sidebar.classList.add('show');
                const overlay = document.getElementById('sidebarOverlay');
                if (overlay) overlay.classList.add('active');
                document.body.style.overflow = 'hidden';
                document.body.classList.add('sidebar-open');
            }
            
            // Swipe left to close sidebar
            if (swipeDistance < -swipeThreshold && sidebar && 
                sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                sidebar.classList.remove('show');
                const overlay = document.getElementById('sidebarOverlay');
                if (overlay) overlay.classList.remove('active');
                document.body.style.overflow = '';
                document.body.classList.remove('sidebar-open');
            }
        }
    }
}

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
