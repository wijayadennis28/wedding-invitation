<?php get_header(); ?>

<!-- Hero Section -->
<section class="hero-section min-h-screen flex items-start justify-center relative overflow-hidden">
    
    <div class="w-full max-w-4xl mx-auto px-4 text-center pt-32 md:pt-40 lg:pt-48">
        <!-- Elegant Script Monogram -->
        <div class="monogram-container mb-4 sm:mb-6 leading-none">
            <?php 
            $groom_name = get_theme_mod('groom_name', 'Dennis');
            $bride_name = get_theme_mod('bride_name', 'Emilia');
            ?>
            <!-- Combined monogram -->
            <span class="monogram-combined">
                <span class="groom-initial"><?php echo strtolower(substr($groom_name, 0, 1)); ?></span><span class="bride-initial"><?php echo strtolower(substr($bride_name, 0, 1)); ?></span>
            </span>
        </div>
        
        <!-- Couple Names -->
        <h1 class="couple-names text-base sm:text-lg md:text-xl tracking-[0.2em] sm:tracking-[0.3em] font-medium leading-tight">
            <?php echo strtoupper($groom_name . ' AND ' . $bride_name); ?>
        </h1>
    </div>
    
</section>

<!-- Invitation Reveal Section -->
<section class="invitation-section min-h-screen flex items-center justify-center relative overflow-hidden">
    <!-- Background Image as HTML element -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-black/40"></div>
    </div>
    
    <div class="w-full max-w-2xl mx-auto px-4 text-center relative z-10">
        <!-- Small Monogram that fades in -->
        <div class="small-monogram-container mb-12">
            <span class="small-monogram-combined">
                <span class="small-groom-initial"><?php echo strtolower(substr($groom_name, 0, 1)); ?></span><span class="small-bride-initial"><?php echo strtolower(substr($bride_name, 0, 1)); ?></span>
            </span>
        </div>
        
        <!-- Greeting Text -->
        <div class="invitation-text mb-12">
            <h2 class="greeting-title text-2xl md:text-3xl font-light text-white mb-6 tracking-wide">
                DEAR MR. JOHN AND MRS. JANE,
            </h2>
            <p class="invitation-message text-base md:text-lg text-white leading-relaxed font-light italic">
                You are cordially invited to join us on our wedding day.<br>
                We apologize for any misspellings of names or titles.
            </p>
        </div>
        
        <!-- Open Invitation Button -->
        <div class="invitation-button-container">
            <button class="open-invitation-btn bg-transparent border border-white text-white px-8 py-3 text-sm font-light tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                OPEN THE INVITATION
            </button>
            <p class="reveal-text text-white text-sm mt-4 italic font-light">
                Or slide to reveal
            </p>
        </div>
    </div>
    
</section>

<script>
// Advanced GSAP Animation Sequence
document.addEventListener('DOMContentLoaded', function() {
    // Create a timeline for sequenced animations
    const tl = gsap.timeline();
    
    // Check if mobile device
    const isMobile = window.innerWidth <= 768;
    
    // Initially hide elements
    gsap.set('.monogram-combined', { y: -window.innerHeight, opacity: 0 });
    gsap.set('.couple-names', { opacity: 0, y: 30 });
    
    // Hide second section elements initially
    gsap.set('.small-monogram-combined', { y: -window.innerHeight, opacity: 0 });
    gsap.set('.greeting-title', { opacity: 0, y: 30 });
    gsap.set('.invitation-message', { opacity: 0, y: 30 });
    gsap.set('.open-invitation-btn', { opacity: 0, y: 30 });
    gsap.set('.reveal-text', { opacity: 0, y: 30 });
    
    // First section animation sequence
    tl.to('.monogram-combined', {
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
    }, "+=0.3"); // Start 0.3s after previous animation ends
    
    // Scroll-triggered animations
    gsap.registerPlugin(ScrollTrigger);
    
    // First section ScrollTrigger - handle enter/leave properly
    ScrollTrigger.create({
        trigger: '.invitation-section',
        start: 'top 95%',
        end: 'top 40%',
        onEnter: () => {
            // Hide first section elements when entering second section
            gsap.to('.monogram-combined', {
                opacity: 0,
                scale: 0.6,
                y: -100,
                duration: 0.5,
                ease: "power2.out"
            });
            gsap.to('.couple-names', {
                opacity: 0,
                y: -100,
                duration: 0.5,
                ease: "power2.out"
            });
        },
        onLeave: () => {
            // Keep first section elements hidden when leaving second section
        },
        onEnterBack: () => {
            // Keep first section elements hidden when scrolling back up to second section
        },
        onLeaveBack: () => {
            // Show first section elements when scrolling back up past second section
            gsap.to('.monogram-combined', {
                opacity: 1,
                scale: 1,
                y: 0,
                duration: 0.5,
                ease: "power2.out"
            });
            gsap.to('.couple-names', {
                opacity: 1,
                y: 0,
                duration: 0.5,
                ease: "power2.out"
            });
        }
    });
    
    // Second section sequential animations with proper enter/leave handling
    let secondSectionTl = gsap.timeline({ paused: true });
    
    // Build the second section timeline
    secondSectionTl
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
    
    // Second section ScrollTrigger
    ScrollTrigger.create({
        trigger: '.invitation-section',
        start: 'top 80%',
        end: 'bottom 20%',
        onEnter: () => {
            // Play second section animation when entering
            secondSectionTl.restart();
        },
        onLeave: () => {
            // Reset second section elements when leaving
            gsap.set('.small-monogram-combined', { y: -window.innerHeight, opacity: 0 });
            gsap.set('.greeting-title', { opacity: 0, y: 30 });
            gsap.set('.invitation-message', { opacity: 0, y: 30 });
            gsap.set('.open-invitation-btn', { opacity: 0, y: 30 });
            gsap.set('.reveal-text', { opacity: 0, y: 30 });
        },
        onEnterBack: () => {
            // Play second section animation when scrolling back up
            secondSectionTl.restart();
        },
        onLeaveBack: () => {
            // Reset second section elements when scrolling back past top
            gsap.set('.small-monogram-combined', { y: -window.innerHeight, opacity: 0 });
            gsap.set('.greeting-title', { opacity: 0, y: 30 });
            gsap.set('.invitation-message', { opacity: 0, y: 30 });
            gsap.set('.open-invitation-btn', { opacity: 0, y: 30 });
            gsap.set('.reveal-text', { opacity: 0, y: 30 });
        }
    });
    
    // Button click animation
    document.querySelector('.open-invitation-btn').addEventListener('click', function() {
        gsap.to(this, {
            scale: 0.95,
            duration: 0.1,
            yoyo: true,
            repeat: 1,
            ease: "power2.inOut"
        });
    });
});
</script>

<?php get_footer(); ?>
