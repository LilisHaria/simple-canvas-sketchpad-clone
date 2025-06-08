
// User Header JavaScript untuk ArenaKuy

document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const hamburgerToggle = document.getElementById('hamburgerToggle');
    const userSidebar = document.getElementById('userSidebar');
    const sidebarClose = document.getElementById('sidebarClose');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    const searchToggle = document.getElementById('searchToggle');
    const searchModal = document.getElementById('searchModal');
    const searchClose = document.getElementById('searchClose');
    
    const userMenuToggle = document.getElementById('userMenuToggle');
    const userDropdown = document.getElementById('userDropdown');
    
    // Hamburger menu toggle
    if (hamburgerToggle && userSidebar) {
        hamburgerToggle.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            openSidebar();
        });
    }
    
    // Open sidebar
    function openSidebar() {
        if (userSidebar) {
            userSidebar.classList.add('active');
            if (sidebarOverlay) {
                sidebarOverlay.classList.add('active');
            }
            if (hamburgerToggle) {
                hamburgerToggle.classList.add('active');
            }
            document.body.style.overflow = 'hidden';
        }
    }
    
    // Close sidebar
    function closeSidebar() {
        if (userSidebar) {
            userSidebar.classList.remove('active');
            if (sidebarOverlay) {
                sidebarOverlay.classList.remove('active');
            }
            if (hamburgerToggle) {
                hamburgerToggle.classList.remove('active');
            }
            document.body.style.overflow = '';
        }
    }
    
    // Close sidebar events
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
    
    // User dropdown functionality
    if (userMenuToggle && userDropdown) {
        userMenuToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('active');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            userDropdown.classList.remove('active');
        });
        
        userDropdown.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Close modals on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSidebar();
            if (searchModal) {
                searchModal.classList.remove('active');
            }
            if (userDropdown) {
                userDropdown.classList.remove('active');
            }
        }
    });
    
    // Highlight active menu item
    highlightActiveMenuItem();
    
    // Touch/swipe handling for mobile sidebar
    if (window.innerWidth <= 768) {
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        
        document.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, { passive: true });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const swipeDistance = touchEndX - touchStartX;
            
            // Swipe right to open sidebar (from left edge)
            if (swipeDistance > swipeThreshold && touchStartX < 50 && 
                userSidebar && !userSidebar.classList.contains('active')) {
                openSidebar();
            }
            
            // Swipe left to close sidebar
            if (swipeDistance < -swipeThreshold && userSidebar && 
                userSidebar.classList.contains('active')) {
                closeSidebar();
            }
        }
    }
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });
});

// Highlight active menu item
function highlightActiveMenuItem() {
    const currentPage = window.location.pathname.split('/').pop() || 'user_dashboard.php';
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    const navItems = document.querySelectorAll('.nav-item');
    
    sidebarItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && href.includes(currentPage)) {
            item.classList.add('active');
        }
    });
    
    navItems.forEach(item => {
        const href = item.getAttribute('href');
        if (href && href.includes(currentPage)) {
            item.classList.add('active');
        }
    });
}
