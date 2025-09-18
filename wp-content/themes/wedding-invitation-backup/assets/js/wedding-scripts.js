// Wedding Invitation JavaScript with GSAP Animations

document.addEventListener('DOMContentLoaded', function() {
    // Initialize everything
    initPageLoader();
    initNavigation();
    initCountdown();
    initFloatingHearts();
    initBackToTop();
    initGSAPAnimations();
    initRSVPForm();
    
    // Page loader
    function initPageLoader() {
        const loader = document.getElementById('page-loader');
        
        // Hide loader after everything is loaded
        window.addEventListener('load', function() {
            gsap.to(loader, {
                opacity: 0,
                duration: 0.5,
                onComplete: function() {
                    loader.classList.add('hidden');
                    // Trigger page entrance animations
                    triggerPageAnimations();
                }
            });
        });
    }
    
    // Navigation functionality
    function initNavigation() {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        const header = document.getElementById('main-header');
        
        // Mobile menu toggle
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.contains('hidden');
                
                if (isHidden) {
                    mobileMenu.classList.remove('hidden');
                    gsap.fromTo(mobileMenu, 
                        { height: 0, opacity: 0 }, 
                        { height: 'auto', opacity: 1, duration: 0.3 }
                    );
                } else {
                    gsap.to(mobileMenu, {
                        height: 0,
                        opacity: 0,
                        duration: 0.3,
                        onComplete: function() {
                            mobileMenu.classList.add('hidden');
                        }
                    });
                }
            });
        }
        
        // Header scroll effect
        let lastScrollY = window.scrollY;
        
        window.addEventListener('scroll', function() {
            const currentScrollY = window.scrollY;
            
            if (currentScrollY > 100) {
                header.classList.add('bg-white/95', 'shadow-md');
            } else {
                header.classList.remove('bg-white/95', 'shadow-md');
            }
            
            // Hide/show header on scroll
            if (currentScrollY > lastScrollY && currentScrollY > 200) {
                gsap.to(header, { y: -100, duration: 0.3 });
            } else {
                gsap.to(header, { y: 0, duration: 0.3 });
            }
            
            lastScrollY = currentScrollY;
        });
    }
    
    // Countdown timer
    function initCountdown() {
        // Set the wedding date (you can modify this)
        const weddingDate = new Date('2025-12-25T15:00:00').getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = weddingDate - now;
            
            if (timeLeft > 0) {
                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);
                
                // Update countdown display with animation
                animateNumberChange('days', days);
                animateNumberChange('hours', hours);
                animateNumberChange('minutes', minutes);
                animateNumberChange('seconds', seconds);
            } else {
                // Wedding day has arrived!
                document.getElementById('countdown').innerHTML = '<div class="text-4xl text-rose-600 font-serif">🎉 Our Wedding Day is Here! 🎉</div>';
            }
        }
        
        function animateNumberChange(elementId, newValue) {
            const element = document.getElementById(elementId);
            if (element && element.textContent !== newValue.toString().padStart(2, '0')) {
                gsap.to(element, {
                    scale: 1.2,
                    duration: 0.1,
                    onComplete: function() {
                        element.textContent = newValue.toString().padStart(2, '0');
                        gsap.to(element, { scale: 1, duration: 0.1 });
                    }
                });
            }
        }
        
        // Update countdown every second
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
    
    // Floating hearts animation
    function initFloatingHearts() {
        const heartsContainer = document.getElementById('floating-hearts');
        
        function createHeart() {
            const heart = document.createElement('div');
            heart.innerHTML = '💖';
            heart.className = 'floating-heart';
            heart.style.left = Math.random() * 100 + 'vw';
            heart.style.fontSize = (Math.random() * 20 + 15) + 'px';
            heart.style.animationDuration = (Math.random() * 3 + 2) + 's';
            
            heartsContainer.appendChild(heart);
            
            // Remove heart after animation
            setTimeout(() => {
                if (heart.parentNode) {
                    heart.parentNode.removeChild(heart);
                }
            }, 5000);
        }
        
        // Create hearts periodically
        setInterval(createHeart, 3000);
    }
    
    // Back to top button
    function initBackToTop() {
        const backToTopBtn = document.getElementById('back-to-top');
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                gsap.to(backToTopBtn, { opacity: 1, y: 0, duration: 0.3 });
            } else {
                gsap.to(backToTopBtn, { opacity: 0, y: 16, duration: 0.3 });
            }
        });
        
        backToTopBtn.addEventListener('click', function() {
            gsap.to(window, { scrollTo: 0, duration: 1, ease: "power2.out" });
        });
    }
    
    // GSAP Animations
    function initGSAPAnimations() {
        // Register GSAP plugins
        gsap.registerPlugin(ScrollTrigger);
        
        // Parallax effects for hero sections
        gsap.utils.toArray('.parallax-bg').forEach(bg => {
            gsap.to(bg, {
                yPercent: -50,
                ease: "none",
                scrollTrigger: {
                    trigger: bg,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true
                }
            });
        });
        
        // Stagger animations for cards
        gsap.utils.toArray('.card-animate').forEach((card, i) => {
            gsap.fromTo(card, 
                { 
                    opacity: 0, 
                    y: 50,
                    scale: 0.8
                },
                {
                    opacity: 1,
                    y: 0,
                    scale: 1,
                    duration: 0.6,
                    delay: i * 0.1,
                    scrollTrigger: {
                        trigger: card,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        });
        
        // Text reveal animations
        gsap.utils.toArray('.text-reveal').forEach(text => {
            gsap.fromTo(text,
                { opacity: 0, y: 30 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.8,
                    scrollTrigger: {
                        trigger: text,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        });
        
        // Image animations
        gsap.utils.toArray('.image-animate').forEach(img => {
            gsap.fromTo(img,
                { opacity: 0, scale: 1.2 },
                {
                    opacity: 1,
                    scale: 1,
                    duration: 1,
                    scrollTrigger: {
                        trigger: img,
                        start: "top 85%",
                        toggleActions: "play none none reverse"
                    }
                }
            );
        });
    }
    
    // Page entrance animations
    function triggerPageAnimations() {
        // Hero text animation
        const heroTitle = document.querySelector('.hero-title');
        const heroSubtitle = document.querySelector('.hero-subtitle');
        const heroButton = document.querySelector('.hero-button');
        
        if (heroTitle) {
            gsap.fromTo(heroTitle,
                { opacity: 0, y: 50 },
                { opacity: 1, y: 0, duration: 1, ease: "power2.out" }
            );
        }
        
        if (heroSubtitle) {
            gsap.fromTo(heroSubtitle,
                { opacity: 0, y: 30 },
                { opacity: 1, y: 0, duration: 0.8, delay: 0.3, ease: "power2.out" }
            );
        }
        
        if (heroButton) {
            gsap.fromTo(heroButton,
                { opacity: 0, scale: 0.8 },
                { opacity: 1, scale: 1, duration: 0.6, delay: 0.6, ease: "back.out(1.7)" }
            );
        }
        
        // Navigation animation
        gsap.fromTo('#main-header',
            { y: -100, opacity: 0 },
            { y: 0, opacity: 1, duration: 0.8, delay: 0.2 }
        );
    }
    
    // RSVP Form handling
    function initRSVPForm() {
        const rsvpForm = document.getElementById('rsvp-form');
        
        if (rsvpForm) {
            rsvpForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(rsvpForm);
                formData.append('action', 'handle_rsvp');
                formData.append('nonce', wedding_ajax.nonce);
                
                // Show loading state
                const submitBtn = rsvpForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = 'Sending...';
                submitBtn.disabled = true;
                
                // AJAX request
                fetch(wedding_ajax.ajax_url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message with animation
                        showRSVPSuccess(data.data.message);
                        rsvpForm.reset();
                    } else {
                        showRSVPError('Sorry, there was an error submitting your RSVP. Please try again.');
                    }
                })
                .catch(error => {
                    showRSVPError('Sorry, there was an error submitting your RSVP. Please try again.');
                })
                .finally(() => {
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                });
            });
        }
    }
    
    function showRSVPSuccess(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'fixed top-20 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50';
        successDiv.textContent = message;
        document.body.appendChild(successDiv);
        
        gsap.fromTo(successDiv,
            { opacity: 0, y: -50, scale: 0.8 },
            { opacity: 1, y: 0, scale: 1, duration: 0.5 }
        );
        
        setTimeout(() => {
            gsap.to(successDiv, {
                opacity: 0,
                y: -50,
                duration: 0.5,
                onComplete: () => successDiv.remove()
            });
        }, 4000);
    }
    
    function showRSVPError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'fixed top-20 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50';
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);
        
        gsap.fromTo(errorDiv,
            { opacity: 0, y: -50, scale: 0.8 },
            { opacity: 1, y: 0, scale: 1, duration: 0.5 }
        );
        
        setTimeout(() => {
            gsap.to(errorDiv, {
                opacity: 0,
                y: -50,
                duration: 0.5,
                onComplete: () => errorDiv.remove()
            });
        }, 4000);
    }
    
    // Utility functions
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Image lazy loading with intersection observer
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                observer.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
});

// Additional GSAP animations for specific pages
function initPageSpecificAnimations() {
    const currentPage = document.body.className;
    
    // Home page animations
    if (currentPage.includes('home')) {
        // Add home-specific animations
    }
    
    // Invitation page animations
    if (currentPage.includes('invitation')) {
        // Envelope opening animation
        initEnvelopeAnimation();
    }
    
    // Love story page animations
    if (currentPage.includes('love-story')) {
        // Timeline animations
        initTimelineAnimations();
    }
}

function initEnvelopeAnimation() {
    const envelope = document.querySelector('.envelope');
    if (envelope) {
        gsap.set('.envelope-top', { transformOrigin: 'bottom center' });
        
        envelope.addEventListener('click', function() {
            gsap.to('.envelope-top', {
                rotationX: -180,
                duration: 1,
                ease: "power2.out"
            });
            
            gsap.to('.invitation-content', {
                opacity: 1,
                y: 0,
                duration: 0.8,
                delay: 0.5,
                ease: "power2.out"
            });
        });
    }
}

function initTimelineAnimations() {
    gsap.utils.toArray('.timeline-item').forEach((item, i) => {
        gsap.fromTo(item,
            { opacity: 0, x: i % 2 === 0 ? -50 : 50 },
            {
                opacity: 1,
                x: 0,
                duration: 0.8,
                scrollTrigger: {
                    trigger: item,
                    start: "top 80%",
                    toggleActions: "play none none reverse"
                }
            }
        );
    });
}
