<?php get_header(); ?>

<?php 
$groom_name = get_theme_mod('groom_name', 'Dennis');
$bride_name = get_theme_mod('bride_name', 'Emilia');
?>

<!-- Hero Section -->
<section id="hero-section" class="relative min-h-screen flex items-start justify-center bg-cover bg-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/wedding-landscape.png');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto mt-64 md:mt-32">
        <div class="monogram-container mb-4 sm:mb-6 leading-none">
            <span class="monogram-combined">
                <span class="groom-initial"><?php echo strtolower(substr($groom_name, 0, 1)); ?></span><span class="bride-initial"><?php echo strtolower(substr($bride_name, 0, 1)); ?></span>
            </span>
        </div>
        
        <h1 class="couple-names text-base sm:text-lg md:text-xl tracking-[0.2em] sm:tracking-[0.3em] font-medium leading-tight">
            <?php echo strtoupper($groom_name . ' AND ' . $bride_name); ?>
        </h1>
    </div>
</section>

<!-- Invitation Section -->
<section id="invitation-section" class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-black/40"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-2xl mx-auto">
        <div class="monogram-container mb-6 sm:mb-8 leading-none">
            <span class="monogram-combined-small" style="font-family: Alex Brush, cursive; font-size: 2.5rem; line-height: 1; color: white; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
                <span class="groom-initial" style="display: inline-block;"><?php echo strtolower(substr($groom_name, 0, 1)); ?></span><span class="bride-initial" style="display: inline-block;"><?php echo strtolower(substr($bride_name, 0, 1)); ?></span>
            </span>
        </div>
        
        <h2 class="greeting-title text-sm md:text-2xl font-light mb-4 md:mb-6 tracking-wide">
            DEAR MR. JOHN AND MRS. JANE,
        </h2>
        <p class="invitation-message text-sm md:text-base leading-relaxed mb-6 md:mb-8">
            You are warmly invited to join us on our wedding day.<br>
            We apologise for any misspellings of names or titles.
        </p>
        
        <button id="invitation-btn" style="display: inline-block !important; visibility: visible !important; border: 1px solid white; background: transparent; color: white; padding: 12px 32px; font-size: 14px; font-weight: 300; letter-spacing: 0.1em; text-transform: uppercase; cursor: pointer; transition: all 0.3s ease; position: relative; z-index: 9999;">
            OPEN THE INVITATION
        </button>
        <p class="reveal-text text-sm mt-4 italic font-light">
            Or scroll to reveal
        </p>
    </div>
</section>
<!-- Wedding Details Section -->
<section id="wedding-details-section" class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-black/50"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-2xl mx-auto py-8">
        <!-- Wedding Title -->
        <div class="wedding-title-section mb-6 md:mb-8">
            <h2 class="wedding-title text-base md:text-xl font-light mb-3 md:mb-4 tracking-wider italic">
                The wedding of
            </h2>
            <h1 class="wedding-couple-names text-xl md:text-3xl font-medium tracking-wider leading-tight">
                <?php echo strtoupper($groom_name . ' AND ' . $bride_name); ?>
            </h1>
        </div>
        
        <!-- Wedding Date -->
        <div class="wedding-date-section mt-4 md:mt-8 mb-8 md:mb-12">
            <p class="wedding-date text-sm md:text-lg font-light tracking-wide">
                Saturday, 22 November 2025
            </p>
        </div>
        
        <!-- Bible Verse -->
        <div class="bible-verse-section mb-8 md:mb-12">
            <p class="bible-verse text-xs md:text-base font-light leading-relaxed max-w-lg mx-auto px-4">
                "Love knows no limit to its endurance, <br>
                no end to its trust, love still stands <br>
                when all else has fallen."<br>
                <span class="verse-reference mt-6 md:mt-12 text-xs tracking-wider block">1 Corinthians 13:7-8</span>
            </p>
        </div>
        
        <!-- Wedding Details -->
        <div class="wedding-info-section mb-8 md:mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-lg mx-auto">
                <div class="groom-info">
                    <h3 class="person-name text-base md:text-lg font-medium mb-2">Dennis Wijaya</h3>
                    <p class="person-details text-xs md:text-sm font-light">
                        First son of<br>
                        <b>Saleh Widjaja </b> and<br>
                        <b>Soesi Wijaya </b>
                    </p>
                </div>
                
                <div class="bride-info">
                    <h3 class="person-name text-base md:text-lg font-medium mb-2">Emilia Bewintara</h3>
                    <p class="person-details text-xs md:text-sm font-light">
                        Second daughter of<br>
                        <b>Budy Bewintara </b> and<br>
                        <b>Lindawati</b>
                    </p>
                </div>
            </div>
        </div>
        
        <!-- RSVP Call to Action -->
        <div class="details-rsvp-section">
            <p class="details-rsvp-text text-xs md:text-sm font-light tracking-wider mt-6 md:mt-12 mb-4">
                SCROLL TO CONTINUE
            </p>
            <div class="scroll-indicator">
                <div class="details-scroll-arrow opacity-70 animate-bounce"><i class="fas fa-angle-down"></i></div>
            </div>
        </div>
    </div>
</section>

<!-- Ceremony & Reception Section -->
<section id="ceremony-reception-section" class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-2xl mx-auto py-8">
        <p class="ceremony-intro text-xs md:text-sm font-light mb-2">We request the blessing of your presence as we are united in</p>
        <h2 class="ceremony-title text-lg md:text-xl tracking-widest mb-2 font-semibold">HOLY MATRIMONY</h2>
        <div class="ceremony-time text-sm md:text-base tracking-wider mb-4 font-medium">9 AM ONWARD</div>
        <div class="ceremony-location mb-6">
            <div class="ceremony-place font-semibold tracking-widest text-sm md:text-lg">GEREJA KATOLIK<br>SANTO LAURENSIUS</div>
            <div class="ceremony-address text-xs md:text-sm font-light italic mt-2 mb-3">Jl. Sutera Utama No. 2, Alam Sutera<br>Tangerang, Banten 15326</div>
            <a href="https://maps.app.goo.gl/wWV4HAQFGC6D9Xy76" target="_blank" class="ceremony-map-link text-xs underline tracking-wider">VIEW MAP</a>
        </div>
        <hr class="ceremony-divider my-6 md:my-8 border-white/30">
        <p class="reception-intro text-xs md:text-sm font-light mb-2">We request the pleasure of your company at our</p>
        <h2 class="reception-title text-lg md:text-xl font-light tracking-widest mb-2">EVENING RECEPTION</h2>
        <div class="reception-time text-sm md:text-base font-semibold tracking-wider mb-4">6 PM ONWARD</div>
        <div class="reception-location mb-6">
            <div class="reception-place font-semibold tracking-widest text-sm md:text-lg">JHL SOLITAIRE</div>
            <div class="reception-address text-xs md:text-sm font-light italic mt-2 mb-3">Jl. Gading Serpong Boulevard,<br>Blok S no. 5, Gading Serpong,<br>Tangerang, Banten 15810</div>
            <a href="https://maps.app.goo.gl/xPCwuyatC8ghgDd79" target="_blank" class="reception-map-link text-xs underline tracking-wider">VIEW MAP</a>
        </div>
        <div class="rsvp-section mt-6 md:mt-14">
            <p class="ceremony-rsvp-text text-xs md:text-sm font-light tracking-wider mb-4">SCROLL TO CONTINUE</p>
            <div class="scroll-indicator">
                <div class="ceremony-scroll-arrow opacity-70 animate-bounce"><i class="fas fa-angle-down"></i></div>
            </div>
        </div>
    </div>
</section>

<!-- Love Story Section -->
<section id="love-story-section" class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-2xl mx-auto py-8">
        <!-- Love Story Title -->
        <h2 class="love-story-title text-lg md:text-2xl font-bold mb-6 md:mb-8 tracking-wider italic">
            The Love Story
        </h2>
        
        <!-- Video Container -->
        <div class="love-story-video-container mb-6 md:mb-8 relative">
            <div class="video-placeholder bg-gray-800 rounded-lg overflow-hidden relative aspect-video max-w-xs md:max-w-md mx-auto">
                <!-- Placeholder for video -->
                <div class="video-overlay absolute inset-0 bg-black/50 flex items-center justify-center">
                    <div class="play-button w-12 h-12 md:w-16 md:h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                        <div class="play-icon w-0 h-0 border-l-[16px] md:border-l-[20px] border-l-white border-t-[10px] md:border-t-[12px] border-t-transparent border-b-[10px] md:border-b-[12px] border-b-transparent ml-1"></div>
                    </div>
                </div>
                <!-- Placeholder image -->
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg" alt="Love Story" class="w-full h-full object-cover">
            </div>
        </div>
        
        <!-- Love Quote -->
        <div class="love-quote-section mb-6 md:mb-8">
            <p class="love-quote text-xs md:text-base font-light italic leading-relaxed max-w-sm mx-auto px-4">
                "..Their paths were always aligned,<br>
                <b>yet they never met,</b><br>
                as if the stars were never quite set."
            </p>
        </div>
        
        <!-- Continue Call to Action -->
        <div class="love-story-rsvp-section">
            <p class="love-story-rsvp-text text-xs md:text-sm font-light tracking-wider mb-4">
                SCROLL TO CONTINUE
            </p>
            <div class="scroll-indicator">
                <div class="love-story-scroll-arrow opacity-70 animate-bounce"><i class="fas fa-angle-down"></i></div>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Love Story Section -->
<section id="detailed-love-story-section" class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-2xl mx-auto py-8">
        <!-- Love Story Title -->
        <h2 class="detailed-love-story-title text-xl md:text-2xl font-bold mb-6 tracking-wider italic">
            The Love Story
        </h2>
        
        <!-- Love Story Narrative -->
        <div class="love-story-narrative text-sm md:text-base leading-relaxed space-y-3 max-w-lg mx-auto">
            <p class="narrative-opening text-center mb-4">
                <strong>Their paths quietly overlapped.</strong><br>
                Always in the same places,<br>
                <strong>just never at the same time.</strong>
            </p>
            
            <p class="narrative-college">
                During their college years in <strong>2018</strong>,<br>
                Emilia studied in Kelapa Gading but<br>
                had internships in Malaysia at UCSI University.<br>
                Dennis was studying nearby at SEGI College in Subang Jaya.
            </p>
            
            <p class="narrative-malaysia">
                They visited the same places in Malaysia.<br>
                So close, so often, <strong>yet unaware</strong><br>
                that someone familiar was always there.
            </p>
            
            <p class="narrative-return">
                When Dennis returned home to Kelapa Gading,<br>
                Emilia was there too...<br>
                <strong>Their paths aligned, yet never met,</strong><br>
                as if the stars were not quite set.
            </p>
            
            <p class="narrative-2020">
                Then <strong>2020 came</strong>, two circles met in Discord's name.<br>
                A voice, a laugh. Something felt <strong>true</strong>, at least to him.<br>
                Before she knew.
            </p>
            
            <p class="narrative-2022">
                <strong>In 2022</strong>, they met in person for the first time.<br>
                He liked her then. Clear and sincere.<br>
                But her heart wasn't ready, not yet near.
            </p>
        </div>
        
        <!-- Continue Call to Action -->
        <div class="detailed-love-story-rsvp-section mt-8">
            <p class="detailed-love-story-rsvp-text text-sm font-light tracking-wider mb-4">
                SCROLL TO CONTINUE
            </p>
            <div class="scroll-indicator">
                <div class="detailed-love-story-scroll-arrow opacity-70 animate-bounce"><i class="fas fa-angle-down"></i></div>
            </div>
        </div>
    </div>
</section>

<!-- Final Love Story Section -->
<section id="final-love-story-section" class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-2xl mx-auto py-8">                    
        <!-- Love Story Title -->
        <h2 class="final-love-story-title text-xl md:text-2xl font-bold mb-6 tracking-wider italic">
            The Love Story
        </h2>
        
        <!-- Final Love Story Narrative -->
        <div class="final-love-story-narrative text-sm md:text-base leading-relaxed space-y-3 max-w-lg mx-auto">
            <p class="narrative-time-kind text-center mb-4">
                Still, time was kind and gently played<br>
                through work and life, the bond was laid.
            </p>
            
            <p class="narrative-2023">
                <strong>By 2023</strong>, she saw it too. The steady light she once outgrew.
            </p>
            
            <p class="narrative-august">
                On August, he took the leap, for a love no longer fast asleep.<br>
                And this time, when he reached for her,<br>
                her heart was open, soft, and sure.
            </p>
            
            <!-- Animated vertical line before proposal -->
            <div class="proposal-divider flex justify-center my-6">
                <div class="vertical-line w-px bg-white/50 h-16"></div>
            </div>
            
            <p class="narrative-proposal">
                <strong>On April 28th, 2024</strong>,<br>
                under a sky full of hope and love,<br>
                with steady hands and love nearby.<br>
                He asked again, his voice sincere.
            </p>
            
            <p class="narrative-forever">
                This time, for forever, through every year.
            </p>
            
            <p class="narrative-yes">
                And in that golden, honest light,<br>
                <strong>she said yes</strong>, to what felt right.
            </p>
            
            <p class="narrative-question text-center font-semibold">
                We said yes, will you say yes to us?
            </p>
        </div>
        
        <!-- Continue Call to Action -->
        <div class="final-love-story-rsvp-section mt-8">
            <p class="final-love-story-rsvp-text text-sm font-light tracking-wider mb-4">
                SCROLL TO CONTINUE
            </p>
            <div class="scroll-indicator">
                <div class="final-love-story-scroll-arrow opacity-70 animate-bounce"><i class="fas fa-angle-down"></i></div>
            </div>
        </div>
    </div>
</section>

<!-- Image Slider Section -->
<section id="image-slider-section" class="relative min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');">
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 to-black/70"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-2xl mx-auto py-8">                    
        <!-- Romantic Quote -->
        <div class="slider-quote-section mb-8">
            <p class="slider-quote-line1 text-sm md:text-base font-light italic leading-relaxed text-center">
                "Our paths always knew,
            </p>
            <p class="slider-quote-line2 text-sm md:text-base font-light italic leading-relaxed text-center">
                though we walked unaware.
            </p>
            <p class="slider-quote-line3 text-sm md:text-base font-light italic leading-relaxed text-center">
                A promise unspoken
            </p>
            <p class="slider-quote-line4 text-sm md:text-base font-light italic leading-relaxed text-center mb-6">
                that led us here.
            </p>
            <p class="slider-quote-line5 text-sm md:text-base font-light italic leading-relaxed text-center">
                We crossed many lifetimes
            </p>
            <p class="slider-quote-line6 text-sm md:text-base font-light italic leading-relaxed text-center">
                to stand side by side.
            </p>
            <p class="slider-quote-line7 text-sm md:text-base font-light italic leading-relaxed text-center">
                <strong>Wherever you are,</strong>
            </p>
            <p class="slider-quote-line8 text-sm md:text-base font-light italic leading-relaxed text-center">
                <strong>my heart will reside."</strong>
            </p>
        </div>
        
        <!-- Image Container -->
        <div class="slider-image-container mb-8 relative">
            <div class="slider-image-placeholder bg-gray-800 rounded-lg overflow-hidden relative aspect-square max-w-xs mx-auto">
                <!-- Placeholder for slider image -->
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder-hands.jpg" alt="Love Story Image" class="slider-main-image w-full h-full object-cover">
                
                <!-- Navigation arrows (for future slider functionality) -->
                <div class="slider-nav absolute inset-y-0 left-0 flex items-center">
                    <button class="slider-prev bg-white/20 hover:bg-white/30 text-white p-2 ml-2 rounded-full transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                </div>
                <div class="slider-nav absolute inset-y-0 right-0 flex items-center">
                    <button class="slider-next bg-white/20 hover:bg-white/30 text-white p-2 mr-2 rounded-full transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Bottom Quote -->
        <div class="slider-bottom-quote mb-8">
            <p class="slider-bottom-text text-sm md:text-base font-light italic leading-relaxed text-center">
                We said yes, <strong>will you say yes to us?</strong>
            </p>
        </div>
        
        <!-- Final RSVP Call to Action -->
        <div class="slider-rsvp-section">
            <p class="slider-rsvp-text text-sm font-light tracking-wider mb-4">
                SCROLL TO RSVP
            </p>
            <div class="scroll-indicator">
                <div class="slider-scroll-arrow opacity-70 animate-bounce"><i class="fas fa-angle-down"></i></div>
            </div>
        </div>
    </div>
</section>

<!-- Backup GSAP loading in case WordPress doesn't load it properly -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<script>
// Scroll-based Section Animations
document.addEventListener('DOMContentLoaded', function() {
    // Check if GSAP is loaded
    if (typeof gsap === 'undefined') {
        console.error('GSAP is not loaded!');
        return;
    }
    
    gsap.registerPlugin(ScrollTrigger);
    
    // Hero Section Animation
    gsap.timeline()
        .from('#hero-section .monogram-combined', {
            duration: 1.5,
            y: -100,
            opacity: 0,
            ease: "power2.out",
            delay: 0.5
        })
        .from('#hero-section .couple-names', {
            duration: 1,
            opacity: 0,
            y: 30,
            ease: "power2.out"
        }, "+=0.3");
    
    // Monogram Transition from Hero to Invitation
    gsap.timeline({
        scrollTrigger: {
            trigger: "#invitation-section",
            start: "top 70%",
            end: "top 30%",
            scrub: 1,
            onEnter: () => {
                // Hide invitation monogram during transition
                gsap.set('#invitation-section .monogram-combined-small', { opacity: 0 });
            },
            onLeave: () => {
                // Show invitation monogram when transitioning further
                gsap.set('#invitation-section .monogram-combined-small', { opacity: 1 });
            },
            onEnterBack: () => {
                // Hide invitation monogram when scrolling back
                gsap.set('#invitation-section .monogram-combined-small', { opacity: 0 });
            },
            onLeaveBack: () => {
                // Reset hero monogram when scrolling back to hero
                gsap.set('#hero-section .monogram-combined', { scale: 1, y: 0 });
            }
        }
    })
    .to('#hero-section .monogram-combined', {
        scale: 0.6,
        y: -150,
        duration: 1,
        ease: "power2.out"
    })
    .to('#hero-section .couple-names', {
        opacity: 0,
        y: -50,
        duration: 0.8,
        ease: "power2.out"
    }, 0);

    // Invitation Section Content Animation (with proper opacity animations)
    gsap.timeline({
        scrollTrigger: {
            trigger: "#invitation-section",
            start: "top 70%",
            end: "bottom 20%",
            toggleActions: "play none none reverse"
        }
    })
    .from('#invitation-section .monogram-combined-small', {
        duration: 1.8,
        y: -200,
        opacity: 0,
        scale: 1.2,
        ease: "back.out(1.7)"
    })
    .from('#invitation-section .greeting-title', {
        duration: 1,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.4")
    .from('#invitation-section .invitation-message', {
        duration: 1,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.2")
    .from('#invitation-section #invitation-btn', {
        duration: 0.8,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.3")
    .from('#invitation-section .reveal-text', {
        duration: 0.8,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.2");
    
    // Wedding Details Section Animation
    gsap.timeline({
        scrollTrigger: {
            trigger: "#wedding-details-section",
            start: "top 80%",
            end: "bottom 20%",
            toggleActions: "play none none reverse"
        }
    })
    .from('#wedding-details-section .wedding-title', {
        duration: 1,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    })
    .from('#wedding-details-section .wedding-couple-names', {
        duration: 1,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.1")
    .from('#wedding-details-section .wedding-date', {
        duration: 0.8,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.2")
    .from('#wedding-details-section .bible-verse', {
        duration: 1,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.3")
    .from('#wedding-details-section .groom-info', {
        duration: 0.8,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.2")
    .from('#wedding-details-section .bride-info', {
        duration: 0.8,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.1")
    .from('#wedding-details-section .details-rsvp-text', {
        duration: 0.8,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.3")
    .from('#wedding-details-section .details-scroll-arrow', {
        duration: 0.8,
        opacity: 0,
        y: 30,
        ease: "power2.out"
    }, "+=0.1");
    
    // Ceremony & Reception Section Animation
    gsap.timeline({
        scrollTrigger: {
            trigger: "#ceremony-reception-section",
            start: "top 80%",
            end: "bottom 20%",
            toggleActions: "play none none reverse"
        }
    })
    .from('#ceremony-reception-section .ceremony-intro', { duration: 0.7, opacity: 0, y: 30, ease: "power2.out" })
    .from('#ceremony-reception-section .ceremony-title', { duration: 0.7, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .ceremony-time', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .ceremony-location', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .ceremony-place', { duration: 0.5, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .ceremony-address', { duration: 0.5, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .ceremony-map-link', { duration: 0.5, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .ceremony-divider', { duration: 0.8, opacity: 0, scaleX: 0, ease: "power2.out" }, "+=0.2")
    .from('#ceremony-reception-section .reception-intro', { duration: 0.7, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .reception-title', { duration: 0.7, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .reception-time', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .reception-location', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .reception-place', { duration: 0.5, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .reception-address', { duration: 0.5, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .reception-map-link', { duration: 0.5, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1")
    .from('#ceremony-reception-section .ceremony-rsvp-text', { duration: 0.7, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#ceremony-reception-section .ceremony-scroll-arrow', { duration: 0.7, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1");
    
    // Love Story Section Animation
    gsap.timeline({
        scrollTrigger: {
            trigger: "#love-story-section",
            start: "top 80%",
            end: "bottom 20%",
            toggleActions: "play none none reverse"
        }
    })
    .from('#love-story-section .love-story-title', { duration: 1, opacity: 0, y: 30, ease: "power2.out" })
    .from('#love-story-section .video-placeholder', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#love-story-section .love-quote', { duration: 1, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#love-story-section .love-story-rsvp-text', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#love-story-section .love-story-scroll-arrow', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1");
    
    // Detailed Love Story Section Animation
    gsap.timeline({
        scrollTrigger: {
            trigger: "#detailed-love-story-section",
            start: "top 80%",
            end: "bottom 20%",
            toggleActions: "play none none reverse"
        }
    })
    .from('#detailed-love-story-section .detailed-love-story-title', { duration: 1, opacity: 0, y: 30, ease: "power2.out" })
    .from('#detailed-love-story-section .narrative-opening', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#detailed-love-story-section .narrative-college', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#detailed-love-story-section .narrative-malaysia', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#detailed-love-story-section .narrative-return', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#detailed-love-story-section .narrative-2020', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#detailed-love-story-section .narrative-2022', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#detailed-love-story-section .detailed-love-story-rsvp-text', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#detailed-love-story-section .detailed-love-story-scroll-arrow', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1");
    
    // Final Love Story Section Animation
    gsap.timeline({
        scrollTrigger: {
            trigger: "#final-love-story-section",
            start: "top 80%",
            end: "bottom 20%",
            toggleActions: "play none none reverse"
        }
    })
    .from('#final-love-story-section .final-love-story-title', { duration: 1, opacity: 0, y: 30, ease: "power2.out" })
    .from('#final-love-story-section .narrative-time-kind', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#final-love-story-section .narrative-2023', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#final-love-story-section .narrative-august', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#final-love-story-section .vertical-line', { duration: 1, height: 0, opacity: 0, ease: "power2.out" }, "+=0.3")
    .from('#final-love-story-section .narrative-proposal', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.2")
    .from('#final-love-story-section .narrative-forever', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#final-love-story-section .narrative-yes', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#final-love-story-section .narrative-question', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.4")
    .from('#final-love-story-section .final-love-story-rsvp-text', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#final-love-story-section .final-love-story-scroll-arrow', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1");
    
    // Image Slider Section Animation
    gsap.timeline({
        scrollTrigger: {
            trigger: "#image-slider-section",
            start: "top 80%",
            end: "bottom 20%",
            toggleActions: "play none none reverse"
        }
    })
    .from('#image-slider-section .slider-quote-line1', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" })
    .from('#image-slider-section .slider-quote-line2', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.2")
    .from('#image-slider-section .slider-quote-line3', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.2")
    .from('#image-slider-section .slider-quote-line4', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.2")
    .from('#image-slider-section .slider-quote-line5', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#image-slider-section .slider-quote-line6', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.2")
    .from('#image-slider-section .slider-quote-line7', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.2")
    .from('#image-slider-section .slider-quote-line8', { duration: 0.6, opacity: 0, y: 30, ease: "power2.out" }, "+=0.2")
    .from('#image-slider-section .slider-image-placeholder', { duration: 1, opacity: 0, scale: 0.9, ease: "power2.out" }, "+=0.4")
    .from('#image-slider-section .slider-bottom-text', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#image-slider-section .slider-rsvp-text', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.3")
    .from('#image-slider-section .slider-scroll-arrow', { duration: 0.8, opacity: 0, y: 30, ease: "power2.out" }, "+=0.1");
    
    // Button functionality
    document.querySelector('#invitation-btn')?.addEventListener('click', function() {
        document.querySelector('#wedding-details-section')?.scrollIntoView({ 
            behavior: 'smooth' 
        });
    });
    
    // Ensure button is visible (backup)
    setTimeout(() => {
        const btn = document.querySelector('#invitation-btn');
        if (btn) {
            btn.style.display = 'inline-block';
            btn.style.visibility = 'visible';
        }
    }, 100);
});
</script>

<?php get_footer(); ?>
