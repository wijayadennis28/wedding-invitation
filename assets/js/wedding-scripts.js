// Wedding Invitation Dynamic Section JavaScript with GSAP Animations

document.addEventListener('DOMContentLoaded', function() {
    // Only run if we're on the front page with dynamic section
    if (!document.querySelector('.dynamic-section')) {
        return;
    }
    
    initDynamicSection();
    
    // Dynamic section initialization
    function initDynamicSection() {
        gsap.registerPlugin(ScrollTrigger);
        
        // Get elements
        const heroContent = document.getElementById('hero-content');
        const invitationContent = document.getElementById('invitation-content');
        const backgroundImage = document.getElementById('background-image');
        const backgroundOverlay = document.getElementById('background-overlay');
        
        // Check if elements exist
        if (!heroContent || !invitationContent || !backgroundImage || !backgroundOverlay) {
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
        
        // Get theme directory URI from WordPress localized data
        const themeUri = wedding_ajax.theme_uri || '';
        
        // Function to switch to invitation content
        function switchToInvitation() {
            if (isInvitationMode) return;
            isInvitationMode = true;
            
            // Change background image
            backgroundImage.src = themeUri + '/assets/images/s.jpg';
            
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
                themeUri + '/assets/images/wedding.png' : 
                themeUri + '/assets/images/wedding-landscape.png';
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
                // Switch to invitation when scrolled down 50%
                if (self.progress > 0.5) {
                    switchToInvitation();
                } else {
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
            const newIsMobile = window.innerWidth <= 768;
            if (!isInvitationMode) {
                const newSrc = newIsMobile ? 
                    themeUri + '/assets/images/wedding.png' : 
                    themeUri + '/assets/images/wedding-landscape.png';
                backgroundImage.src = newSrc;
            }
        });
    }
});
