// Main JavaScript File - Gen Z Modern Interactions

// Mobile Menu Toggle with Animation
function toggleMobileMenu() {
    const navMenu = document.querySelector('.nav-menu');
    const toggleBtn = document.querySelector('.mobile-menu-toggle');
    
    if (navMenu) {
        if (navMenu.style.display === 'flex') {
            navMenu.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => {
                navMenu.style.display = 'none';
            }, 300);
        } else {
            navMenu.style.display = 'flex';
            navMenu.style.animation = 'slideIn 0.3s ease';
        }
    }
}

// Smooth Scroll with Offset
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            const offset = 80;
            const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    });
});

// Form Validation Helper
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[\d\s\-\+\(\)]+$/;
    return re.test(phone) && phone.replace(/\D/g, '').length >= 10;
}

// Scroll Animations - Intersection Observer
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            observer.unobserve(entry.target);
        }
    });
}, observerOptions);

// Parallax Effect for Hero Banner
function parallaxEffect() {
    const heroBanner = document.querySelector('.hero-banner');
    if (heroBanner) {
        const scrolled = window.pageYOffset;
        const rate = scrolled * 0.5;
        heroBanner.style.transform = `translateY(${rate}px)`;
    }
}

// Sticky Header Effect
let lastScroll = 0;
function handleScroll() {
    const header = document.querySelector('.main-header');
    const currentScroll = window.pageYOffset;
    
    if (header) {
        if (currentScroll > 100) {
            header.style.background = 'rgba(255, 255, 255, 0.98)';
            header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
        } else {
            header.style.background = 'rgba(255, 255, 255, 0.95)';
            header.style.boxShadow = '0 2px 8px rgba(0,0,0,0.08)';
        }
    }
    
    lastScroll = currentScroll;
}

// Animate Counter Numbers
function animateCounter(element, target, duration = 2000) {
    let start = 0;
    const increment = target / (duration / 16);
    
    const timer = setInterval(() => {
        start += increment;
        if (start >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(start);
        }
    }, 16);
}

// Add Ripple Effect to Buttons
function addRippleEffect() {
    document.querySelectorAll('.btn').forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.classList.add('ripple');
            
            this.appendChild(ripple);
            
            setTimeout(() => {
                ripple.remove();
            }, 600);
        });
    });
}

// Package Card Hover Effects
function enhancePackageCards() {
    const packageCards = document.querySelectorAll('.package-card');
    packageCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

// Smooth Page Transitions
function addPageTransitions() {
    document.querySelectorAll('a[href^="/"], a[href$=".php"]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.getAttribute('href').startsWith('#')) {
                e.preventDefault();
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.3s';
                
                setTimeout(() => {
                    window.location.href = this.getAttribute('href');
                }, 300);
            }
        });
    });
}

// Typing Effect for Hero Text
function typingEffect(element, text, speed = 50) {
    let i = 0;
    element.textContent = '';
    
    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    
    type();
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set minimum date for travel date inputs
    const travelDateInputs = document.querySelectorAll('input[type="date"]');
    travelDateInputs.forEach(input => {
        if (input.name === 'travel_date') {
            input.min = new Date().toISOString().split('T')[0];
        }
    });
    
    // Add scroll animations to elements
    const animateElements = document.querySelectorAll('.package-card, .feature-item, .contact-item');
    animateElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
    
    // Add ripple effect to buttons
    addRippleEffect();
    
    // Enhance package cards
    enhancePackageCards();
    
    // Scroll event listeners
    window.addEventListener('scroll', () => {
        handleScroll();
        parallaxEffect();
    });
    
    // Add smooth transitions
    document.body.style.transition = 'opacity 0.3s';
    
    // Animate hero text on homepage
    const heroTitle = document.querySelector('.hero-banner h1');
    if (heroTitle && window.location.pathname === '/' || window.location.pathname.includes('index.php')) {
        const originalText = heroTitle.textContent;
        setTimeout(() => {
            typingEffect(heroTitle, originalText, 30);
        }, 500);
    }
    
    // Add loading animation
    window.addEventListener('load', () => {
        document.body.style.opacity = '1';
    });
    
    // Add cursor trail effect (optional, Gen Z love)
    createCursorTrail();
});

// Cursor Trail Effect (Modern Gen Z Touch)
function createCursorTrail() {
    const trail = [];
    const trailLength = 20;
    
    for (let i = 0; i < trailLength; i++) {
        const dot = document.createElement('div');
        dot.style.position = 'fixed';
        dot.style.width = '6px';
        dot.style.height = '6px';
        dot.style.borderRadius = '50%';
        dot.style.background = `rgba(99, 102, 241, ${0.3 - i * 0.015})`;
        dot.style.pointerEvents = 'none';
        dot.style.zIndex = '9999';
        dot.style.transition = 'all 0.1s ease';
        document.body.appendChild(dot);
        trail.push({ element: dot, x: 0, y: 0 });
    }
    
    let mouseX = 0, mouseY = 0;
    
    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });
    
    function animateTrail() {
        let x = mouseX;
        let y = mouseY;
        
        trail.forEach((dot, index) => {
            const nextX = x;
            const nextY = y;
            
            dot.element.style.left = nextX + 'px';
            dot.element.style.top = nextY + 'px';
            
            x += (dot.x - nextX) * 0.3;
            y += (dot.y - nextY) * 0.3;
            
            dot.x = nextX;
            dot.y = nextY;
        });
        
        requestAnimationFrame(animateTrail);
    }
    
    animateTrail();
}

// Add CSS for ripple effect
const style = document.createElement('style');
style.textContent = `
    .btn {
        position: relative;
        overflow: hidden;
    }
    
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        transform: scale(0);
        animation: ripple-animation 0.6s ease-out;
        pointer-events: none;
    }
    
    @keyframes ripple-animation {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(-20px);
        }
    }
`;
document.head.appendChild(style);
