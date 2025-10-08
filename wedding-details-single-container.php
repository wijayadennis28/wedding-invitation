<?php
/*
 * Single Container Wedding Details Implementation
 * This file demonstrates the new structure for wedding details in a single scrollable div
 */

get_header(); ?>

<!-- Hero Section (unchanged) -->
<section class="dynamic-section relative" style="margin: 0; padding: 0; height: 100vh; height: 100dvh; min-height: 100vh; min-height: 100dvh;">
    <!-- Dynamic Background Image -->
    <div class="background-container fixed inset-0 z-0" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg'); background-size: cover; background-position: center;">
        <img id="background-image" src="<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg" alt="" class="w-full h-full object-cover transition-opacity duration-1000 responsive-bg-image" loading="eager" decoding="async">
        <div id="background-overlay" class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent transition-opacity duration-1000"></div>
    </div>
    
    <div class="content-wrapper fixed inset-0 flex items-center justify-center z-10 px-safe-left pr-safe-right">
        <div class="w-full mx-auto px-3 xs:px-4 sm:px-6 md:px-8 text-center relative" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: none;">
            <?php 
            $groom_name = get_theme_mod('groom_name', 'Dennis');
            $bride_name = get_theme_mod('bride_name', 'Emilia');
            ?>
            
            <!-- Hero Content (unchanged) -->
            <div id="hero-content" class="content-container max-w-4xl mx-auto" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%;">
                <!-- Hero content here... -->
                <button id="open-invitation-btn" class="invitation-button">
                    OPEN THE INVITATION
                </button>
            </div>

            <!-- NEW: Single Wedding Details Container -->
            <div id="wedding-details-container" class="wedding-details-container" style="display: none;">
                <div class="details-scroll-wrapper">
                    <!-- Section 1: Wedding Details -->
                    <div class="wedding-section" data-section="wedding-details" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('wedding_details') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>">
                        <div class="section-content">
                            <div class="wedding-title-section">
                                <h2 class="wedding-title">The wedding of</h2>
                                <h1 class="wedding-couple-names"><?php echo strtoupper($groom_name . ' AND ' . $bride_name); ?></h1>
                            </div>
                            
                            <div class="wedding-date-section">
                                <p class="wedding-date">Saturday, 22 November 2025</p>
                            </div>
                            
                            <div class="bible-verse-section">
                                <p class="bible-verse">
                                    "Love knows no limit to its endurance,<br>
                                    no end to its trust, love still stands<br>
                                    when all else has fallen."<br>
                                    <span class="verse-reference">1 Corinthians 13:7-8</span>
                                </p>
                            </div>
                            
                            <div class="wedding-info-section">
                                <div class="couple-details">
                                    <div class="groom-info">
                                        <h3 class="person-name">Dennis<br>Wijaya</h3>
                                        <p class="person-details">
                                            First son of<br>
                                            <b>Saleh Widjaja</b> and<br>
                                            <b>Soesi Wijaya</b>
                                        </p>
                                    </div>
                                    
                                    <div class="bride-info">
                                        <h3 class="person-name">Emilia Bewintara</h3>
                                        <p class="person-details">
                                            Second daughter of<br>
                                            <b>Budy Bewintara</b> and<br>
                                            <b>Lindawati</b>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Ceremony & Reception -->
                    <div class="wedding-section" data-section="ceremony-reception" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('ceremony_reception') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>">
                        <div class="section-content">
                            <div class="ceremony-info">
                                <h2 class="ceremony-title">Holy Matrimony</h2>
                                <div class="ceremony-details">
                                    <p class="ceremony-time">10:00 AM</p>
                                    <p class="ceremony-date">Saturday, November 22, 2025</p>
                                    <p class="ceremony-venue">
                                        St. Paul's Church<br>
                                        123 Church Street<br>
                                        Jakarta, Indonesia
                                    </p>
                                </div>
                            </div>
                            
                            <div class="reception-info">
                                <h2 class="reception-title">Wedding Reception</h2>
                                <div class="reception-details">
                                    <p class="reception-time">6:00 PM - 10:00 PM</p>
                                    <p class="reception-date">Saturday, November 22, 2025</p>
                                    <p class="reception-venue">
                                        Grand Ballroom<br>
                                        The Ritz Carlton<br>
                                        Jakarta, Indonesia
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: RSVP -->
                    <div class="wedding-section" data-section="rsvp" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('rsvp') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>">
                        <div class="section-content">
                            <h2 class="rsvp-title">Please Join Us</h2>
                            <p class="rsvp-message">Your presence would make our day even more special</p>
                            
                            <!-- RSVP Form integration -->
                            <div class="rsvp-form-container">
                                <?php 
                                if (function_exists('is_wedding_family_page') && is_wedding_family_page()) {
                                    // Show personalized RSVP form
                                    if (class_exists('WeddingRSVP')) {
                                        echo do_shortcode('[wedding_rsvp_form]');
                                    }
                                } else {
                                    // Show general RSVP message
                                    echo '<p class="general-rsvp">Please contact us to RSVP</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation dots -->
                    <div class="scroll-navigation">
                        <div class="nav-dot active" data-section="wedding-details"></div>
                        <div class="nav-dot" data-section="ceremony-reception"></div>
                        <div class="nav-dot" data-section="rsvp"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
/* Single Container Wedding Details Styles */
.wedding-details-container {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    height: 100vh;
    overflow: hidden;
}

.details-scroll-wrapper {
    height: 100vh;
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
    scroll-snap-type: y mandatory;
    -webkit-overflow-scrolling: touch;
}

.wedding-section {
    min-height: 100vh;
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    scroll-snap-align: start;
    position: relative;
    padding: 2rem;
}

.section-content {
    max-width: 800px;
    width: 100%;
    text-align: center;
    color: white;
    z-index: 10;
    position: relative;
}

/* Wedding Details Section */
.wedding-title {
    font-size: 1.2rem;
    font-weight: 300;
    margin-bottom: 1rem;
    letter-spacing: 0.1em;
    font-style: italic;
}

.wedding-couple-names {
    font-size: 2rem;
    font-weight: 500;
    margin-bottom: 2rem;
    letter-spacing: 0.2em;
}

.wedding-date {
    font-size: 1.1rem;
    font-weight: 300;
    margin-bottom: 2rem;
    letter-spacing: 0.05em;
}

.bible-verse {
    font-size: 0.95rem;
    line-height: 1.6;
    font-weight: 300;
    margin-bottom: 3rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.verse-reference {
    display: block;
    margin-top: 1rem;
    font-size: 0.8rem;
    letter-spacing: 0.1em;
    opacity: 0.8;
}

.couple-details {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
    max-width: 600px;
    margin: 0 auto;
}

.person-name {
    font-size: 1.2rem;
    font-weight: 500;
    margin-bottom: 1rem;
}

.person-details {
    font-size: 0.9rem;
    font-weight: 300;
    line-height: 1.5;
}

/* Ceremony & Reception Section */
.ceremony-info, .reception-info {
    margin-bottom: 3rem;
}

.ceremony-title, .reception-title {
    font-size: 1.8rem;
    font-weight: 400;
    margin-bottom: 1.5rem;
    letter-spacing: 0.1em;
}

.ceremony-details, .reception-details {
    font-size: 1rem;
    line-height: 1.6;
    font-weight: 300;
}

.ceremony-time, .reception-time {
    font-size: 1.2rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.ceremony-venue, .reception-venue {
    margin-top: 1rem;
    opacity: 0.9;
}

/* RSVP Section */
.rsvp-title {
    font-size: 2.5rem;
    font-weight: 300;
    margin-bottom: 1rem;
    letter-spacing: 0.1em;
}

.rsvp-message {
    font-size: 1.1rem;
    font-weight: 300;
    margin-bottom: 2rem;
    opacity: 0.9;
}

/* Navigation Dots */
.scroll-navigation {
    position: fixed;
    right: 2rem;
    top: 50%;
    transform: translateY(-50%);
    z-index: 100;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.nav-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.4);
    border: 2px solid rgba(255, 255, 255, 0.6);
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-dot.active {
    background: rgba(255, 255, 255, 0.9);
    border-color: rgba(255, 255, 255, 1);
    transform: scale(1.2);
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .wedding-section {
        padding: 1rem;
    }
    
    .couple-details {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .wedding-couple-names {
        font-size: 1.5rem;
    }
    
    .ceremony-title, .reception-title {
        font-size: 1.5rem;
    }
    
    .rsvp-title {
        font-size: 2rem;
    }
    
    .scroll-navigation {
        right: 1rem;
    }
    
    .nav-dot {
        width: 10px;
        height: 10px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get elements
    const heroContent = document.getElementById('hero-content');
    const weddingDetailsContainer = document.getElementById('wedding-details-container');
    const openInvitationBtn = document.getElementById('open-invitation-btn');
    const backgroundImage = document.getElementById('background-image');
    const backgroundOverlay = document.getElementById('background-overlay');
    const scrollWrapper = document.querySelector('.details-scroll-wrapper');
    const navDots = document.querySelectorAll('.nav-dot');
    const sections = document.querySelectorAll('.wedding-section');

    let currentSectionIndex = 0;

    // Open invitation button click
    if (openInvitationBtn) {
        openInvitationBtn.addEventListener('click', function() {
            openWeddingDetails();
        });
    }

    function openWeddingDetails() {
        // Hide hero content
        gsap.to(heroContent, {
            opacity: 0,
            duration: 0.5,
            ease: "power2.out",
            onComplete: () => {
                heroContent.style.display = 'none';
                weddingDetailsContainer.style.display = 'block';
                
                // Show wedding details with animation
                gsap.fromTo(weddingDetailsContainer, 
                    { opacity: 0, y: 50 },
                    { opacity: 1, y: 0, duration: 0.8, ease: "power2.out" }
                );
                
                // Initialize scroll-based background changes
                initScrollTriggers();
            }
        });
    }

    function initScrollTriggers() {
        // Clear any existing ScrollTriggers
        ScrollTrigger.getAll().forEach(trigger => trigger.kill());

        sections.forEach((section, index) => {
            const bgImage = section.dataset.bgImage;
            
            ScrollTrigger.create({
                trigger: section,
                start: "top 80%",
                end: "bottom 20%",
                onEnter: () => {
                    changeBackground(bgImage, index);
                },
                onEnterBack: () => {
                    changeBackground(bgImage, index);
                }
            });
        });

        // Update navigation dots on scroll
        ScrollTrigger.create({
            trigger: scrollWrapper,
            start: "top top",
            end: "bottom bottom",
            onUpdate: (self) => {
                const scrollProgress = self.progress;
                const newIndex = Math.round(scrollProgress * (sections.length - 1));
                
                if (newIndex !== currentSectionIndex) {
                    currentSectionIndex = newIndex;
                    updateNavDots(currentSectionIndex);
                }
            }
        });
    }

    function changeBackground(newBgImage, sectionIndex) {
        if (backgroundImage.src !== newBgImage) {
            // Fade out current image
            gsap.to(backgroundImage, {
                opacity: 0,
                duration: 0.5,
                ease: "power2.out",
                onComplete: () => {
                    // Change the image
                    backgroundImage.src = newBgImage;
                    
                    // Fade in new image
                    gsap.to(backgroundImage, {
                        opacity: 1,
                        duration: 0.5,
                        ease: "power2.out"
                    });
                }
            });
        }

        // Update overlay based on section
        let overlayClass = 'absolute inset-0 bg-gradient-to-t from-black/70 to-black/50 transition-opacity duration-1000';
        
        switch(sectionIndex) {
            case 0: // Wedding details
                overlayClass = 'absolute inset-0 bg-gradient-to-t from-black/70 to-black/50 transition-opacity duration-1000';
                break;
            case 1: // Ceremony & Reception
                overlayClass = 'absolute inset-0 bg-gradient-to-t from-black/80 to-black/60 transition-opacity duration-1000';
                break;
            case 2: // RSVP
                overlayClass = 'absolute inset-0 bg-gradient-to-t from-black/60 to-black/40 transition-opacity duration-1000';
                break;
        }
        
        backgroundOverlay.className = overlayClass;
    }

    function updateNavDots(activeIndex) {
        navDots.forEach((dot, index) => {
            dot.classList.toggle('active', index === activeIndex);
        });
    }

    // Navigation dot clicks
    navDots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            scrollToSection(index);
        });
    });

    function scrollToSection(index) {
        const targetSection = sections[index];
        if (targetSection) {
            targetSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    }

    // Smooth scroll enhancement for mobile
    if ('ontouchstart' in window) {
        scrollWrapper.style.webkitOverflowScrolling = 'touch';
        scrollWrapper.style.scrollBehavior = 'smooth';
    }
});
</script>

<?php get_footer(); ?>