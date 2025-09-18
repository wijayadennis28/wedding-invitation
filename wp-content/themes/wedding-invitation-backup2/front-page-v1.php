<?php get_header(); ?>

<!-- Single Dynamic Section -->
<section class="dynamic-section relative min-h-screen">
    <!-- Dynamic Background Image -->
    <div class="background-container fixed inset-0 z-0">
        <img id="background-image" src="" alt="" class="w-full h-full object-cover transition-opacity duration-1000">
        <div id="background-overlay" class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent transition-opacity duration-1000"></div>
    </div>
    
    <div class="content-wrapper fixed inset-0 flex items-center justify-center z-10">
        <div class="w-full mx-auto px-4 text-center relative" style="position: fixed; top: 20%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: none;">
            <?php 
            $groom_name = get_theme_mod('groom_name', 'Dennis');
            $bride_name = get_theme_mod('bride_name', 'Emilia');
            ?>
            
            <!-- Hero Content -->
            <div id="hero-content" class="content-container max-w-4xl mx-auto" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%;">
                <div class="monogram-container mb-4 sm:mb-6 leading-none">
                    <span class="monogram-combined">
                        <span class="groom-initial"><?php echo strtolower(substr($groom_name, 0, 1)); ?></span><span class="bride-initial"><?php echo strtolower(substr($bride_name, 0, 1)); ?></span>
                    </span>
                </div>
                
                <h1 class="couple-names text-base sm:text-lg md:text-xl tracking-[0.2em] sm:tracking-[0.3em] font-medium leading-tight text-white">
                    <?php echo strtoupper($groom_name . ' AND ' . $bride_name); ?>
                </h1>
            </div>
            
            <!-- Invitation Opening Content (Initially Hidden) -->
            <div id="invitation-content" class="content-container max-w-2xl mx-auto hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full z-20 max-w-[90vw] px-4">
                <div class="invitation-text text-center">
                    <h2 class="greeting-title text-sm md:text-2xl font-light text-white mb-4 md:mb-6 tracking-wide">
                        DEAR MR. JOHN AND MRS. JANE,
                    </h2>
                    <p class="invitation-message text-sm md:text-base text-white leading-relaxed mb-6 md:mb-8">
                        You are warmly invited to join us on our wedding day.<br>
                        We apologise for any misspellings of names or titles.
                    </p>
                </div>
                
                <div class="invitation-button-container">
                    <button class="open-invitation-btn bg-transparent border border-white text-white px-8 py-3 text-sm font-light tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                        OPEN THE INVITATION
                    </button>
                    <p class="reveal-text text-white text-sm mt-4 italic font-light">
                        Or slide to reveal
                    </p>
                </div>
            </div>
            
            <!-- Wedding Details Content (Initially Hidden) -->
            <div id="wedding-details-content" class="content-container max-w-2xl mx-auto hidden fixed top-0 left-1/2 transform -translate-x-1/2 w-full z-30 overflow-y-auto h-screen px-4 scrollbar-hide">
                <div class="wedding-details-inner flex flex-col py-8 md:py-12 text-center min-h-screen md:justify-center">
                    <!-- Wedding Title -->
                    <div class="wedding-title-section mb-6 md:mb-8 text-center">
                        <h2 class="wedding-title text-base md:text-xl font-light text-white mb-3 md:mb-4 tracking-wider italic">
                            The wedding of
                        </h2>
                        <h1 class="wedding-couple-names text-xl md:text-3xl font-medium text-white tracking-wider leading-tight">
                            <?php echo strtoupper($groom_name . ' AND ' . $bride_name); ?>
                        </h1>
                    </div>
                    
                    <!-- Wedding Date -->
                    <div class="wedding-date-section mt-4 md:mt-8 mb-8 md:mb-12 text-center">
                        <p class="wedding-date text-sm md:text-lg text-white font-light tracking-wide">
                            Saturday, 22 November 2025
                        </p>
                    </div>
                    
                    <!-- Bible Verse -->
                    <div class="bible-verse-section mb-8 md:mb-12 text-center">
                        <p class="bible-verse text-xs md:text-base text-white font-light leading-relaxed max-w-lg mx-auto px-4">
                            "Love knows no limit to its endurance, <br>
                            no end to its trust, love still stands <br>
                            when all else has fallen."<br>
                            <span class="verse-reference mt-6 md:mt-12 text-xs tracking-wider block">1 Corinthians 13:7-8</span>
                        </p>
                    </div>
                    
                    <!-- Wedding Details -->
                    <div class="wedding-info-section mb-8 md:mb-12 text-center">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 max-w-lg mx-auto">
                            <div class="groom-info">
                                <h3 class="person-name text-base md:text-lg text-white font-medium mb-2">Dennis Wijaya</h3>
                                <p class="person-details text-xs md:text-sm text-white font-light">
                                    First son of<br>
                                    <b>Saleh Widjaja </b> and<br>
                                    <b>Soesi Wijaya </b>
                                </p>
                            </div>
                            
                            <div class="bride-info">
                                <h3 class="person-name text-base md:text-lg text-white font-medium mb-2">Emilia Bewintara</h3>
                                <p class="person-details text-xs md:text-sm text-white font-light">
                                    Second daughter of<br>
                                    <b>Budy Bewintara </b> and<br>
                                    <b>Lindawati</b>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- RSVP Call to Action for Wedding Details -->
                    <div class="details-rsvp-section text-center">
                        <p class="details-rsvp-text text-xs md:text-sm text-white font-light tracking-wider mt-6 md:mt-12 mb-4">
                            SCROLL TO RSVP
                        </p>
                        <div class="scroll-indicator">
                            <div class="details-scroll-arrow text-white opacity-70"><i class="fas fa-angle-down"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ceremony & Reception Content (Initially Hidden) -->
            <div id="ceremony-reception-content" class="content-container max-w-2xl mx-auto hidden fixed top-0 left-1/2 transform -translate-x-1/2 w-full z-40 overflow-y-auto h-screen px-4 scrollbar-hide">
                <div class="ceremony-reception-inner flex flex-col py-8 md:py-12 text-center min-h-screen md:justify-center">
                    <p class="ceremony-intro text-xs md:text-sm text-white font-light mb-2">We request the blessing of your presence as we are united in</p>
                    <h2 class="ceremony-title text-lg md:text-xl tracking-widest mb-2 text-white font-semibold">HOLY MATRIMONY</h2>
                    <div class="ceremony-time text-sm md:text-base tracking-wider mb-4 text-white font-medium">9 AM ONWARD</div>
                    <div class="ceremony-location mb-6 text-white">
                        <div class="ceremony-place font-semibold tracking-widest text-sm md:text-lg">GEREJA KATOLIK<br>SANTO LAURENSIUS</div>
                        <div class="ceremony-address text-xs md:text-sm font-light italic mt-2 mb-3">Jl. Sutera Utama No. 2, Alam Sutera<br>Tangerang, Banten 15326</div>
                        <a href="https://maps.app.goo.gl/wWV4HAQFGC6D9Xy76" target="_blank" class="ceremony-map-link text-xs underline text-white tracking-wider">VIEW MAP</a>
                    </div>
                    <hr class="ceremony-divider my-6 md:my-8 border-white/30">
                    <p class="reception-intro text-xs md:text-sm text-white font-light mb-2">We request the pleasure of your company at our</p>
                    <h2 class="reception-title text-lg md:text-xl font-light text-white tracking-widest mb-2">EVENING RECEPTION</h2>
                    <div class="reception-time text-sm md:text-base text-white font-semibold tracking-wider mb-4">6 PM ONWARD</div>
                    <div class="reception-location text-white mb-6">
                        <div class="reception-place font-semibold tracking-widest text-sm md:text-lg">JHL SOLITAIRE</div>
                        <div class="reception-address text-xs md:text-sm font-light italic mt-2 mb-3">Jl. Gading Serpong Boulevard,<br>Blok S no. 5, Gading Serpong,<br>Tangerang, Banten 15810</div>
                        <a href="https://maps.app.goo.gl/xPCwuyatC8ghgDd79" target="_blank" class="reception-map-link text-xs underline text-white tracking-wider">VIEW MAP</a>
                    </div>
                    <div class="rsvp-section text-center mt-6 md:mt-14">
                        <p class="ceremony-rsvp-text text-xs md:text-sm text-white font-light tracking-wider mb-4">SCROLL TO RSVP</p>
                        <div class="scroll-indicator">
                            <div class="ceremony-scroll-arrow text-white opacity-70"><i class="fas fa-angle-down"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Love Story Content (Initially Hidden) -->
            <div id="love-story-content" class="content-container max-w-2xl mx-auto hidden fixed top-0 left-1/2 transform -translate-x-1/2 w-full z-50 overflow-y-auto h-screen px-4 scrollbar-hide">
                <div class="love-story-inner flex flex-col py-8 md:py-12 text-center min-h-screen md:justify-center">
                    <!-- Love Story Title -->
                    <h2 class="love-story-title text-lg md:text-2xl font-bold mb-6 md:mb-8 tracking-wider italic text-white">
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
                        <p class="love-quote text-xs md:text-base text-white font-light italic leading-relaxed max-w-sm mx-auto px-4">
                            "..Their paths were always aligned,<br>
                            <b>yet they never met,</b><br>
                            as if the stars were never quite set."
                        </p>
                    </div>
                    
                    <!-- Final RSVP Call to Action -->
                    <div class="love-story-rsvp-section text-center">
                        <p class="love-story-rsvp-text text-xs md:text-sm text-white font-light tracking-wider mb-4">
                            SCROLL TO RSVP
                        </p>
                        <div class="scroll-indicator">
                            <div class="love-story-scroll-arrow text-white opacity-70"><i class="fas fa-angle-down"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Love Story Content (Initially Hidden) -->
            <div id="detailed-love-story-content" class="content-container max-w-2xl mx-auto hidden fixed top-0 left-1/2 transform -translate-x-1/2 w-full z-50 overflow-y-auto h-screen px-4 scrollbar-hide">
                <div class="detailed-love-story-inner flex flex-col py-6 md:py-8 text-center">
                    <!-- Love Story Title -->
                    <h2 class="detailed-love-story-title text-xl md:text-2xl font-bold mb-6 tracking-wider italic">
                        The Love Story
                    </h2>
                    
                    <!-- Love Story Narrative -->
                    <div class="love-story-narrative text-white text-sm md:text-base leading-relaxed space-y-3 max-w-lg mx-auto">
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
                    
                    <!-- Final RSVP Call to Action -->
                    <div class="detailed-love-story-rsvp-section text-center mt-8">
                        <p class="detailed-love-story-rsvp-text text-sm text-white font-light tracking-wider mb-4">
                            SCROLL TO RSVP
                        </p>
                        <div class="scroll-indicator">
                            <div class="detailed-love-story-scroll-arrow text-white opacity-70"><i class="fas fa-angle-down"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Final Love Story Section (Initially Hidden) -->
            <div id="final-love-story-content" class="content-container max-w-2xl mx-auto hidden fixed top-0 left-1/2 transform -translate-x-1/2 w-full z-50 overflow-y-auto h-screen px-4 scrollbar-hide">
                <div class="final-love-story-inner flex flex-col py-6 md:py-8 text-center">                    
                    <!-- Love Story Title -->
                    <h2 class="final-love-story-title text-xl md:text-2xl font-bold mb-6 tracking-wider italic">
                        The Love Story
                    </h2>
                    
                    <!-- Final Love Story Narrative -->
                    <div class="final-love-story-narrative text-white text-sm md:text-base leading-relaxed space-y-3 max-w-lg mx-auto">
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
                    
                    <!-- Final RSVP Call to Action -->
                    <div class="final-love-story-rsvp-section text-center mt-8">
                        <p class="final-love-story-rsvp-text text-sm text-white font-light tracking-wider mb-4">
                            SCROLL TO RSVP
                        </p>
                        <div class="scroll-indicator">
                            <div class="final-love-story-scroll-arrow text-white opacity-70"><i class="fas fa-angle-down"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Image Slider Section (Initially Hidden) -->
            <div id="image-slider-content" class="content-container max-w-2xl mx-auto hidden fixed top-0 left-1/2 transform -translate-x-1/2 w-full z-50 overflow-y-auto h-screen px-4 scrollbar-hide">
                <div class="image-slider-inner flex flex-col py-6 md:py-8 text-center">                    
                    <!-- Romantic Quote -->
                    <div class="slider-quote-section mb-8">
                        <p class="slider-quote-line1 text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                            "Our paths always knew,
                        </p>
                        <p class="slider-quote-line2 text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                            though we walked unaware.
                        </p>
                        <p class="slider-quote-line3 text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                            A promise unspoken
                        </p>
                        <p class="slider-quote-line4 text-sm md:text-base text-white font-light italic leading-relaxed text-center mb-6">
                            that led us here.
                        </p>
                        <p class="slider-quote-line5 text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                            We crossed many lifetimes
                        </p>
                        <p class="slider-quote-line6 text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                            to stand side by side.
                        </p>
                        <p class="slider-quote-line7 text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                            <strong>Wherever you are,</strong>
                        </p>
                        <p class="slider-quote-line8 text-sm md:text-base text-white font-light italic leading-relaxed text-center">
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
                        <p class="slider-bottom-text text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                            We said yes, <strong>will you say yes to us?</strong>
                        </p>
                    </div>
                    
                    <!-- Final RSVP Call to Action -->
                    <div class="slider-rsvp-section text-center">
                        <p class="slider-rsvp-text text-sm text-white font-light tracking-wider mb-4">
                            SCROLL TO RSVP
                        </p>
                        <div class="scroll-indicator">
                            <div class="slider-scroll-arrow text-white opacity-70"><i class="fas fa-angle-down"></i></div>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </div>
    
    <!-- Invisible spacer to create scroll distance -->
    <div class="scroll-spacer" style="height: 800vh;"></div>
    
</section>

<!-- Backup GSAP loading in case WordPress doesn't load it properly -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

<style>
/* Hide scrollbars but keep scrolling functionality */
.scrollbar-hide {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Safari and Chrome */
}

/* Smooth image transitions */
#background-image {
    transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
}

/* Image transition effects */
.image-fade-in {
    animation: fadeInScale 0.8s ease-in-out;
}

.image-fade-out {
    animation: fadeOutScale 0.8s ease-in-out;
}

@keyframes fadeInScale {
    0% {
        opacity: 0;
        transform: scale(1.05);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

@keyframes fadeOutScale {
    0% {
        opacity: 1;
        transform: scale(1);
    }
    100% {
        opacity: 0;
        transform: scale(0.95);
    }
}
</style>

<script>
// Single Section Dynamic Content Animation
document.addEventListener('DOMContentLoaded', function() {
    // Check if GSAP is loaded
    if (typeof gsap === 'undefined') {
        console.error('GSAP is not loaded!');
        return;
    }
    
    gsap.registerPlugin(ScrollTrigger);
    
    // Get elements
    const heroContent = document.getElementById('hero-content');
    const invitationContent = document.getElementById('invitation-content');
    const weddingDetailsContent = document.getElementById('wedding-details-content');
    const ceremonyReceptionContent = document.getElementById('ceremony-reception-content');
    const loveStoryContent = document.getElementById('love-story-content');
    const detailedLoveStoryContent = document.getElementById('detailed-love-story-content');
    const finalLoveStoryContent = document.getElementById('final-love-story-content');
    const imageSliderContent = document.getElementById('image-slider-content');
    const backgroundImage = document.getElementById('background-image');
    const backgroundOverlay = document.getElementById('background-overlay');
    
    // Check if mobile device and set initial background image immediately
    let isMobile = window.innerWidth <= 768;
    const initialImageSrc = isMobile ? 
        '<?php echo get_template_directory_uri(); ?>/assets/images/wedding.png' : 
        '<?php echo get_template_directory_uri(); ?>/assets/images/wedding-landscape.png';
    
    // Set the correct image immediately to prevent flash
    backgroundImage.src = initialImageSrc;
    
    // Set initial positions for hero content
    gsap.set('.monogram-combined', { y: -window.innerHeight, opacity: 0 });
    gsap.set('.couple-names', { opacity: 0, y: 30 });
    
    // Set initial positions for invitation content (no monogram needed, we'll use the hero one)
    gsap.set('.greeting-title', { opacity: 0, y: 30 });
    gsap.set('.invitation-message', { opacity: 0, y: 30 });
    gsap.set('.open-invitation-btn', { opacity: 0, y: 30 });
    gsap.set('.reveal-text', { opacity: 0, y: 30 });
    
    // Set initial positions for wedding details content
    gsap.set('.wedding-title', { opacity: 0, y: 30 });
    gsap.set('.wedding-couple-names', { opacity: 0, y: 30 });
    gsap.set('.wedding-date', { opacity: 0, y: 30 });
    gsap.set('.bible-verse', { opacity: 0, y: 30 });
    gsap.set('.groom-info', { opacity: 0, y: 30 });
    gsap.set('.bride-info', { opacity: 0, y: 30 });
    gsap.set('.details-rsvp-text', { opacity: 0, y: 30 });
    gsap.set('.details-scroll-arrow', { opacity: 0, y: 30 });
    
    // Set initial positions for ceremony & reception content
    gsap.set('.ceremony-intro', { opacity: 0, y: 30 });
    gsap.set('.ceremony-title', { opacity: 0, y: 30 });
    gsap.set('.ceremony-time', { opacity: 0, y: 30 });
    gsap.set('.ceremony-location', { opacity: 0, y: 30 });
    gsap.set('.ceremony-place', { opacity: 0, y: 30 });
    gsap.set('.ceremony-address', { opacity: 0, y: 30 });
    gsap.set('.ceremony-map-link', { opacity: 0, y: 30 });
    gsap.set('.ceremony-divider', { opacity: 0, scaleX: 0 });
    gsap.set('.reception-intro', { opacity: 0, y: 30 });
    gsap.set('.reception-title', { opacity: 0, y: 30 });
    gsap.set('.reception-time', { opacity: 0, y: 30 });
    gsap.set('.reception-location', { opacity: 0, y: 30 });
    gsap.set('.reception-place', { opacity: 0, y: 30 });
    gsap.set('.reception-address', { opacity: 0, y: 30 });
    gsap.set('.reception-map-link', { opacity: 0, y: 30 });
    gsap.set('.ceremony-rsvp-text', { opacity: 0, y: 30 });
    gsap.set('.ceremony-scroll-arrow', { opacity: 0, y: 30 });
    
    // Set initial positions for love story content
    gsap.set('.love-story-monogram', { opacity: 0, y: 30 });
    gsap.set('.love-story-title', { opacity: 0, y: 30 });
    gsap.set('.video-placeholder', { opacity: 0, y: 30 });
    gsap.set('.love-quote', { opacity: 0, y: 30 });
    gsap.set('.love-story-rsvp-text', { opacity: 0, y: 30 });
    gsap.set('.love-story-scroll-arrow', { opacity: 0, y: 30 });
    
    // Set initial positions for detailed love story content
    gsap.set('.detailed-love-story-monogram', { opacity: 0, y: 30 });
    gsap.set('.detailed-love-story-title', { opacity: 0, y: 30 });
    gsap.set('.narrative-opening', { opacity: 0, y: 30 });
    gsap.set('.narrative-college', { opacity: 0, y: 30 });
    gsap.set('.narrative-malaysia', { opacity: 0, y: 30 });
    gsap.set('.narrative-return', { opacity: 0, y: 30 });
    gsap.set('.narrative-2020', { opacity: 0, y: 30 });
    gsap.set('.narrative-2022', { opacity: 0, y: 30 });
    gsap.set('.detailed-love-story-rsvp-text', { opacity: 0, y: 30 });
    gsap.set('.detailed-love-story-scroll-arrow', { opacity: 0, y: 30 });
    
    // Set initial positions for final love story content
    gsap.set('.final-love-story-monogram', { opacity: 0, y: 30 });
    gsap.set('.final-love-story-title', { opacity: 0, y: 30 });
    gsap.set('.narrative-time-kind', { opacity: 0, y: 30 });
    gsap.set('.narrative-2023', { opacity: 0, y: 30 });
    gsap.set('.narrative-august', { opacity: 0, y: 30 });
    gsap.set('.vertical-line', { height: 0, opacity: 0 });
    gsap.set('.narrative-proposal', { opacity: 0, y: 30 });
    gsap.set('.narrative-forever', { opacity: 0, y: 30 });
    gsap.set('.narrative-yes', { opacity: 0, y: 30 });
    gsap.set('.narrative-question', { opacity: 0, y: 30 });
    gsap.set('.final-love-story-rsvp-text', { opacity: 0, y: 30 });
    gsap.set('.final-love-story-scroll-arrow', { opacity: 0, y: 30 });
    
    // Set initial positions for image slider content
    gsap.set('.slider-monogram-combined', { opacity: 0, y: 30 });
    gsap.set('.slider-quote-line1', { opacity: 0, y: 30 });
    gsap.set('.slider-quote-line2', { opacity: 0, y: 30 });
    gsap.set('.slider-quote-line3', { opacity: 0, y: 30 });
    gsap.set('.slider-quote-line4', { opacity: 0, y: 30 });
    gsap.set('.slider-quote-line5', { opacity: 0, y: 30 });
    gsap.set('.slider-quote-line6', { opacity: 0, y: 30 });
    gsap.set('.slider-quote-line7', { opacity: 0, y: 30 });
    gsap.set('.slider-quote-line8', { opacity: 0, y: 30 });
    gsap.set('.slider-image-placeholder', { opacity: 0, scale: 0.9 });
    gsap.set('.slider-bottom-text', { opacity: 0, y: 30 });
    gsap.set('.slider-rsvp-text', { opacity: 0, y: 30 });
    gsap.set('.slider-scroll-arrow', { opacity: 0, y: 30 });
    
    // Ensure hero content is visible and others are hidden on load
    heroContent.style.display = 'block';
    heroContent.style.opacity = '1';
    invitationContent.style.display = 'none';
    weddingDetailsContent.style.display = 'none';
    ceremonyReceptionContent.style.display = 'none';
    loveStoryContent.style.display = 'none';
    detailedLoveStoryContent.style.display = 'none';
    finalLoveStoryContent.style.display = 'none';
    imageSliderContent.style.display = 'none';
    
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
        .to('.couple-names', {
            duration: 0.8,
            opacity: 0,
            y: -30,
            ease: "power2.out"
        })
        .to('.monogram-combined', {
            duration: 2,
            y: -window.innerHeight * 0.15, // Move up slightly to position above invitation text
            scale: 0.6, // Scale down to 60% of original size
            ease: "power2.out"
        }, 0)
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
    
    // Create wedding details timeline (paused initially)
    const weddingDetailsTl = gsap.timeline({ paused: true });
    weddingDetailsTl
        .to('.wedding-title', {
            duration: 1,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        })
        .to('.wedding-couple-names', {
            duration: 1,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.1")
        .to('.wedding-date', {
            duration: 0.8,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.2")
        .to('.bible-verse', {
            duration: 1,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.3")
        .to('.groom-info', {
            duration: 0.8,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.2")
        .to('.bride-info', {
            duration: 0.8,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.1")
        .to('.details-rsvp-text', {
            duration: 0.8,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.3")
        .to('.details-scroll-arrow', {
            duration: 0.8,
            opacity: 1,
            y: 0,
            ease: "power2.out"
        }, "+=0.1");
    
    // Ceremony & Reception timeline (paused initially)
    const ceremonyReceptionTl = gsap.timeline({ paused: true });
    ceremonyReceptionTl
        .to('.ceremony-intro', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" })
        .to('.ceremony-title', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.ceremony-time', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.ceremony-location', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.ceremony-place', { duration: 0.5, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.ceremony-address', { duration: 0.5, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.ceremony-map-link', { duration: 0.5, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.ceremony-divider', { duration: 0.8, opacity: 1, scaleX: 1, ease: "power2.out" }, "+=0.2")
        .to('.reception-intro', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.reception-title', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.reception-time', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.reception-location', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.reception-place', { duration: 0.5, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.reception-address', { duration: 0.5, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.reception-map-link', { duration: 0.5, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
        .to('.ceremony-rsvp-text', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.ceremony-scroll-arrow', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1");
    
    // Love Story timeline (paused initially)
    const loveStoryTl = gsap.timeline({ paused: true });
    loveStoryTl
        .to('.love-story-monogram', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
        .to('.love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.video-placeholder', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.love-quote', { duration: 1, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.love-story-rsvp-text', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.love-story-scroll-arrow', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1");
    
    // Detailed Love Story timeline (paused initially)
    const detailedLoveStoryTl = gsap.timeline({ paused: true });
    detailedLoveStoryTl
        .to('.detailed-love-story-monogram', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
        .to('.detailed-love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.narrative-opening', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.narrative-college', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.narrative-malaysia', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.narrative-return', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.narrative-2020', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.narrative-2022', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.detailed-love-story-rsvp-text', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.detailed-love-story-scroll-arrow', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1");
    
    // Final Love Story timeline (paused initially)
    const finalLoveStoryTl = gsap.timeline({ paused: true });
    finalLoveStoryTl
        .to('.final-love-story-monogram', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
        .to('.final-love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.narrative-time-kind', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.narrative-2023', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.narrative-august', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.vertical-line', { duration: 1, height: '4rem', opacity: 1, ease: "power2.out" }, "+=0.3")
        .to('.narrative-proposal', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.narrative-forever', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.narrative-yes', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.narrative-question', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
        .to('.final-love-story-rsvp-text', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.final-love-story-scroll-arrow', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1");
    
    // Image Slider timeline (paused initially)
    const imageSliderTl = gsap.timeline({ paused: true });
    imageSliderTl
        .to('.slider-monogram-combined', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
        .to('.slider-quote-line1', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.slider-quote-line2', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.slider-quote-line3', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.slider-quote-line4', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.slider-quote-line5', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.slider-quote-line6', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.slider-quote-line7', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.slider-quote-line8', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
        .to('.slider-image-placeholder', { duration: 1, opacity: 1, scale: 1, ease: "power2.out" }, "+=0.4")
        .to('.slider-bottom-text', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.slider-rsvp-text', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
        .to('.slider-scroll-arrow', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1");
    
    let currentSection = 'hero'; // 'hero', 'invitation', 'details', 'ceremony', 'love-story', 'detailed-love-story', 'final-love-story', 'image-slider'
    
    // Function to change background with smooth transition
    function changeBackgroundImage(newSrc) {
        // Add fade out effect
        backgroundImage.classList.add('image-fade-out');
        
        setTimeout(() => {
            backgroundImage.src = newSrc;
            backgroundImage.classList.remove('image-fade-out');
            backgroundImage.classList.add('image-fade-in');
            
            setTimeout(() => {
                backgroundImage.classList.remove('image-fade-in');
            }, 800);
        }, 400);
    }
    
    // Function to switch to invitation content
    function switchToInvitation() {
        if (currentSection === 'invitation') return;
        currentSection = 'invitation';
        
        // Change background image with smooth transition
        changeBackgroundImage('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');
        
        // Update background overlay
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/60 to-black/40 transition-opacity duration-1000';
        
        // Hide wedding details and ceremony if visible
        weddingDetailsContent.style.display = 'none';
        ceremonyReceptionContent.style.display = 'none';
        loveStoryContent.style.display = 'none';
        detailedLoveStoryContent.style.display = 'none';
        finalLoveStoryContent.style.display = 'none';
        imageSliderContent.style.display = 'none';
        
        // Show invitation content container first
        invitationContent.style.display = 'block';
        invitationContent.style.opacity = '1';
        
        // Start the monogram zoom-out animation
        invitationTl.restart();
    }
    
    // Function to switch to wedding details content
    function switchToWeddingDetails() {
        if (currentSection === 'details') return;
        currentSection = 'details';
        
        // Change background image with smooth transition (keep same as invitation)
        changeBackgroundImage('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');
        
        // Update background overlay
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/70 to-black/50 transition-opacity duration-1000';
        
        // Hide invitation and ceremony content
        invitationContent.style.display = 'none';
        ceremonyReceptionContent.style.display = 'none';
        loveStoryContent.style.display = 'none';
        detailedLoveStoryContent.style.display = 'none';
        finalLoveStoryContent.style.display = 'none';
        imageSliderContent.style.display = 'none';
        
        // Keep hero monogram visible but maintain its scaled state from invitation
        // (No need to hide it since we want to reuse it)
        
        // Show wedding details content
        weddingDetailsContent.style.display = 'block';
        weddingDetailsContent.style.opacity = '1';
        
        // Start wedding details animation
        weddingDetailsTl.restart();
    }
    
    // Function to switch to ceremony & reception content
    function switchToCeremonyReception() {
        if (currentSection === 'ceremony') return;
        currentSection = 'ceremony';
        
        // Change background image with smooth transition for ceremony (same as wedding details)
        changeBackgroundImage('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');
        
        // Update background overlay
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/80 to-black/60 transition-opacity duration-1000';
        
        // Hide invitation, wedding details, and love story content
        invitationContent.style.display = 'none';
        weddingDetailsContent.style.display = 'none';
        loveStoryContent.style.display = 'none';
        detailedLoveStoryContent.style.display = 'none';
        finalLoveStoryContent.style.display = 'none';
        imageSliderContent.style.display = 'none';
        
        // Show ceremony & reception content
        ceremonyReceptionContent.style.display = 'block';
        ceremonyReceptionContent.style.opacity = '1';
        
        // Start ceremony & reception animation
        ceremonyReceptionTl.restart();
    }
    
    // Function to switch to love story content
    function switchToLoveStory() {
        if (currentSection === 'love-story') return;
        currentSection = 'love-story';
        
        // Change background image with smooth transition for love story
        changeBackgroundImage('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');
        
        // Update background overlay
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/80 to-black/60 transition-opacity duration-1000';
        
        // Hide other content
        invitationContent.style.display = 'none';
        weddingDetailsContent.style.display = 'none';
        ceremonyReceptionContent.style.display = 'none';
        detailedLoveStoryContent.style.display = 'none';
        finalLoveStoryContent.style.display = 'none';
        imageSliderContent.style.display = 'none';
        
        // Show love story content
        loveStoryContent.style.display = 'block';
        loveStoryContent.style.opacity = '1';
        
        // Start love story animation
        loveStoryTl.restart();
    }
    
    // Function to switch to detailed love story content
    function switchToDetailedLoveStory() {
        if (currentSection === 'detailed-love-story') return;
        currentSection = 'detailed-love-story';
        
        // Change background image with smooth transition for detailed love story
        changeBackgroundImage('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');
        
        // Update background overlay
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/80 to-black/60 transition-opacity duration-1000';
        
        // Hide other content
        invitationContent.style.display = 'none';
        weddingDetailsContent.style.display = 'none';
        ceremonyReceptionContent.style.display = 'none';
        loveStoryContent.style.display = 'none';
        finalLoveStoryContent.style.display = 'none';
        imageSliderContent.style.display = 'none';
        
        // Show detailed love story content
        detailedLoveStoryContent.style.display = 'block';
        detailedLoveStoryContent.style.opacity = '1';
        
        // Start detailed love story animation
        detailedLoveStoryTl.restart();
    }
    
    // Function to switch to final love story content
    function switchToFinalLoveStory() {
        if (currentSection === 'final-love-story') return;
        currentSection = 'final-love-story';
        
        // Change background image with smooth transition for final love story
        changeBackgroundImage('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');
        
        // Update background overlay
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/80 to-black/60 transition-opacity duration-1000';
        
        // Hide other content
        invitationContent.style.display = 'none';
        weddingDetailsContent.style.display = 'none';
        ceremonyReceptionContent.style.display = 'none';
        loveStoryContent.style.display = 'none';
        detailedLoveStoryContent.style.display = 'none';
        imageSliderContent.style.display = 'none';
        
        // Show final love story content
        finalLoveStoryContent.style.display = 'block';
        finalLoveStoryContent.style.opacity = '1';
        
        // Start final love story animation
        finalLoveStoryTl.restart();
    }
    
    // Function to switch to image slider content
    function switchToImageSlider() {
        if (currentSection === 'image-slider') return;
        currentSection = 'image-slider';
        
        // Change background image with smooth transition for image slider
        changeBackgroundImage('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg');
        
        // Update background overlay
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/90 to-black/70 transition-opacity duration-1000';
        
        // Hide other content
        invitationContent.style.display = 'none';
        weddingDetailsContent.style.display = 'none';
        ceremonyReceptionContent.style.display = 'none';
        loveStoryContent.style.display = 'none';
        detailedLoveStoryContent.style.display = 'none';
        finalLoveStoryContent.style.display = 'none';
        
        // Show image slider content
        imageSliderContent.style.display = 'block';
        imageSliderContent.style.opacity = '1';
        
        // Start image slider animation
        imageSliderTl.restart();
    }
    
    // Function to switch back to hero content
    function switchToHero() {
        if (currentSection === 'hero') return;
        
        currentSection = 'hero';
        
        // Change background image back with smooth transition - check current device size
        const currentIsMobile = window.innerWidth <= 768;
        const newSrc = currentIsMobile ? 
            '<?php echo get_template_directory_uri(); ?>/assets/images/wedding.png' : 
            '<?php echo get_template_directory_uri(); ?>/assets/images/wedding-landscape.png';
        changeBackgroundImage(newSrc);
        
        // Update background overlay back
        backgroundOverlay.className = 'absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent transition-opacity duration-1000';
        
        // Hide invitation, wedding details, and ceremony content
        invitationContent.style.display = 'none';
        weddingDetailsContent.style.display = 'none';
        ceremonyReceptionContent.style.display = 'none';
        loveStoryContent.style.display = 'none';
        detailedLoveStoryContent.style.display = 'none';
        finalLoveStoryContent.style.display = 'none';
        imageSliderContent.style.display = 'none';
        
        // Reset invitation content positions
        gsap.set('.greeting-title', { opacity: 0, y: 30 });
        gsap.set('.invitation-message', { opacity: 0, y: 30 });
        gsap.set('.open-invitation-btn', { opacity: 0, y: 30 });
        gsap.set('.reveal-text', { opacity: 0, y: 30 });
        
        // Reset wedding details positions
        gsap.set('.wedding-title', { opacity: 0, y: 30 });
        gsap.set('.wedding-couple-names', { opacity: 0, y: 30 });
        gsap.set('.wedding-date', { opacity: 0, y: 30 });
        gsap.set('.bible-verse', { opacity: 0, y: 30 });
        gsap.set('.groom-info', { opacity: 0, y: 30 });
        gsap.set('.bride-info', { opacity: 0, y: 30 });
        gsap.set('.details-rsvp-text', { opacity: 0, y: 30 });
        gsap.set('.details-scroll-arrow', { opacity: 0, y: 30 });
        
        // Reset ceremony & reception positions
        gsap.set('.ceremony-intro', { opacity: 0, y: 30 });
        gsap.set('.ceremony-title', { opacity: 0, y: 30 });
        gsap.set('.ceremony-time', { opacity: 0, y: 30 });
        gsap.set('.ceremony-location', { opacity: 0, y: 30 });
        gsap.set('.ceremony-place', { opacity: 0, y: 30 });
        gsap.set('.ceremony-address', { opacity: 0, y: 30 });
        gsap.set('.ceremony-map-link', { opacity: 0, y: 30 });
        gsap.set('.ceremony-divider', { opacity: 0, scaleX: 0 });
        gsap.set('.reception-intro', { opacity: 0, y: 30 });
        gsap.set('.reception-title', { opacity: 0, y: 30 });
        gsap.set('.reception-time', { opacity: 0, y: 30 });
        gsap.set('.reception-location', { opacity: 0, y: 30 });
        gsap.set('.reception-place', { opacity: 0, y: 30 });
        gsap.set('.reception-address', { opacity: 0, y: 30 });
        gsap.set('.reception-map-link', { opacity: 0, y: 30 });
        gsap.set('.ceremony-rsvp-text', { opacity: 0, y: 30 });
        gsap.set('.ceremony-scroll-arrow', { opacity: 0, y: 30 });
        
        // Reset love story positions
        gsap.set('.love-story-monogram', { opacity: 0, y: 30 });
        gsap.set('.love-story-title', { opacity: 0, y: 30 });
        gsap.set('.video-placeholder', { opacity: 0, y: 30 });
        gsap.set('.love-quote', { opacity: 0, y: 30 });
        gsap.set('.love-story-rsvp-text', { opacity: 0, y: 30 });
        gsap.set('.love-story-scroll-arrow', { opacity: 0, y: 30 });
        
        // Reset detailed love story positions
        gsap.set('.detailed-love-story-monogram', { opacity: 0, y: 30 });
        gsap.set('.detailed-love-story-title', { opacity: 0, y: 30 });
        gsap.set('.narrative-opening', { opacity: 0, y: 30 });
        gsap.set('.narrative-college', { opacity: 0, y: 30 });
        gsap.set('.narrative-malaysia', { opacity: 0, y: 30 });
        gsap.set('.narrative-return', { opacity: 0, y: 30 });
        gsap.set('.narrative-2020', { opacity: 0, y: 30 });
        gsap.set('.narrative-2022', { opacity: 0, y: 30 });
        gsap.set('.detailed-love-story-rsvp-text', { opacity: 0, y: 30 });
        gsap.set('.detailed-love-story-scroll-arrow', { opacity: 0, y: 30 });
        
        // Reset final love story positions
        gsap.set('.final-love-story-monogram', { opacity: 0, y: 30 });
        gsap.set('.final-love-story-title', { opacity: 0, y: 30 });
        gsap.set('.narrative-time-kind', { opacity: 0, y: 30 });
        gsap.set('.narrative-2023', { opacity: 0, y: 30 });
        gsap.set('.narrative-august', { opacity: 0, y: 30 });
        gsap.set('.vertical-line', { height: 0, opacity: 0 });
        gsap.set('.narrative-proposal', { opacity: 0, y: 30 });
        gsap.set('.narrative-forever', { opacity: 0, y: 30 });
        gsap.set('.narrative-yes', { opacity: 0, y: 30 });
        gsap.set('.narrative-question', { opacity: 0, y: 30 });
        gsap.set('.final-love-story-rsvp-text', { opacity: 0, y: 30 });
        gsap.set('.final-love-story-scroll-arrow', { opacity: 0, y: 30 });
        
        // Reset image slider positions
        gsap.set('.slider-monogram-combined', { opacity: 0, y: 30 });
        gsap.set('.slider-quote-line1', { opacity: 0, y: 30 });
        gsap.set('.slider-quote-line2', { opacity: 0, y: 30 });
        gsap.set('.slider-quote-line3', { opacity: 0, y: 30 });
        gsap.set('.slider-quote-line4', { opacity: 0, y: 30 });
        gsap.set('.slider-quote-line5', { opacity: 0, y: 30 });
        gsap.set('.slider-quote-line6', { opacity: 0, y: 30 });
        gsap.set('.slider-quote-line7', { opacity: 0, y: 30 });
        gsap.set('.slider-quote-line8', { opacity: 0, y: 30 });
        gsap.set('.slider-image-placeholder', { opacity: 0, scale: 0.9 });
        gsap.set('.slider-bottom-text', { opacity: 0, y: 30 });
        gsap.set('.slider-rsvp-text', { opacity: 0, y: 30 });
        gsap.set('.slider-scroll-arrow', { opacity: 0, y: 30 });
        
        // Animate monogram back to hero position and show couple names
        gsap.to('.monogram-combined', {
            duration: 1.5,
            y: 0,
            scale: 1,
            opacity: 1,
            ease: "power2.out"
        });
        
        gsap.to('.couple-names', {
            duration: 1,
            opacity: 1,
            y: 0,
            ease: "power2.out",
            delay: 0.5
        });
    }
    
    // Enhanced scroll approach that handles eight sections
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        
        // Calculate scroll percentages for 8 sections
        const invitationTrigger = windowHeight * 0.8;
        const detailsTrigger = windowHeight * 2;
        const ceremonyTrigger = windowHeight * 3;
        const loveStoryTrigger = windowHeight * 4;
        const detailedLoveStoryTrigger = windowHeight * 4.5;
        const finalLoveStoryTrigger = windowHeight * 5.5;
        const imageSliderTrigger = windowHeight * 6.5;
        
        if (scrollY > imageSliderTrigger) {
            switchToImageSlider();
        } else if (scrollY > finalLoveStoryTrigger) {
            switchToFinalLoveStory();
        } else if (scrollY > detailedLoveStoryTrigger) {
            switchToDetailedLoveStory();
        } else if (scrollY > loveStoryTrigger) {
            switchToLoveStory();
        } else if (scrollY > ceremonyTrigger) {
            switchToCeremonyReception();
        } else if (scrollY > detailsTrigger) {
            switchToWeddingDetails();
        } else if (scrollY > invitationTrigger) {
            switchToInvitation();
        } else {
            switchToHero();
        }
    });
    
    // Button click animation and functionality - optimized for smoothness
    const openInvitationBtn = document.querySelector('.open-invitation-btn');
    if (openInvitationBtn) {
        openInvitationBtn.addEventListener('click', function(e) {
            // Button animation
            gsap.to(e.target, {
                scale: 0.95,
                duration: 0.15,
                yoyo: true,
                repeat: 1,
                ease: "power1.inOut",
                onComplete: () => {
                    // After animation, switch to wedding details
                    switchToWeddingDetails();
                }
            });
        });
    }
    
    // Handle window resize for background image
    let resizeTimeout;
    window.addEventListener('resize', function() {
        // Debounce resize events
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const newIsMobile = window.innerWidth <= 768;
            
            // Only change image if we're in hero mode and device type actually changed
            if (currentSection === 'hero' && newIsMobile !== isMobile) {
                const newSrc = newIsMobile ? 
                    '<?php echo get_template_directory_uri(); ?>/assets/images/wedding.png' : 
                    '<?php echo get_template_directory_uri(); ?>/assets/images/wedding-landscape.png';
                backgroundImage.src = newSrc;
                
                // Update the isMobile variable
                isMobile = newIsMobile;
            }
        }, 100);
    });
});
</script>

<?php get_footer(); ?>
