
// Navigation functions
function goToSignIn() {
    window.location.href = 'signin.html';
}

function goToSignUp() {
    window.location.href = 'signup.html';
}

// Form handling
function handleSignIn(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const email = formData.get('email');
    const password = formData.get('password');
    
    // Simple validation
    if (!email || !password) {
        alert('Please fill in all fields');
        return;
    }
    
    // Here you would typically send the data to a server
    console.log('Sign in attempt:', { email, password });
    
    // For demo purposes, show success message
    alert('Sign in successful! (This is a demo)');
    
    // Redirect to home page
    window.location.href = 'index.html';
}

function handleSignUp(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    const username = formData.get('username');
    const email = formData.get('email');
    const password = formData.get('password');
    const confirmPassword = formData.get('confirmPassword');
    
    // Validation
    if (!username || !email || !password || !confirmPassword) {
        alert('Please fill in all fields');
        return;
    }
    
    if (password !== confirmPassword) {
        alert('Passwords do not match');
        return;
    }
    
    if (password.length < 6) {
        alert('Password must be at least 6 characters long');
        return;
    }
    
    // Here you would typically send the data to a server
    console.log('Sign up attempt:', { username, email, password });
    
    // For demo purposes, show success message
    alert('Sign up successful! (This is a demo)');
    
    // Redirect to sign in page
    window.location.href = 'signin.html';
}

// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-menu-toggle');
    const navMenu = document.querySelector('.nav-menu');
    
    if (mobileToggle && navMenu) {
        mobileToggle.addEventListener('click', function() {
            navMenu.classList.toggle('mobile-menu-active');
        });
    }
    
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
});

// Arena card interactions
document.addEventListener('DOMContentLoaded', function() {
    const arenaCards = document.querySelectorAll('.arena-card');
    
    arenaCards.forEach(card => {
        card.addEventListener('click', function() {
            const arenaName = this.querySelector('h4').textContent;
            alert(`You selected ${arenaName}! (This is a demo - booking functionality would go here)`);
        });
    });
});

// Form animations
document.addEventListener('DOMContentLoaded', function() {
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
});
