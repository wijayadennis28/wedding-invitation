// Wedding Invitation JavaScript - Single Dynamic Section Approach
document.addEventListener('DOMContentLoaded', function() {
    console.log('Wedding script loaded - checking for dynamic section...');
    
    // Only initialize if we're on the front page with dynamic section
    if (document.querySelector('.dynamic-section')) {
        console.log('Dynamic section found - initializing...');
        initDynamicSection();
    } else {
        console.log('Dynamic section NOT found');
    }
});

// Single Section Dynamic Content Animation
function initDynamicSection() {
    gsap.registerPlugin(ScrollTrigger);
    
    // Get elements
    const heroContent = document.getElementById('hero-content');
    const invitationContent = document.getElementById('invitation-content');
    const backgroundImage = document.getElementById('background-image');
    const backgroundOverlay = document.getElementById('background-overlay');
    
    // Check if elements exist
    if (!heroContent || !invitationContent || !backgroundImage || !backgroundOverlay) {
        console.warn('Dynamic section elements not found');
        return;
    }
    
    // Check if mobile device
    const isMobile = window.innerWidth <= 768;
    
    // Set initial positions for hero content
    gsap.set('.monogram-combined', { y: -window.innerHeight, opacity: 0 });
    gsap.set('.couple-names', { opacity: 0, y: 30 });
    
    // Set initial positions for invitation content
    gsap.set('.small-monogram-combined', { y: -window.innerHeight, opacity: 0 });
    gsap.set('.greeting-title', { opacity: 0, y: 30 });
    gsap.set('.invitation-message', { opacity: 0, y: 30 });
    gsap.set('.open-invitation-btn', { opacity: 0, y: 30 });
    gsap.set('.reveal-text', { opacity: 0, y: 30 });
    
    // Initial hero animation
    const heroTl = gsap.timeline();
    heroTl.to('.monogram-combined', {
        duration: 1.5,
        y: 0,
        opacity: 1,
        ease: "power2.out",
        delay: 0.5
    })
    .to('.couple-names', {
        duration: 1,
        opacity: 1,
        y: 0,
        ease: "power2.out"
    }, "+=0.3");
    
    // Background scroll animations
    initBackgroundAnimations(backgroundImage, backgroundOverlay);
    
    // Create invitation timeline (paused initially)
    const invitationTl = gsap.timeline({ paused: true });
    invitationTl
        .to('.small-monogram-combined', {
            duration: 1.5,
            y: 0,
            opacity: 1,
            ease: "power2.out"
        })
        .to('.greeting-title', {
            duration: 1,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.3")
        .to('.invitation-message', {
            duration: 1,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.2")
        .to('.open-invitation-btn', {
            duration: 0.8,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.3")
        .to('.reveal-text', {
            duration: 0.8,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.2");
    
    let isInvitationMode = false;
    
    // Get template directory URI from WordPress localized data
    const templateUri = (typeof weddingData !== 'undefined') ? weddingData.templateUri : '';
    
    // Function to switch to invitation content
    function switchToInvitation() {
        if (isInvitationMode) return;
        isInvitationMode = true;
        
        // Change background image
        backgroundImage.src = templateUri + '/assets/images/s.jpg';
        
        // Update background overlay
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/60 to-black/40 transition-opacity duration-1000';
        
        // Hide hero content
        gsap.to(heroContent, {
            opacity: 0,
            duration: 0.5,
            ease: "power2.out",
            onComplete: () => {
                heroContent.style.display = 'none';
                invitationContent.style.display = 'block';
                
                // Show invitation content with animation
                invitationTl.restart();
            }
        });
    }
    
    // Function to switch back to hero content
    function switchToHero() {
        if (!isInvitationMode) return;
        isInvitationMode = false;
        
        // Change background image back
        const newSrc = isMobile ? 
            templateUri + '/assets/images/wedding.png' : 
            templateUri + '/assets/images/wedding-landscape.png';
        backgroundImage.src = newSrc;
        
        // Update background overlay back
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent transition-opacity duration-1000';
        
        // Hide invitation content
        gsap.to(invitationContent, {
            opacity: 0,
            duration: 0.5,
            ease: "power2.out",
            onComplete: () => {
                invitationContent.style.display = 'none';
                
                // Reset invitation content positions
                gsap.set('.small-monogram-combined', { y: -window.innerHeight, opacity: 0 });
                gsap.set('.greeting-title', { opacity: 0, y: 30 });
                gsap.set('.invitation-message', { opacity: 0, y: 30 });
                gsap.set('.open-invitation-btn', { opacity: 0, y: 30 });
                gsap.set('.reveal-text', { opacity: 0, y: 30 });
                
                heroContent.style.display = 'block';
                
                // Show hero content
                gsap.to(heroContent, {
                    opacity: 1,
                    duration: 0.5,
                    ease: "power2.out"
                });
            }
        });
    }
    
    // ScrollTrigger for content switching
    ScrollTrigger.create({
        trigger: '.dynamic-section',
        start: 'top top',
        end: 'bottom top',
        onUpdate: (self) => {
            // Debug: Log content switching
            console.log('Content switch progress:', Math.floor(self.progress * 100) + '%');
            
            // Switch to invitation when scrolled down 50%
            if (self.progress > 0.5) {
                console.log('Switching to invitation mode');
                switchToInvitation();
            } else {
                console.log('Switching to hero mode');
                switchToHero();
            }
        }
    });
    
    // Button click animation
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('open-invitation-btn')) {
            gsap.to(e.target, {
                scale: 0.95,
                duration: 0.1,
                yoyo: true,
                repeat: 1,
                ease: "power2.inOut"
            });
        }
    });
    
    // Handle window resize for background image
    window.addEventListener('resize', function() {
        const currentIsMobile = window.innerWidth <= 768;
        if (!isInvitationMode) {
            const newSrc = currentIsMobile ? 
                templateUri + '/assets/images/wedding.png' : 
                templateUri + '/assets/images/wedding-landscape.png';
            backgroundImage.src = newSrc;
        }
    });
}

// Background scroll animations
function initBackgroundAnimations(backgroundImage, backgroundOverlay) {
    // Set initial scale to prevent gaps during animations
    gsap.set(backgroundImage, { scale: 1.1 }); // Reduced initial scale
    
    // TEMPORARILY DISABLE MOST ANIMATIONS TO TEST SCROLLING
    
    // Only keep gentle zoom effect during scroll - contained within bounds
    gsap.fromTo(backgroundImage, 
        { scale: 1.1 },
        {
            scale: 1.2, // Smaller range to prevent overflow
            ease: "none",
            scrollTrigger: {
                trigger: '.dynamic-section',
                start: 'top bottom',
                end: 'bottom top',
                scrub: 1 // Slower scrub for testing
            }
        }
    );
    
    // Very gentle blur effect during transition - reduced intensity
    ScrollTrigger.create({
        trigger: '.dynamic-section',
        start: 'top top',
        end: 'bottom top',
        onUpdate: (self) => {
            const progress = self.progress;
            const blurAmount = Math.sin(progress * Math.PI) * 0.5; // Very subtle
            backgroundImage.style.filter = `blur(${blurAmount}px)`;
            
            // Debug: Log scroll progress
            if (Math.floor(progress * 100) % 10 === 0) {
                console.log('Scroll progress:', Math.floor(progress * 100) + '%');
            }
        }
    });
}
