// Wedding Details Container - Single Container with Background Changes
console.log('🎯 Wedding Details Container JS Loading...');

document.addEventListener('DOMContentLoaded', function() {
    console.log('🎬 Wedding Details Container DOMContentLoaded');
    
    const weddingContainer = document.getElementById('wedding-details-container');
    const scrollWrapper = document.querySelector('.details-scroll-wrapper');
    const navDots = document.querySelectorAll('.nav-dot');
    const closeBtn = document.getElementById('close-details-btn');
    const sections = document.querySelectorAll('.wedding-content-section');
    
    if (!weddingContainer || !scrollWrapper || !sections.length) {
        console.error('❌ Wedding details container elements not found');
        return;
    }
    
    console.log('✅ Found container elements:', {
        container: !!weddingContainer,
        scrollWrapper: !!scrollWrapper,
        sections: sections.length,
        navDots: navDots.length
    });
    
    // Background image URLs from data attributes
    const backgroundImages = {};
    sections.forEach(section => {
        const sectionName = section.dataset.section;
        const bgImage = section.dataset.bgImage;
        if (sectionName && bgImage) {
            backgroundImages[sectionName] = bgImage;
        }
    });
    
    console.log('🖼️ Background images loaded:', backgroundImages);
    
    // Function to change background image
    function changeBackgroundImage(imageUrl) {
        console.log('🔄 Changing background to:', imageUrl);
        
        // Update container background with transition
        weddingContainer.style.backgroundImage = `url(${imageUrl})`;
        weddingContainer.style.backgroundSize = 'cover';
        weddingContainer.style.backgroundPosition = 'center';
        weddingContainer.style.backgroundAttachment = 'fixed';
    }
    
    // Function to update active navigation dot
    function updateActiveDot(index) {
        navDots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });
    }
    
    // Function to scroll to specific section
    function scrollToSection(index) {
        const targetSection = sections[index];
        if (targetSection) {
            const targetPosition = targetSection.offsetTop;
            scrollWrapper.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
        }
    }
    
    // Function to open the wedding details container
    function openWeddingDetails() {
        console.log('🚀 Opening wedding details container');
        
        // Hide the main hero section
        const heroSection = document.querySelector('.dynamic-section');
        if (heroSection) {
            heroSection.style.display = 'none';
        }
        
        // Show the wedding details container
        weddingContainer.style.display = 'block';
        
        // Trigger entrance animation
        setTimeout(() => {
            weddingContainer.classList.add('active');
            
            // Set initial background image (first section)
            const firstSection = sections[0];
            if (firstSection) {
                const firstBgImage = firstSection.dataset.bgImage;
                if (firstBgImage) {
                    changeBackgroundImage(firstBgImage);
                }
            }
        }, 50);
        
        // Reset scroll position to top
        scrollWrapper.scrollTop = 0;
        
        // Update navigation dot
        updateActiveDot(0);
    }
    
    // Function to close the wedding details container
    function closeWeddingDetails() {
        console.log('❌ Closing wedding details container');
        
        // Hide the wedding details container
        weddingContainer.classList.remove('active');
        
        setTimeout(() => {
            weddingContainer.style.display = 'none';
            
            // Show the main hero section
            const heroSection = document.querySelector('.dynamic-section');
            if (heroSection) {
                heroSection.style.display = 'block';
            }
            
            // Scroll back to top of page
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }, 800);
    }
    
    // Scroll listener for background changes
    let scrollTimeout;
    scrollWrapper.addEventListener('scroll', function() {
        // Throttle scroll events
        if (scrollTimeout) {
            clearTimeout(scrollTimeout);
        }
        
        scrollTimeout = setTimeout(() => {
            const scrollTop = scrollWrapper.scrollTop;
            const containerHeight = scrollWrapper.clientHeight;
            
            // Determine which section is most visible
            let activeIndex = 0;
            let maxVisibility = 0;
            
            sections.forEach((section, index) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                const sectionBottom = sectionTop + sectionHeight;
                
                // Calculate how much of this section is visible
                const visibleStart = Math.max(scrollTop, sectionTop);
                const visibleEnd = Math.min(scrollTop + containerHeight, sectionBottom);
                const visibleHeight = Math.max(0, visibleEnd - visibleStart);
                const visibilityRatio = visibleHeight / containerHeight;
                
                if (visibilityRatio > maxVisibility) {
                    maxVisibility = visibilityRatio;
                    activeIndex = index;
                }
            });
            
            // Change background image if section changed
            const activeSection = sections[activeIndex];
            if (activeSection) {
                const bgImage = activeSection.dataset.bgImage;
                if (bgImage) {
                    changeBackgroundImage(bgImage);
                }
            }
            
            // Update navigation dots
            updateActiveDot(activeIndex);
            
        }, 100); // Throttle to 100ms
    });
    
    // Navigation dot click handlers
    navDots.forEach((dot, index) => {
        dot.addEventListener('click', function() {
            console.log('🎯 Navigation dot clicked:', index);
            scrollToSection(index);
        });
    });
    
    // Close button handler
    if (closeBtn) {
        closeBtn.addEventListener('click', closeWeddingDetails);
    }
    
    // Make openWeddingDetails available globally
    window.openWeddingDetails = openWeddingDetails;
    window.closeWeddingDetails = closeWeddingDetails;
    
    console.log('✅ Wedding details container initialized');
});