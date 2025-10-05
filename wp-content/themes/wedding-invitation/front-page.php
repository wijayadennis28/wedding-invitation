<?php
// Template helpers are now automatically loaded when the plugin is active
// No need for manual inclusion since the plugin loads them in its init method

get_header(); ?>

<!-- Single Dynamic Section -->
<section class="dynamic-section relative" style="margin: 0; padding: 0; height: 100vh; height: 100dvh; min-height: 100vh; min-height: 100dvh;">
    <!-- Dynamic Background Image -->
    <div class="background-container fixed inset-0 z-0" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg'); background-size: cover; background-position: center;">
        <img id="background-image" src="<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg" alt="" class="w-full h-full object-cover transition-opacity duration-1000 responsive-bg-image" loading="eager" decoding="async" onload="console.log('Image loaded successfully')" onerror="console.log('Image failed to load')">
        <div id="background-overlay" class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent transition-opacity duration-1000"></div>
    </div>
    
    <div class="content-wrapper fixed inset-0 flex items-center justify-center z-10 px-safe-left pr-safe-right">
        <div class="w-full mx-auto px-3 xs:px-4 sm:px-6 md:px-8 text-center relative" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 100%; max-width: none;">
            <?php 
            $groom_name = get_theme_mod('groom_name', 'Dennis');
            $bride_name = get_theme_mod('bride_name', 'Emilia');
            ?>
            
            <!-- Hero Content -->
            <div id="hero-content" class="content-container max-w-4xl mx-auto" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%;">
                <div class="monogram-container mb-3 xs:mb-4 sm:mb-6 leading-none">
                    <span class="monogram-combined">
                        <span class="groom-initial"><?php echo strtolower(substr($groom_name, 0, 1)); ?></span><span class="bride-initial"><?php echo strtolower(substr($bride_name, 0, 1)); ?></span>
                    </span>
                </div>
                
                <h1 class="couple-names text-sm xs:text-base sm:text-lg md:text-xl lg:text-2xl tracking-[0.15em] xs:tracking-[0.2em] sm:tracking-[0.3em] font-medium leading-tight text-white px-2"><?php echo strtoupper($groom_name . ' AND ' . $bride_name); ?>
                </h1>
                
                <!-- Invitation greeting (initially hidden) -->
                <h2 class="hero-greeting-title text-xs xs:text-sm sm:text-base md:text-lg lg:text-2xl font-light text-white mb-3 xs:mb-4 md:mb-6 tracking-wide text-center px-2 leading-relaxed">
                    <?php 
                    // Debug: Check if global variables are set
                    global $wedding_family_data, $wedding_guests_data;
                    if ($wedding_family_data || $wedding_guests_data) {
                        echo "<!-- DEBUG: Global variables are set -->";
                    } else {
                        echo "<!-- DEBUG: Global variables NOT set -->";
                    }
                    
                    // Smart greeting based on titles and relationships
                    if (function_exists('is_wedding_family_page') && is_wedding_family_page()) {
                        $family_data = function_exists('get_wedding_family_data') ? get_wedding_family_data() : null;
                        $guests_data = function_exists('get_wedding_guests_data') ? get_wedding_guests_data() : null;
                        
                        if ($family_data && $guests_data && function_exists('format_smart_wedding_greeting')) {
                            echo format_smart_wedding_greeting($family_data, $guests_data);
                        } else {
                            // Fallback to simple greeting
                            $guest_names = function_exists('get_formatted_guest_names') ? get_formatted_guest_names() : '';
                            if ($guest_names) {
                                echo 'DEAR ' . strtoupper($guest_names) . ',';
                            } else {
                                echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                            }
                        }
                    } else {
                        echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                    }
                    ?>
                </h2>
                
                <!-- Invitation message at bottom (initially hidden) -->
                <div class="hero-invitation-bottom" style="position: absolute; bottom: -180px; left: 50%; transform: translateX(-50%); width: 100%; text-align: center;">
                    <p class="hero-invitation-message text-xs xs:text-sm sm:text-base md:text-lg text-white leading-relaxed mb-4 xs:mb-6 md:mb-8 max-w-xs xs:max-w-sm sm:max-w-md mx-auto px-3">
                        You are warmly invited to join us on our wedding day.<br>
                        We apologise for any misspellings of names or titles.
                    </p>
                    
                    <button class="hero-open-invitation-btn bg-transparent border border-white text-white px-4 xs:px-6 sm:px-8 py-2 xs:py-3 text-xs xs:text-sm font-light tracking-wider xs:tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                        OPEN THE INVITATION
                    </button>
                </div>
            </div>
            
            <!-- Invitation Opening Content (Initially Hidden) -->
            <div id="invitation-content" class="content-container max-w-2xl mx-auto" style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 100%; opacity: 0; visibility: hidden;">
                <div class="invitation-text text-center">
                    <h2 class="greeting-title text-sm md:text-2xl font-light text-white mb-4 md:mb-6 tracking-wide">
                        <?php 
                        // Dynamic greeting for invitation content
                        if (function_exists('is_wedding_family_page') && is_wedding_family_page()) {
                            $guest_names = function_exists('get_formatted_guest_names') ? get_formatted_guest_names() : '';
                            if ($guest_names) {
                                echo 'DEAR ' . strtoupper($guest_names) . ',';
                            } else {
                                echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                            }
                        } else {
                            echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                        }
                        ?>
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
            
            
        </div>
    </div>
    
</section>



<!-- Wedding Details Section -->
<section id="wedding-details" class="wedding-details-section relative" style="margin: 0; padding: 0; height: 100vh; height: 100dvh; min-height: 100vh; min-height: 100dvh;">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('wedding_details') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" alt="Wedding Details" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-black/50"></div>
    </div>
    
    <div class="relative z-10 flex items-center justify-center" style="height: 100vh; height: 100dvh; min-height: 100vh; min-height: 100dvh;">
        <div class="max-w-xs xs:max-w-sm sm:max-w-lg md:max-w-2xl mx-auto px-3 xs:px-4 sm:px-6 py-6 xs:py-8 md:py-12 text-center">
            <!-- Wedding Title -->
            <div class="wedding-title-section mb-4 xs:mb-6 md:mb-8 text-center">
                <h2 class="wedding-title text-sm xs:text-base md:text-xl font-light text-white mb-2 xs:mb-3 md:mb-4 tracking-wider italic">
                    The wedding of
                </h2>
                <h1 class="wedding-couple-names text-lg xs:text-xl sm:text-2xl md:text-3xl font-medium text-white tracking-wider leading-tight">
                    <?php echo strtoupper($groom_name . ' AND ' . $bride_name); ?>
                </h1>
            </div>
            
            <!-- Wedding Date -->
            <div class="wedding-date-section mt-3 xs:mt-4 md:mt-8 mb-6 xs:mb-8 md:mb-12 text-center">
                <p class="wedding-date text-xs xs:text-sm md:text-lg text-white font-light tracking-wide">
                    Saturday, 22 November 2025
                </p>
            </div>
            
            <!-- Bible Verse -->
            <div class="bible-verse-section mb-6 xs:mb-8 md:mb-12 text-center">
                <p class="bible-verse text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light leading-relaxed max-w-xs xs:max-w-sm sm:max-w-lg mx-auto px-2 xs:px-4">
                    "Love knows no limit to its endurance, <br>
                    no end to its trust, love still stands <br>
                    when all else has fallen."<br>
                    <span class="verse-reference mt-4 xs:mt-6 md:mt-12 text-2xs xs:text-xs tracking-wider block">1 Corinthians 13:7-8</span>
                </p>
            </div>
            
            <!-- Wedding Details -->
            <div class="wedding-info-section mb-6 xs:mb-8 md:mb-12 text-center">
                <div class="grid grid-cols-2 gap-3 xs:gap-4 sm:gap-6 md:gap-8 max-w-xs xs:max-w-sm sm:max-w-lg mx-auto">
                    <div class="groom-info">
                        <h3 class="person-name text-sm xs:text-base md:text-lg text-white font-medium mb-1 xs:mb-2">Dennis <br>Wijaya</h3>
                        <p class="person-details text-2xs xs:text-xs md:text-sm text-white font-light">
                            First son of<br>
                            <b>Saleh Widjaja </b> and<br>
                            <b>Soesi Wijaya </b>
                        </p>
                    </div>
                    
                    <div class="bride-info">
                        <h3 class="person-name text-sm xs:text-base md:text-lg text-white font-medium mb-1 xs:mb-2">Emilia Bewintara</h3>
                        <p class="person-details text-2xs xs:text-xs md:text-sm text-white font-light">
                            Second daughter of<br>
                            <b>Budy Bewintara </b> and<br>
                            <b>Lindawati</b>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ceremony & Reception Section -->
<section id="ceremony-reception" class="ceremony-reception-section relative min-h-screen">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('ceremony_reception') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" alt="Ceremony Reception" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    </div>
    
    <div class="relative z-10 flex items-center justify-center min-h-screen">
        <div class="max-w-xs xs:max-w-sm sm:max-w-lg md:max-w-2xl mx-auto px-3 xs:px-4 sm:px-6 py-6 xs:py-8 md:py-12 text-center">
            <p class="ceremony-intro text-2xs xs:text-xs md:text-sm text-white font-light mb-1 xs:mb-2">We request the blessing of your presence as we are united in</p>
            <h2 class="ceremony-title text-base xs:text-lg md:text-xl tracking-widest mb-1 xs:mb-2 text-white font-semibold">HOLY MATRIMONY</h2>
            <div class="ceremony-time text-xs xs:text-sm md:text-base tracking-wider mb-3 xs:mb-4 text-white font-medium">9 AM ONWARD</div>
            <div class="ceremony-location mb-4 xs:mb-6 text-white">
                <div class="ceremony-place font-semibold tracking-widest text-xs xs:text-sm md:text-lg">GEREJA KATOLIK<br>SANTO LAURENSIUS</div>
                <div class="ceremony-address text-2xs xs:text-xs md:text-sm font-light italic mt-1 xs:mt-2 mb-2 xs:mb-3">Jl. Sutera Utama No. 2, Alam Sutera<br>Tangerang, Banten 15326</div>
                <a href="https://maps.app.goo.gl/wWV4HAQFGC6D9Xy76" target="_blank" class="ceremony-map-link text-2xs xs:text-xs underline text-white tracking-wider">VIEW MAP</a>
            </div>
            <hr class="ceremony-divider my-4 xs:my-6 md:my-8 border-white/30">
            <p class="reception-intro text-2xs xs:text-xs md:text-sm text-white font-light mb-1 xs:mb-2">We request the pleasure of your company at our</p>
            <h2 class="reception-title text-base xs:text-lg md:text-xl font-light text-white tracking-widest mb-1 xs:mb-2">EVENING RECEPTION</h2>
            <div class="reception-time text-xs xs:text-sm md:text-base text-white font-semibold tracking-wider mb-3 xs:mb-4">6 PM ONWARD</div>
            <div class="reception-location text-white mb-4 xs:mb-6">
                <div class="reception-place font-semibold tracking-widest text-xs xs:text-sm md:text-lg">JHL SOLITAIRE</div>
                <div class="reception-address text-2xs xs:text-xs md:text-sm font-light italic mt-1 xs:mt-2 mb-2 xs:mb-3">Jl. Gading Serpong Boulevard,<br>Blok S no. 5, Gading Serpong,<br>Tangerang, Banten 15810</div>
                <a href="https://maps.app.goo.gl/xPCwuyatC8ghgDd79" target="_blank" class="reception-map-link text-2xs xs:text-xs underline text-white tracking-wider">VIEW MAP</a>
            </div>
        </div>
    </div>
</section>

<!-- Love Story Section -->
<section id="love-story" class="love-story-section relative min-h-screen">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('love_story') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" alt="Love Story" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    </div>
    
    <div class="relative z-10 flex items-center justify-center min-h-screen">
        <div class="max-w-xs xs:max-w-sm sm:max-w-lg md:max-w-2xl mx-auto px-3 xs:px-4 sm:px-6 py-6 xs:py-8 md:py-12 text-center">
            <!-- Love Story Title -->
            <h2 class="love-story-title text-base xs:text-lg md:text-2xl font-bold mb-4 xs:mb-6 md:mb-8 tracking-wider italic text-white">
                The Love Story
            </h2>
            
            <!-- Video Container -->
            <div class="love-story-video-container mb-4 xs:mb-6 md:mb-8 relative">
                <div class="video-placeholder bg-gray-800 rounded-lg overflow-hidden relative aspect-video max-w-xs xs:max-w-sm md:max-w-md mx-auto">
                    <!-- Placeholder for video -->
                    <div class="video-overlay absolute inset-0 bg-black/50 flex items-center justify-center">
                        <div class="play-button w-10 xs:w-12 md:w-16 h-10 xs:h-12 md:h-16 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <div class="play-icon w-0 h-0 border-l-[12px] xs:border-l-[16px] md:border-l-[20px] border-l-white border-t-[8px] xs:border-t-[10px] md:border-t-[12px] border-t-transparent border-b-[8px] xs:border-b-[10px] md:border-b-[12px] border-b-transparent ml-1"></div>
                        </div>
                    </div>
                    <!-- Placeholder image -->
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg" alt="Love Story" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
                </div>
            </div>
            
            <!-- Love Quote -->
            <div class="love-quote-section mb-4 xs:mb-6 md:mb-8">
                <p class="love-quote text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed max-w-xs xs:max-w-sm mx-auto px-2 xs:px-4">
                    "..Their paths were always aligned,<br>
                    <b>yet they never met,</b><br>
                    as if the stars were never quite set."
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Detailed Love Story Section -->
<section id="detailed-love-story" class="detailed-love-story-section relative min-h-screen">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('detailed_love_story') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" alt="Detailed Love Story" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    </div>
    
    <div class="relative z-10 flex items-center justify-center min-h-screen">
        <div class="max-w-xs xs:max-w-sm sm:max-w-lg md:max-w-2xl mx-auto px-3 xs:px-4 sm:px-6 py-4 xs:py-6 md:py-8 text-center">
            <!-- Love Story Title -->
            <h2 class="detailed-love-story-title text-base xs:text-xl md:text-2xl font-bold mb-4 xs:mb-6 mt-6 xs:mt-8 md:mt-10 tracking-wider italic text-white">
                The Love Story
            </h2>
            
            <!-- Love Story Narrative -->
            <div class="love-story-narrative text-white text-2xs xs:text-xs sm:text-sm md:text-base leading-relaxed space-y-2 xs:space-y-3 max-w-xs xs:max-w-sm sm:max-w-lg mx-auto">
                <p class="narrative-opening text-center mb-3 xs:mb-4">
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
        </div>
    </div>
</section>

<!-- Final Love Story Section -->
<section id="final-love-story" class="final-love-story-section relative min-h-screen">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('final_love_story') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" alt="Final Love Story" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    </div>
    
    <div class="relative z-10 flex items-center justify-center min-h-screen">
        <div class="max-w-xs xs:max-w-sm sm:max-w-lg md:max-w-2xl mx-auto px-3 xs:px-4 sm:px-6 py-4 xs:py-6 md:py-8 text-center">
            <!-- Love Story Title -->
            <h2 class="final-love-story-title text-base xs:text-xl md:text-2xl font-bold mb-4 xs:mb-6 tracking-wider italic text-white">
                The Love Story
            </h2>
            
            <!-- Final Love Story Narrative -->
            <div class="final-love-story-narrative text-white text-2xs xs:text-xs sm:text-sm md:text-base leading-relaxed space-y-2 xs:space-y-3 max-w-xs xs:max-w-sm sm:max-w-lg mx-auto">
                <p class="narrative-time-kind text-center mb-3 xs:mb-4">
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
                <div class="proposal-divider flex justify-center my-4 xs:my-6">
                    <div class="vertical-line w-px bg-white/50 h-12 xs:h-16"></div>
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
        </div>
    </div>
</section>

<!-- Image Slider Section -->
<section id="image-slider" class="image-slider-section relative min-h-screen">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('image_slider') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" alt="Image Slider" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-black/60"></div>
    </div>
    
    <div class="relative z-10 flex items-center justify-center min-h-screen">
        <div class="max-w-xs xs:max-w-sm sm:max-w-lg md:max-w-2xl mx-auto px-3 xs:px-4 sm:px-6 py-4 xs:py-6 md:py-8 text-center">
            <!-- Romantic Quote -->
            <div class="slider-quote-section mb-6 xs:mb-8">
                <p class="slider-quote-line1 text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                    "Our paths always knew,
                </p>
                <p class="slider-quote-line2 text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                    though we walked unaware.
                </p>
                <p class="slider-quote-line3 text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                    A promise unspoken
                </p>
                <p class="slider-quote-line4 text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center mb-4 xs:mb-6">
                    that led us here.
                </p>
                <p class="slider-quote-line5 text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                    We crossed many lifetimes
                </p>
                <p class="slider-quote-line6 text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                    to stand side by side.
                </p>
                <p class="slider-quote-line7 text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                    <strong>Wherever you are,</strong>
                </p>
                <p class="slider-quote-line8 text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                    <strong>my heart will reside."</strong>
                </p>
            </div>
            
            <!-- Image Container -->
            <div class="slider-image-container mb-6 xs:mb-8 relative">
                <div class="slider-image-placeholder bg-gray-800 rounded-lg overflow-hidden relative aspect-square max-w-xs xs:max-w-sm mx-auto">
                    <!-- Placeholder for slider image -->
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg" alt="Love Story Image" class="slider-main-image w-full h-full object-cover">
                    
                    <!-- Navigation arrows (for future slider functionality) -->
                    <div class="slider-nav absolute inset-y-0 left-0 flex items-center">
                        <button class="slider-prev bg-white/20 hover:bg-white/30 text-white p-1 xs:p-2 ml-1 xs:ml-2 rounded-full transition-all">
                            <svg class="w-3 xs:w-4 h-3 xs:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="slider-nav absolute inset-y-0 right-0 flex items-center">
                        <button class="slider-next bg-white/20 hover:bg-white/30 text-white p-1 xs:p-2 mr-1 xs:mr-2 rounded-full transition-all">
                            <svg class="w-3 xs:w-4 h-3 xs:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Quote -->
            <div class="slider-bottom-quote mb-6 xs:mb-8">
                <p class="slider-bottom-text text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                    We said yes, <strong>will you say yes to us?</strong>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- RSVP Section -->
<section id="rsvp" class="rsvp-section relative" style="margin: 0; padding: 0; height: 100vh; height: 100dvh; min-height: 100vh; min-height: 100dvh;">
    <!-- Background Image -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('rsvp') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" alt="RSVP" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 to-black/70"></div>
    </div>
    
    <div class="relative z-10 flex items-center justify-center" style="height: 100vh; height: 100dvh; min-height: 100vh; min-height: 100dvh;">
        <div class="max-w-xs xs:max-w-sm sm:max-w-lg md:max-w-2xl mx-auto px-3 xs:px-4 sm:px-6 py-6 xs:py-8 md:py-12 text-center">
            
            <?php if (function_exists('is_wedding_family_page') && is_wedding_family_page()): ?>
                <!-- Family-specific Multi-step RSVP Form -->
                <div id="family-rsvp-container" class="rsvp-content">
                    <!-- Step 1: Will You Be Attending -->
                    <div id="family-step-1" class="rsvp-step active">
                        <h2 class="rsvp-title text-lg xs:text-xl md:text-2xl font-medium text-white mb-2 xs:mb-3 md:mb-4 tracking-wider">
                            RSVP
                        </h2>
                        <h3 class="attendance-question text-sm xs:text-base md:text-lg text-white mb-6 xs:mb-8 md:mb-10 font-light tracking-wide">
                            WILL YOU BE ATTENDING?
                        </h3>
                        
                        <div class="attendance-options mb-6 xs:mb-8 space-y-4">
                            <div class="attendance-button-wrapper">
                                <button type="button" class="attendance-btn attendance-yes w-full max-w-sm mx-auto block bg-white/20 backdrop-blur-sm border-2 border-white/50 text-white px-8 py-4 text-xs xs:text-sm font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded-lg" data-answer="yes">
                                    With joy, I'll be there
                                </button>
                            </div>
                            <div class="attendance-button-wrapper">
                                <button type="button" class="attendance-btn attendance-no w-full max-w-sm mx-auto block bg-white/20 backdrop-blur-sm border-2 border-white/50 text-white px-8 py-4 text-xs xs:text-sm font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded-lg" data-answer="no">
                                    Regretfully, can't attend
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 2: Event Selection -->
                    <div id="family-step-2" class="rsvp-step hidden">
                        <h2 class="rsvp-title text-lg xs:text-xl md:text-2xl font-medium text-white mb-2 xs:mb-3 md:mb-4 tracking-wider">
                            RSVP
                        </h2>
                        <h3 class="event-question text-sm xs:text-base md:text-lg text-white mb-6 xs:mb-8 md:mb-10 font-light tracking-wide">
                            WHICH EVENT WILL YOU ATTEND?
                        </h3>
                        
                        <div class="event-options mb-6 xs:mb-8 space-y-3 xs:space-y-4">
                            <button type="button" class="event-btn w-full max-w-xs mx-auto block bg-white/10 backdrop-blur-sm border border-white/30 text-white px-6 py-4 text-2xs xs:text-xs font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded" data-event="church">
                                Holy Matrimony
                            </button>
                            <button type="button" class="event-btn w-full max-w-xs mx-auto block bg-white/10 backdrop-blur-sm border border-white/30 text-white px-6 py-4 text-2xs xs:text-xs font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded" data-event="reception">
                                Evening Reception
                            </button>
                            <!-- Teapai only for family category = "family" -->
                            <div id="teapai-option" class="hidden">
                                <button type="button" class="event-btn w-full max-w-xs mx-auto block bg-white/10 backdrop-blur-sm border border-white/30 text-white px-6 py-4 text-2xs xs:text-xs font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded" data-event="teapai">
                                    Teapai
                                </button>
                            </div>
                            <button type="button" class="event-btn w-full max-w-xs mx-auto block bg-white/10 backdrop-blur-sm border border-white/30 text-white px-6 py-4 text-2xs xs:text-xs font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded" data-event="both">
                                Both
                            </button>
                        </div>
                        
                        <button type="button" class="back-btn flex items-center text-white text-2xs xs:text-xs font-light tracking-wider hover:text-white/70 transition-all duration-300" onclick="showFamilyStep(1)">
                            <i class="fas fa-arrow-left mr-2"></i> BACK
                        </button>
                    </div>
                    
                    <!-- Step 3: Guest List -->
                    <div id="family-step-3" class="rsvp-step hidden">
                        <h2 class="rsvp-title text-lg xs:text-xl md:text-2xl font-medium text-white mb-2 xs:mb-3 md:mb-4 tracking-wider">
                            RSVP
                        </h2>
                        <h3 class="guest-list-title text-sm xs:text-base md:text-lg text-white mb-2 xs:mb-3 font-light tracking-wide">
                            GUEST LIST
                        </h3>
                        <p class="guest-list-subtitle text-2xs xs:text-xs text-white/70 mb-6 xs:mb-8 font-light italic">
                            Select who will be attending
                        </p>
                        
                        <div id="family-members-list" class="guest-checkboxes mb-6 xs:mb-8 space-y-3 xs:space-y-4">
                            <!-- Family members will be populated by JavaScript -->
                        </div>
                        
                        <div class="additional-info-section mb-6 xs:mb-8 space-y-3 xs:space-y-4">
                            <div class="form-group">
                                <textarea id="family-dietary-requirements" 
                                          name="dietary_requirements" 
                                          placeholder="Dietary requirements or allergies" 
                                          rows="3" 
                                          class="w-full px-3 xs:px-4 py-2 xs:py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded text-white placeholder-white/70 text-2xs xs:text-xs resize-none focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"></textarea>
                            </div>
                            <div class="form-group">
                                <textarea id="family-additional-notes" 
                                          name="additional_notes" 
                                          placeholder="Additional notes or special requests" 
                                          rows="3" 
                                          class="w-full px-3 xs:px-4 py-2 xs:py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded text-white placeholder-white/70 text-2xs xs:text-xs resize-none focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"></textarea>
                            </div>
                        </div>
                        
                        <div class="step-actions space-y-4">
                            <button type="button" id="family-submit-btn" class="rsvp-submit-btn w-full bg-transparent border border-white text-white px-6 py-3 text-2xs xs:text-xs font-light tracking-widest hover:bg-white hover:text-black transition-all duration-300 rounded">
                                SUBMIT RSVP
                            </button>
                            <button type="button" class="back-btn flex items-center text-white text-2xs xs:text-xs font-light tracking-wider hover:text-white/70 transition-all duration-300" onclick="showFamilyStep(2)">
                                <i class="fas fa-arrow-left mr-2"></i> BACK
                            </button>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <div id="family-rsvp-success" class="rsvp-step hidden text-center">
                        <h3 class="text-lg xs:text-xl text-white mb-3 xs:mb-4 font-medium">
                            Thank You!
                        </h3>
                        <p class="text-2xs xs:text-xs text-white/90 leading-relaxed">
                            Your RSVP has been received. We can't wait to celebrate with you!
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <!-- General Users Multi-step RSVP Form -->
                <div id="general-rsvp-container" class="rsvp-content">
                    <!-- Step 1: Event Selection (No attendance question) -->
                    <div id="general-step-1" class="rsvp-step active">
                        <h2 class="rsvp-title text-lg xs:text-xl md:text-2xl font-medium text-white mb-2 xs:mb-3 md:mb-4 tracking-wider">
                            RSVP
                        </h2>
                        <h3 class="event-question text-sm xs:text-base md:text-lg text-white mb-6 xs:mb-8 md:mb-10 font-light tracking-wide">
                            WHICH EVENT WILL YOU ATTEND?
                        </h3>
                        
                        <div class="event-options mb-6 xs:mb-8 space-y-3 xs:space-y-4">
                            <button type="button" class="general-event-btn w-full max-w-xs mx-auto block bg-white/10 backdrop-blur-sm border border-white/30 text-white px-6 py-4 text-2xs xs:text-xs font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded" data-event="church">
                                Holy Matrimony
                            </button>
                            <button type="button" class="general-event-btn w-full max-w-xs mx-auto block bg-white/10 backdrop-blur-sm border border-white/30 text-white px-6 py-4 text-2xs xs:text-xs font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded" data-event="reception">
                                Evening Reception
                            </button>
                            <!-- No Teapai for general users -->
                            <button type="button" class="general-event-btn w-full max-w-xs mx-auto block bg-white/10 backdrop-blur-sm border border-white/30 text-white px-6 py-4 text-2xs xs:text-xs font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded" data-event="both">
                                Both
                            </button>
                        </div>
                    </div>
                    
                    <!-- Step 2: Guest Information -->
                    <div id="general-step-2" class="rsvp-step hidden">
                        <h2 class="rsvp-title text-lg xs:text-xl md:text-2xl font-medium text-white mb-2 xs:mb-3 md:mb-4 tracking-wider">
                            RSVP
                        </h2>
                        <h3 class="guest-info-title text-sm xs:text-base md:text-lg text-white mb-6 xs:mb-8 md:mb-10 font-light tracking-wide">
                            GUEST INFORMATION
                        </h3>
                        
                        <div class="guest-info-form space-y-4 xs:space-y-6 mb-6 xs:mb-8">
                            <!-- Contact Information -->
                            <div class="contact-info space-y-3 xs:space-y-4">
                                <input type="text" 
                                       id="general-guest-name" 
                                       name="guest_name" 
                                       placeholder="Your Name" 
                                       required 
                                       class="w-full px-3 xs:px-4 py-2 xs:py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded text-white placeholder-white/70 text-2xs xs:text-xs focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent">
                                
                                <input type="email" 
                                       id="general-guest-email" 
                                       name="guest_email" 
                                       placeholder="Your Email" 
                                       required 
                                       class="w-full px-3 xs:px-4 py-2 xs:py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded text-white placeholder-white/70 text-2xs xs:text-xs focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent">
                            </div>
                            
                            <!-- Guest Count Selection -->
                            <div class="guest-count-section">
                                <label class="block text-white text-2xs xs:text-xs font-light mb-2">Number of Guests (1-4):</label>
                                <select id="general-guest-count" name="guest_count" class="w-full px-3 xs:px-4 py-2 xs:py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded text-white text-2xs xs:text-xs focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent">
                                    <option value="1">1 Guest</option>
                                    <option value="2">2 Guests</option>
                                    <option value="3">3 Guests</option>
                                    <option value="4">4 Guests</option>
                                </select>
                            </div>
                            
                            <!-- Dynamic Guest Name Fields -->
                            <div id="general-guest-names" class="guest-names-section space-y-2 xs:space-y-3">
                                <!-- Guest name inputs will be populated by JavaScript -->
                            </div>
                            
                            <!-- Additional Information -->
                            <div class="additional-info space-y-3 xs:space-y-4">
                                <textarea id="general-dietary-requirements" 
                                          name="dietary_requirements" 
                                          placeholder="Dietary requirements or allergies" 
                                          rows="3" 
                                          class="w-full px-3 xs:px-4 py-2 xs:py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded text-white placeholder-white/70 text-2xs xs:text-xs resize-none focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"></textarea>
                                
                                <textarea id="general-additional-notes" 
                                          name="additional_notes" 
                                          placeholder="Additional notes or special requests" 
                                          rows="3" 
                                          class="w-full px-3 xs:px-4 py-2 xs:py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded text-white placeholder-white/70 text-2xs xs:text-xs resize-none focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent"></textarea>
                            </div>
                        </div>
                        
                        <div class="step-actions space-y-4">
                            <button type="button" id="general-submit-btn" class="rsvp-submit-btn w-full bg-transparent border border-white text-white px-6 py-3 text-2xs xs:text-xs font-light tracking-widest hover:bg-white hover:text-black transition-all duration-300 rounded">
                                SUBMIT RSVP
                            </button>
                            <button type="button" class="back-btn flex items-center text-white text-2xs xs:text-xs font-light tracking-wider hover:text-white/70 transition-all duration-300" onclick="showGeneralStep(1)">
                                <i class="fas fa-arrow-left mr-2"></i> BACK
                            </button>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <div id="general-rsvp-success" class="rsvp-step hidden text-center">
                        <h3 class="text-lg xs:text-xl text-white mb-3 xs:mb-4 font-medium">
                            Thank You!
                        </h3>
                        <p class="text-2xs xs:text-xs text-white/90 leading-relaxed">
                            Your RSVP has been received. We can't wait to celebrate with you!
                        </p>
                    </div>
                </div>
            <?php endif; ?>
            
        </div>
    </div>
</section>



<!-- Backup GSAP loading in case WordPress doesn't load it properly -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Observer.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>

<style>
/* Remove gaps between sections */
* {
    box-sizing: border-box;
}

html, body {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow-x: hidden;
}

/* WordPress theme resets */
body.home, body.page {
    margin: 0 !important;
    padding: 0 !important;
}

#page, #content, .site-content {
    margin: 0 !important;
    padding: 0 !important;
}

/* Ensure sections connect seamlessly - NO GAPS */
section {
    margin: 0 !important;
    padding: 0 !important;
    display: block;
    position: relative;
}

/* Force exact viewport heights - eliminate all gaps */
.dynamic-section, #wedding-details {
    height: 100vh !important;
    height: 100dvh !important;
    min-height: 100vh !important;
    min-height: 100dvh !important;
    max-height: 100vh !important;
    max-height: 100dvh !important;
    margin: 0 !important;
    padding: 0 !important;
    border: none !important;
    outline: none !important;
}

/* Fix for any remaining min-h-screen gaps */
.mobile-full-height, .min-h-screen {
    margin: 0 !important;
    padding: 0 !important;
}

/* Specific responsive image class for all background images */
.responsive-bg-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease, opacity 0.3s ease;
    will-change: transform;
}

/* Performance optimizations for images */
.responsive-bg-image {
    image-rendering: -webkit-optimize-contrast;
    image-rendering: optimize-contrast;
    backface-visibility: hidden;
    -webkit-backface-visibility: hidden;
}

/* Responsive image handling for all screen sizes */
.background-container {
    position: fixed;
    inset: 0;
    z-index: 0;
    overflow: hidden;
}

.background-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: opacity 0.8s ease-in-out, transform 0.8s ease-in-out;
}

/* Section background images - responsive handling */
.wedding-details-section .absolute.inset-0,
.ceremony-reception-section .absolute.inset-0,
.rsvp-section .absolute.inset-0,
.love-story-section .absolute.inset-0,
.detailed-love-story-section .absolute.inset-0,
.final-love-story-section .absolute.inset-0,
.image-slider-section .absolute.inset-0 {
    overflow: hidden;
}

.wedding-details-section img,
.ceremony-reception-section img,
.rsvp-section img,
.love-story-section img,
.detailed-love-story-section img,
.final-love-story-section img,
.image-slider-section img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
}

/* Mobile-first responsive adjustments */
@media (max-width: 480px) {
    .background-container img,
    .wedding-details-section img,
    .ceremony-reception-section img,
    .rsvp-section img,
    .love-story-section img,
    .detailed-love-story-section img,
    .final-love-story-section img,
    .image-slider-section img {
        object-position: center center;
        transform: scale(1.05); /* Slight zoom for better mobile framing */
    }
}

/* Tablet adjustments */
@media (min-width: 481px) and (max-width: 768px) {
    .background-container img,
    .wedding-details-section img,
    .ceremony-reception-section img,
    .rsvp-section img,
    .love-story-section img,
    .detailed-love-story-section img,
    .final-love-story-section img,
    .image-slider-section img {
        object-position: center center;
        transform: scale(1.02); /* Minimal zoom for tablets */
    }
}

/* Desktop and larger screens */
@media (min-width: 769px) {
    .background-container img,
    .wedding-details-section img,
    .ceremony-reception-section img,
    .rsvp-section img,
    .love-story-section img,
    .detailed-love-story-section img,
    .final-love-story-section img,
    .image-slider-section img {
        object-position: center center;
        transform: scale(1);
    }
}

/* High resolution displays */
@media (min-width: 1200px) {
    .background-container img,
    .wedding-details-section img,
    .ceremony-reception-section img,
    .rsvp-section img,
    .love-story-section img,
    .detailed-love-story-section img,
    .final-love-story-section img,
    .image-slider-section img {
        object-fit: cover;
        object-position: center center;
    }
}

/* Ultra-wide screens */
@media (min-width: 1920px) {
    .background-container img,
    .wedding-details-section img,
    .ceremony-reception-section img,
    .rsvp-section img,
    .love-story-section img,
    .detailed-love-story-section img,
    .final-love-story-section img,
    .image-slider-section img {
        object-fit: cover;
        object-position: center center;
        transform: scale(1);
    }
}

/* Portrait orientation optimizations */
@media (orientation: portrait) {
    .background-container img,
    .wedding-details-section img,
    .ceremony-reception-section img,
    .rsvp-section img,
    .love-story-section img,
    .detailed-love-story-section img,
    .final-love-story-section img,
    .image-slider-section img {
        object-position: center top;
    }
}

/* Landscape orientation optimizations */
@media (orientation: landscape) and (max-height: 600px) {
    .background-container img,
    .wedding-details-section img,
    .ceremony-reception-section img,
    .rsvp-section img,
    .love-story-section img,
    .detailed-love-story-section img,
    .final-love-story-section img,
    .image-slider-section img {
        object-position: center center;
        object-fit: cover;
    }
}

/* Retina display optimizations */
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .background-container img,
    .wedding-details-section img,
    .ceremony-reception-section img,
    .rsvp-section img,
    .love-story-section img,
    .detailed-love-story-section img,
    .final-love-story-section img,
    .image-slider-section img {
        image-rendering: -webkit-optimize-contrast;
        image-rendering: optimize-contrast;
    }
}

/* Hide scrollbars but keep scrolling functionality - Enhanced for Safari */
.scrollbar-hide {
    -ms-overflow-style: none;  /* Internet Explorer 10+ */
    scrollbar-width: none;  /* Firefox */
    -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;  /* Safari and Chrome */
    width: 0 !important;
    height: 0 !important;
}

/* Apply to body and html when scrollbar-hide is active */
body.scrollbar-hide,
html.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
    -webkit-overflow-scrolling: touch;
}

body.scrollbar-hide::-webkit-scrollbar,
html.scrollbar-hide::-webkit-scrollbar {
    display: none !important;
    width: 0 !important;
    height: 0 !important;
}

/* Mobile-specific scrollbar hiding */
@media (max-width: 768px) {
    .scrollbar-hide,
    body.scrollbar-hide,
    html.scrollbar-hide {
        -webkit-overflow-scrolling: touch;
        overflow-x: hidden; /* Prevent horizontal scroll */
    }
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

/* Safari iOS fixes */
body {
    -webkit-text-size-adjust: 100%;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    user-select: none;
}

/* Fix viewport issues on Safari iOS */
.dynamic-section, .content-wrapper {
    -webkit-overflow-scrolling: touch;
    will-change: transform;
}

/* Prevent zoom on input focus (Safari iOS) */
input, select, textarea {
    font-size: 16px !important;
}

/* Fix fixed positioning on Safari iOS */
@supports (-webkit-touch-callout: none) {
    .fixed {
        position: -webkit-sticky !important;
        position: sticky !important;
    }
    
    .content-wrapper {
        position: absolute !important;
    }
}

/* Hero Section Custom Typography */
.hero-greeting-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    letter-spacing: 5px;
    line-height: 1.4;
}

.hero-invitation-message {
    font-family: 'Playfair Display', serif;
    font-weight: 400;
    font-style: italic;
}

.hero-open-invitation-btn {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    font-style: normal;
    letter-spacing: 5px;
}

/* Mobile-specific fixes */
@supports (-webkit-touch-callout: none) {
    /* iOS Safari specific fixes */
    .dynamic-section {
        position: relative !important;
        height: 100vh !important;
        height: -webkit-fill-available !important;
    }
    
    .content-wrapper {
        height: 100vh !important;
        height: -webkit-fill-available !important;
    }
}

/* Responsive fixes for orientation changes */
@media screen and (max-width: 768px) {
    .dynamic-section {
        min-height: 100vh;
        min-height: -webkit-fill-available;
    }
    
    .content-wrapper {
        padding: 1rem;
        height: 100vh;
        height: -webkit-fill-available;
    }
    
    .content-container {
        width: 100% !important;
        max-width: 100% !important;
        padding: 0 1rem;
    }
    
    #hero-content {
        position: relative !important;
        top: 35% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        width: 100% !important;
        padding: 0 1rem;
    }
    
    .hero-greeting-title {
        font-size: 0.875rem !important;
        letter-spacing: 3px !important;
    }
    
    .hero-invitation-message {
        font-size: 0.875rem !important;
        line-height: 1.5 !important;
    }
    
    .hero-open-invitation-btn {
        font-size: 0.75rem !important;
        letter-spacing: 3px !important;
        padding: 0.75rem 1.5rem !important;
    }
    
    .couple-names {
        font-size: 0.875rem !important;
        letter-spacing: 0.2em !important;
    }
}

/* Landscape orientation fixes */
@media screen and (max-width: 768px) and (orientation: landscape) {
    .dynamic-section {
        min-height: 100vh;
    }
    
    .content-wrapper {
        height: 100vh;
    }
    
    #hero-content {
        top: 35% !important;
        transform: translate(-50%, -50%) !important;
    }
    
    .hero-invitation-bottom {
        bottom: -150px !important;
    }
}

/* Very small screens */
@media screen and (max-width: 480px) {
    .hero-greeting-title {
        font-size: 0.75rem !important;
        letter-spacing: 2px !important;
    }
    
    .hero-invitation-message {
        font-size: 0.75rem !important;
    }
    
    .hero-open-invitation-btn {
        font-size: 0.625rem !important;
        letter-spacing: 2px !important;
        padding: 0.625rem 1.25rem !important;
    }
    
    /* Mobile RSVP button fixes */
    #sticky-rsvp {
        bottom: 1rem !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        z-index: 9999 !important;
    }
    
    .rsvp-scroll-btn,
    .rsvp-link-btn {
        font-size: 0.75rem !important;
        padding: 0.75rem 1.5rem !important;
        border-radius: 2rem !important;
        text-decoration: none !important;
    }
}

/* Wedding Section Monogram - appears after fly-away animation */
.wedding-section-monogram {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 999;
    color: white;
    /* Removed font-size from container */
    line-height: 1;
    pointer-events: none;
}

.wedding-section-monogram .monogram-combined {
    position: relative;
    z-index: 9999;
    will-change: transform, opacity;
}

.wedding-section-monogram .groom-initial {
    font-family: 'Allura', cursive !important;
    font-size: 60px !important; /* Increased from 48px */
    color: white !important;
}

.wedding-section-monogram .bride-initial {
    font-family: 'Aniyah Personal Use' !important;
    font-size: 40px !important; /* Increased from 32px */
    color: white !important;
    margin-top: -26px !important; /* Adjusted proportionally */
    margin-left: -1px !important; /* Force override */
}

/* RSVP Form Styles */
.rsvp-content {
    max-width: 100%;
}

.rsvp-step {
    transition: all 0.3s ease;
}

.rsvp-step.hidden {
    display: none !important;
}

.rsvp-step.active {
    display: block;
}

/* Multi-step RSVP styles */
.attendance-btn,
.event-btn,
.general-event-btn {
    transition: all 0.3s ease;
    min-height: 50px;
    position: relative;
    z-index: 10;
    cursor: pointer;
    user-select: none;
}

.attendance-btn:hover,
.event-btn:hover,
.general-event-btn:hover {
    background-color: white;
    color: black;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.attendance-btn:active,
.event-btn:active,
.general-event-btn:active {
    transform: translateY(0);
}

.family-member-checkbox label {
    transition: all 0.2s ease;
}

.family-member-checkbox label:hover {
    background-color: rgba(255, 255, 255, 0.25) !important;
    transform: translateY(-1px);
}

.guest-names-section input {
    margin-bottom: 0.5rem;
}

.back-btn {
    transition: all 0.2s ease;
}

.back-btn:hover {
    opacity: 0.7;
    transform: translateX(-2px);
}

.step-actions {
    display: flex;
    flex-direction: column;
    align-items: center;
}

/* Legacy form styles */
.rsvp-form {
    text-align: left;
}

.rsvp-form .form-group {
    position: relative;
}

.rsvp-form input,
.rsvp-form textarea {
    transition: all 0.3s ease;
}

.rsvp-form input:focus,
.rsvp-form textarea:focus {
    background-color: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.7);
}

.event-option {
    transition: all 0.2s ease;
}

.event-option:hover {
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 4px;
    padding: 2px 4px;
}

.event-option input[type="checkbox"] {
    accent-color: white;
    background-color: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.event-option input[type="checkbox"]:checked {
    background-color: white;
    border-color: white;
}

.rsvp-submit-btn {
    transition: all 0.3s ease;
    letter-spacing: 0.1em;
}

.rsvp-submit-btn:hover {
    background-color: white;
    color: black;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.rsvp-submit-btn:active {
    transform: translateY(0);
}

.rsvp-submit-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.rsvp-success {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    padding: 2rem;
    margin-top: 1rem;
}

.hidden {
    display: none !important;
}

/* Mobile adjustments for RSVP form */
@media (max-width: 480px) {
    .rsvp-form input,
    .rsvp-form textarea,
    .attendance-btn,
    .event-btn,
    .general-event-btn {
        font-size: 16px; /* Prevent zoom on iOS */
    }
    
    .event-option {
        margin-bottom: 1rem;
    }
    
    .event-option span {
        margin-left: 0.5rem;
    }
    
    .attendance-btn,
    .event-btn,
    .general-event-btn {
        min-height: 44px; /* Better touch target */
        padding: 12px 24px;
    }
    
    .family-member-checkbox {
        margin-bottom: 0.75rem;
    }
    
    .step-actions {
        gap: 1rem;
    }
}
</style>

<script>
// Force scroll to top on any page load (including refresh)
window.addEventListener('beforeunload', function() {
    window.scrollTo(0, 0);
});

// Also handle page refresh by storing scroll position
if (history.scrollRestoration) {
    history.scrollRestoration = 'manual';
}

// Multi-Section Wedding Invitation with GSAP ScrollTrigger
document.addEventListener('DOMContentLoaded', function() {
    // Set up dynamic viewport height for mobile browsers
    function setViewportHeight() {
        const vh = window.innerHeight * 0.01;
        document.documentElement.style.setProperty('--vh', `${vh}px`);
        
        // Force exact height on sections to eliminate gaps
        const dynamicSection = document.querySelector('.dynamic-section');
        const weddingSection = document.querySelector('#wedding-details');
        
        if (dynamicSection) {
            dynamicSection.style.height = `${window.innerHeight}px`;
            dynamicSection.style.minHeight = `${window.innerHeight}px`;
            dynamicSection.style.maxHeight = `${window.innerHeight}px`;
        }
        
        if (weddingSection) {
            weddingSection.style.height = `${window.innerHeight}px`;
            weddingSection.style.minHeight = `${window.innerHeight}px`;
            weddingSection.style.maxHeight = `${window.innerHeight}px`;
        }
    }
    
    // Set initial viewport height
    setViewportHeight();
    
    // Update on resize and orientation change
    window.addEventListener('resize', setViewportHeight);
    window.addEventListener('orientationchange', () => {
        setTimeout(setViewportHeight, 100);
    });
    
    // Force scroll to top on page load/refresh
    window.scrollTo(0, 0);
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;
    
    // Disable scrolling only on hero section
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
    
    // Check if GSAP is loaded
    if (typeof gsap === 'undefined') {
        console.error('GSAP is not loaded!');
        return;
    }
    
    gsap.registerPlugin(ScrollTrigger, Observer, ScrollToPlugin);
    
    // Get elements
    const heroContent = document.getElementById('hero-content');
    const invitationContent = document.getElementById('invitation-content');
    const backgroundImage = document.getElementById('background-image');
    const backgroundOverlay = document.getElementById('background-overlay');
    const dynamicSection = document.querySelector('.dynamic-section');
    
    // Enhanced device detection for responsive images
    let isMobile = window.innerWidth <= 768;
    let isTablet = window.innerWidth > 768 && window.innerWidth <= 1024;
    let isDesktop = window.innerWidth > 1024;
    
    // Function to get the appropriate image source based on screen size
    function getResponsiveImageSrc(sectionType = 'hero') {
        const screenWidth = window.innerWidth;
        const isPortrait = window.innerHeight > window.innerWidth;
        
        if (sectionType === 'hero') {
            // Hero section responsive images
            if (screenWidth <= 480) {
                // Small mobile
                return '<?php echo function_exists('get_hero_background_image') ? get_hero_background_image(true) : get_template_directory_uri() . '/assets/images/s.jpg'; ?>';
            } else if (screenWidth <= 768) {
                // Large mobile/small tablet
                return '<?php echo function_exists('get_hero_background_image') ? get_hero_background_image(true) : get_template_directory_uri() . '/assets/images/s.jpg'; ?>';
            } else if (screenWidth <= 1024) {
                // Tablet
                return '<?php echo function_exists('get_hero_background_image') ? get_hero_background_image(false) : get_template_directory_uri() . '/assets/images/s.jpg'; ?>';
            } else {
                // Desktop and larger
                return '<?php echo function_exists('get_hero_background_image') ? get_hero_background_image(false) : get_template_directory_uri() . '/assets/images/s.jpg'; ?>';
            }
        }
        
        // Default fallback
        return '<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg';
    }
    
    const initialImageSrc = getResponsiveImageSrc('hero');
    
    // Set the correct image immediately to prevent flash
    backgroundImage.src = initialImageSrc;
    
    // Normal scrolling enabled from start
    // Scroll prevention removed
    
    // Set initial positions for hero content
    gsap.set('.monogram-combined', { y: -window.innerHeight, opacity: 0 });
    gsap.set('.couple-names', { opacity: 0, y: 30 });
    gsap.set('.hero-greeting-title', { opacity: 0, y: 30 });
    gsap.set('.hero-invitation-message', { opacity: 0, y: 30 });
    gsap.set('.hero-open-invitation-btn', { opacity: 0, y: 30 });
    
    // Disable button interaction until fully visible
    const openBtn = document.querySelector('.hero-open-invitation-btn');
    if (openBtn) {
        openBtn.style.pointerEvents = 'none';
    }
    
    // Set initial position for hero monogram
    gsap.set('.monogram-combined', { y: -window.innerHeight, opacity: 0 });
    
    // Set initial positions for invitation content (keep hidden)
    gsap.set('.greeting-title', { opacity: 0, y: 30 });
    gsap.set('.invitation-message', { opacity: 0, y: 30 });
    gsap.set('.open-invitation-btn', { opacity: 0, y: 30 });
    gsap.set('.reveal-text', { opacity: 0, y: 30 });
    
    // Set initial positions for all section elements
    gsap.set('#wedding-details .wedding-title', { opacity: 0, y: 30 });
    gsap.set('#wedding-details .wedding-couple-names', { opacity: 0, y: 30 });
    gsap.set('#wedding-details .wedding-date', { opacity: 0, y: 30 });
    gsap.set('#wedding-details .bible-verse', { opacity: 0, y: 30 });
    gsap.set('#wedding-details .groom-info', { opacity: 0, y: 30 });
    gsap.set('#wedding-details .bride-info', { opacity: 0, y: 30 });
    
    gsap.set('#ceremony-reception .ceremony-intro', { opacity: 0, y: 30 });
    gsap.set('#ceremony-reception .ceremony-title', { opacity: 0, y: 30 });
    gsap.set('#ceremony-reception .ceremony-time', { opacity: 0, y: 30 });
    gsap.set('#ceremony-reception .ceremony-location', { opacity: 0, y: 30 });
    gsap.set('#ceremony-reception .ceremony-divider', { opacity: 0, scaleX: 0 });
    gsap.set('#ceremony-reception .reception-intro', { opacity: 0, y: 30 });
    gsap.set('#ceremony-reception .reception-title', { opacity: 0, y: 30 });
    gsap.set('#ceremony-reception .reception-time', { opacity: 0, y: 30 });
    gsap.set('#ceremony-reception .reception-location', { opacity: 0, y: 30 });
    
    gsap.set('#love-story .love-story-title', { opacity: 0, y: 30 });
    gsap.set('#love-story .video-placeholder', { opacity: 0, y: 30 });
    gsap.set('#love-story .love-quote', { opacity: 0, y: 30 });
    
    gsap.set('#detailed-love-story .detailed-love-story-title', { opacity: 0, y: 30 });
    gsap.set('#detailed-love-story .narrative-opening', { opacity: 0, y: 30 });
    gsap.set('#detailed-love-story .narrative-college', { opacity: 0, y: 30 });
    gsap.set('#detailed-love-story .narrative-malaysia', { opacity: 0, y: 30 });
    gsap.set('#detailed-love-story .narrative-return', { opacity: 0, y: 30 });
    gsap.set('#detailed-love-story .narrative-2020', { opacity: 0, y: 30 });
    gsap.set('#detailed-love-story .narrative-2022', { opacity: 0, y: 30 });
    
    gsap.set('#final-love-story .final-love-story-title', { opacity: 0, y: 30 });
    gsap.set('#final-love-story .narrative-time-kind', { opacity: 0, y: 30 });
    gsap.set('#final-love-story .narrative-2023', { opacity: 0, y: 30 });
    gsap.set('#final-love-story .narrative-august', { opacity: 0, y: 30 });
    gsap.set('#final-love-story .vertical-line', { height: 0, opacity: 0 });
    gsap.set('#final-love-story .narrative-proposal', { opacity: 0, y: 30 });
    gsap.set('#final-love-story .narrative-forever', { opacity: 0, y: 30 });
    gsap.set('#final-love-story .narrative-yes', { opacity: 0, y: 30 });
    gsap.set('#final-love-story .narrative-question', { opacity: 0, y: 30 });
    
    gsap.set('#image-slider .slider-quote-line1', { opacity: 0, y: 30 });
    gsap.set('#image-slider .slider-quote-line2', { opacity: 0, y: 30 });
    gsap.set('#image-slider .slider-quote-line3', { opacity: 0, y: 30 });
    gsap.set('#image-slider .slider-quote-line4', { opacity: 0, y: 30 });
    gsap.set('#image-slider .slider-quote-line5', { opacity: 0, y: 30 });
    gsap.set('#image-slider .slider-quote-line6', { opacity: 0, y: 30 });
    gsap.set('#image-slider .slider-quote-line7', { opacity: 0, y: 30 });
    gsap.set('#image-slider .slider-quote-line8', { opacity: 0, y: 30 });
    gsap.set('#image-slider .slider-image-placeholder', { opacity: 0, scale: 0.9 });
    gsap.set('#image-slider .slider-bottom-text', { opacity: 0, y: 30 });
    
    // Set initial positions for RSVP section elements - visible by default
    gsap.set('#rsvp .rsvp-title', { opacity: 1, y: 0 });
    gsap.set('#rsvp .attendance-question', { opacity: 1, y: 0 });
    gsap.set('#rsvp .attendance-btn', { opacity: 1, y: 0, pointerEvents: 'auto' });
    gsap.set('#rsvp .event-question', { opacity: 1, y: 0 });
    gsap.set('#rsvp .event-btn', { opacity: 1, y: 0, pointerEvents: 'auto' });
    gsap.set('#rsvp .general-event-btn', { opacity: 1, y: 0, pointerEvents: 'auto' });
    gsap.set('#rsvp .guest-list-title', { opacity: 1, y: 0 });
    gsap.set('#rsvp .family-member-checkbox', { opacity: 1, y: 0 });
    gsap.set('#rsvp input', { opacity: 0, y: 30 });
    gsap.set('#rsvp textarea', { opacity: 0, y: 30 });
    gsap.set('#rsvp .rsvp-submit-btn', { opacity: 0, y: 30 });
    
    // Ensure hero content is visible and invitation content is hidden on load
    heroContent.style.display = 'block';
    heroContent.style.opacity = '1';
    invitationContent.style.display = 'none';
    
    // Create the main hero animation timeline
    const heroTimeline = gsap.timeline();
    
    // Step 1: Monogram slides down and becomes visible
    heroTimeline.to('.monogram-combined', {
        duration: 1.5,
        y: 0,
        opacity: 1,
        ease: "power2.out"
    })
    // Step 2: Couple names fade in
    .to('.couple-names', {
        duration: 1,
        opacity: 1,
        y: 0,
        ease: "power2.out"
    }, "+=0.3")
    // Step 3: Wait 5 seconds buffer
    .to({}, { duration: 2.5 })
    // Step 4: Couple names fade out
    .to('.couple-names', {
        duration: 0.8,
        opacity: 0,
        y: -30,
        ease: "power2.out"
    })
    // Step 5: "Dear Mr. John and Mrs. Jane" fades in
    .to('.hero-greeting-title', {
        duration: 1,
        opacity: 1,
        y: 0,
        ease: "power2.out"
    }, "+=0.3")
    // Step 6: Invitation message appears at the bottom
    .to('.hero-invitation-message', {
        duration: 0.8,
        opacity: 1,
        y: 0,
        ease: "power2.out"
    }, "+=0.3")
    // Step 7: Open invitation button fades in
    .to('.hero-open-invitation-btn', {
        duration: 0.8,
        opacity: 1,
        y: 0,
        ease: "power2.out"
    }, "+=0.3")
    // Enable scrolling after animation completes with hidden scrollbars
    .call(() => {
        // Scroll prevention removed - normal scrolling allowed
        
        // Enable button clicking only after it's fully visible
        const openBtn = document.querySelector('.hero-open-invitation-btn');
        if (openBtn) {
            openBtn.style.pointerEvents = 'auto';
        }
    });
    
    // Function to enable scrolling with hidden scrollbars
    // Scroll enabling function removed
    
    // Scroll prevention removed
    
    let currentSection = 'hero'; // 'hero', 'invitation'
    let monogramTransformed = false; // Flag to prevent duplicate monogram animations
    
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
    
    // Scroll-based animation for dynamic content 
    window.addEventListener('scroll', () => {
        // Scroll prevention removed - normal scrolling allowed
        
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        
        // Calculate scroll trigger for other sections (keep hero active)
        const weddingDetailsTrigger = windowHeight * 0.8;
        
        if (scrollY > weddingDetailsTrigger) {
            // User has scrolled past hero, keep current state
        } else {
            // Still in hero section
        }
    });
    
    // ScrollTrigger animations for all individual sections
    
    // Button click handler for open invitation
    const openInvitationBtn = document.querySelector('.hero-open-invitation-btn');
    if (openInvitationBtn) {
        openInvitationBtn.addEventListener('click', function() {
            console.log('🚀 OPEN INVITATION CLICKED - FLY AWAY ANIMATION! 🚀');
            
            // Only run if monogram hasn't been transformed yet
            if (monogramTransformed) return;
            
            const heroMonogram = document.querySelector('.monogram-combined');
            
            // Mark as transformed to prevent duplicate animations
            monogramTransformed = true;
            
            // Enable scrolling when button is clicked
            document.body.style.overflow = 'auto';
            document.documentElement.style.overflow = 'auto';
            
            console.log('🎯 Phase 1: Fading out other content');
            // Phase 1: Fade out all other hero content but keep monogram visible
            gsap.to('.hero-greeting-title, .hero-invitation-message, .hero-open-invitation-btn', {
                duration: 0.5,
                opacity: 0,
                ease: "power2.out"
            });
            
            // Phase 2: Make monogram FLY AWAY - really disappear
            setTimeout(() => {
                console.log('🚁 Phase 2: Monogram flying away completely');
                gsap.to(heroMonogram, {
                    duration: 1.5,
                    y: -window.innerHeight - 200, // Fly way off screen
                    scale: 0.1, // Shrink to almost nothing
                    opacity: 0, // Completely fade out
                    ease: "power2.in",
                    onComplete: function() {
                        console.log('🎬 Phase 3: Hiding hero section');
                        // Hide the entire hero section after monogram flies away
                        document.querySelector('.dynamic-section').style.display = 'none';
                        
                        // Scroll to the wedding details section to ensure it's visible
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                        
                        // Small delay to ensure section is visible before adding monogram
                        setTimeout(() => {
                        
                        console.log('📄 Phase 4: Creating new monogram for wedding section');
                        // Create a new monogram that will slide down from top and stick there
                        const newMonogram = document.createElement('div');
                        newMonogram.className = 'wedding-section-monogram';
                        newMonogram.innerHTML = '<span class="monogram-combined"><span class="groom-initial">d</span><span class="bride-initial">e</span></span>';
                        
                        // Attach to body so it stays fixed at top throughout all sections
                        document.body.appendChild(newMonogram);
                        
                        console.log('⬇️ Phase 5: New monogram sliding down from top');
                        // Set initial position WAY off-screen and tiny scale
                        gsap.set(newMonogram, {
                            y: -window.innerHeight - 100, // Start way above screen
                            opacity: 0,
                            scale: 0.3 // Start very small
                        });
                        
                        console.log('💒 Phase 6: Animating monogram sliding down to fixed position');
                        // Animate the new monogram sliding down from top
                        gsap.to(newMonogram, {
                            duration: 2,
                            y: 0, // Slide to final position
                            opacity: 1, // Fade in
                            scale: 1, // Grow to full size
                            ease: "power2.out",
                            onComplete: function() {
                                console.log('✅ Monogram slide-down animation complete - now fixed at top!');
                                
                                // After monogram slides down, animate the wedding section content
                                console.log('💒 Phase 6: Animating wedding section content');
                                
                                // After monogram slides down, animate the wedding section content
                                const weddingTl = gsap.timeline();
                                weddingTl.to('#wedding-details .wedding-title', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" })
                                        .to('#wedding-details .wedding-couple-names', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                                        .to('#wedding-details .wedding-date', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                                        .to('#wedding-details .bible-verse', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                                        .to('#wedding-details .groom-info', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
                                        .to('#wedding-details .bride-info', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "-=0.4")
                                        .call(() => {
                                            console.log('✅ Animation complete - Wedding section fully loaded!');
                                        });
                            }
                        });
                        
                        }, 200); // End of setTimeout
                    }
                });
            }, 500);
        });
    }
    
    // Wedding Details Section (content only, transition handled above)
    
    // Ceremony & Reception Section
    ScrollTrigger.create({
        trigger: "#ceremony-reception",
        start: "top 100%",
        end: "bottom 20%",
        toggleActions: "play none none reverse",
        onEnter: () => {
            const tl = gsap.timeline();
            tl.to('#ceremony-reception .ceremony-intro', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" })
              .to('#ceremony-reception .ceremony-title', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .ceremony-time', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .ceremony-location', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .ceremony-divider', { duration: 0.8, opacity: 1, scaleX: 1, ease: "power2.out" }, "+=0.2")
              .to('#ceremony-reception .reception-intro', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .reception-title', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .reception-time', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .reception-location', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1");
        },
        onEnterBack: () => {
            const tl = gsap.timeline();
            tl.to('#ceremony-reception .ceremony-intro', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" })
              .to('#ceremony-reception .ceremony-title', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .ceremony-time', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .ceremony-location', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .ceremony-divider', { duration: 0.8, opacity: 1, scaleX: 1, ease: "power2.out" }, "+=0.2")
              .to('#ceremony-reception .reception-intro', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .reception-title', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .reception-time', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
              .to('#ceremony-reception .reception-location', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1");
        }
    });
    
    // Love Story Section
    ScrollTrigger.create({
        trigger: "#love-story",
        start: "top 100%",
        end: "bottom 20%",
        toggleActions: "play none none reverse",
        onEnter: () => {
            const tl = gsap.timeline();
            tl.to('#love-story .love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
              .to('#love-story .video-placeholder', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
              .to('#love-story .love-quote', { duration: 1, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3");
        },
        onEnterBack: () => {
            const tl = gsap.timeline();
            tl.to('#love-story .love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
              .to('#love-story .video-placeholder', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
              .to('#love-story .love-quote', { duration: 1, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3");
        }
    });
    
    // Detailed Love Story Section
    ScrollTrigger.create({
        trigger: "#detailed-love-story",
        start: "top 100%",
        end: "bottom 20%",
        toggleActions: "play none none reverse",
        onEnter: () => {
            const tl = gsap.timeline();
            tl.to('#detailed-love-story .detailed-love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
              .to('#detailed-love-story .narrative-opening', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
              .to('#detailed-love-story .narrative-college', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#detailed-love-story .narrative-malaysia', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#detailed-love-story .narrative-return', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#detailed-love-story .narrative-2020', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#detailed-love-story .narrative-2022', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4");
        },
        onEnterBack: () => {
            const tl = gsap.timeline();
            tl.to('#detailed-love-story .detailed-love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
              .to('#detailed-love-story .narrative-opening', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
              .to('#detailed-love-story .narrative-college', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#detailed-love-story .narrative-malaysia', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#detailed-love-story .narrative-return', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#detailed-love-story .narrative-2020', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#detailed-love-story .narrative-2022', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4");
        }
    });
    
    // Final Love Story Section
    ScrollTrigger.create({
        trigger: "#final-love-story",
        start: "top 100%",
        end: "bottom 20%",
        toggleActions: "play none none reverse",
        onEnter: () => {
            const tl = gsap.timeline();
            tl.to('#final-love-story .final-love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
              .to('#final-love-story .narrative-time-kind', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
              .to('#final-love-story .narrative-2023', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#final-love-story .narrative-august', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#final-love-story .vertical-line', { duration: 1, height: '4rem', opacity: 1, ease: "power2.out" }, "+=0.3")
              .to('#final-love-story .narrative-proposal', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#final-love-story .narrative-forever', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#final-love-story .narrative-yes', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#final-love-story .narrative-question', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4");
        },
        onEnterBack: () => {
            const tl = gsap.timeline();
            tl.to('#final-love-story .final-love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
              .to('#final-love-story .narrative-time-kind', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
              .to('#final-love-story .narrative-2023', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#final-love-story .narrative-august', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#final-love-story .vertical-line', { duration: 1, height: '4rem', opacity: 1, ease: "power2.out" }, "+=0.3")
              .to('#final-love-story .narrative-proposal', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#final-love-story .narrative-forever', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#final-love-story .narrative-yes', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
              .to('#final-love-story .narrative-question', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4");
        }
    });
    
    // Image Slider Section
    ScrollTrigger.create({
        trigger: "#image-slider",
        start: "top 100%",
        end: "bottom 20%",
        toggleActions: "play none none reverse",
        onEnter: () => {
            const tl = gsap.timeline();
            tl.to('#image-slider .slider-quote-line1', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" })
              .to('#image-slider .slider-quote-line2', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line3', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line4', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line5', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
              .to('#image-slider .slider-quote-line6', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line7', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line8', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-image-placeholder', { duration: 1, opacity: 1, scale: 1, ease: "power2.out" }, "+=0.4")
              .to('#image-slider .slider-bottom-text', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3");
        },
        onEnterBack: () => {
            const tl = gsap.timeline();
            tl.to('#image-slider .slider-quote-line1', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" })
              .to('#image-slider .slider-quote-line2', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line3', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line4', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line5', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
              .to('#image-slider .slider-quote-line6', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line7', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-quote-line8', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
              .to('#image-slider .slider-image-placeholder', { duration: 1, opacity: 1, scale: 1, ease: "power2.out" }, "+=0.4")
              .to('#image-slider .slider-bottom-text', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3");
        }
    });
    
    // RSVP Section - No ScrollTrigger, elements visible by default
    
    // Button click animation and functionality - improved smooth transition
    const heroOpenInvitationBtn = document.querySelector('.hero-open-invitation-btn');
    if (heroOpenInvitationBtn) {
        heroOpenInvitationBtn.addEventListener('click', function(e) {
            // Prevent multiple clicks during animation
            if (monogramTransformed) return;
            
            // Disable button to prevent multiple clicks
            e.target.style.pointerEvents = 'none';
            
            // Smooth button press animation - no stutter
            gsap.to(e.target, {
                scale: 0.96,
                duration: 0.1,
                ease: "power1.out",
                onComplete: () => {
                    // Return to normal size smoothly
                    gsap.to(e.target, {
                        scale: 1,
                        duration: 0.2,
                        ease: "back.out(1.2)",
                        onComplete: () => {
                            // Mark as transformed to prevent ScrollTrigger conflicts
                            monogramTransformed = true;
                            
                            // Start the smooth transition immediately
                            transitionToWeddingDetails();
                        }
                    });
                }
            });
        });
    }
    
    // Function to smoothly transition to wedding details section
    function transitionToWeddingDetails() {
        // No scroll blocking during transition - scroll stays enabled
        
        const heroMonogram = document.querySelector('.monogram-combined');
        const heroContent = document.getElementById('hero-content');
        const contentWrapper = document.querySelector('.content-wrapper');
        const weddingDetailsSection = document.getElementById('wedding-details');
        
        // Create smooth transition timeline - simplified and improved
        const transitionTl = gsap.timeline();
        
        // No automatic scroll enabling - only on button click
        
        // Step 1: Smooth scale down while keeping centered
        transitionTl.to(heroMonogram, {
            duration: 1.5,
            scale: 0.5,
            ease: "power2.inOut"
        })
        // Step 2: Fade out other content smoothly
        .to('.hero-greeting-title, .hero-invitation-message, .hero-open-invitation-btn, .couple-names', {
            duration: 1.0,
            opacity: 0,
            y: -20,
            ease: "power2.inOut",
            stagger: 0.1
        }, "-=1.2")
        // Step 3: Move monogram to top center position
        .to(heroMonogram, {
            duration: 1.2,
            y: -window.innerHeight * 0.35,
            scale: 0.4,
            ease: "power2.inOut"
        }, "-=0.8")
        // Step 4: Create the fixed positioned small monogram
        .call(() => {
            // Create new small monogram for the header
            const smallMonogram = heroMonogram.cloneNode(true);
            smallMonogram.classList.add('small-monogram-fixed');
            smallMonogram.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%) scale(0.3);
                z-index: 1000;
                opacity: 0;
            `;
            document.body.appendChild(smallMonogram);
            
            // Fade in the new small monogram
            gsap.to(smallMonogram, {
                duration: 0.8,
                opacity: 1,
                ease: "power2.out"
            });
            
            // Hide the original monogram
            gsap.to(heroMonogram, {
                duration: 0.5,
                opacity: 0,
                ease: "power2.out"
            });
        })
        // Step 5: Change background and show wedding details
        .call(() => {
            changeBackgroundImage('<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('wedding_details') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>');
        })
        .to(weddingDetailsSection, {
            duration: 1.0,
            opacity: 1,
            ease: "power2.out"
        });
    }
    
    // Function to animate wedding details content
    function animateWeddingDetailsContent() {
        const detailsTl = gsap.timeline();
        detailsTl.to('#wedding-details .wedding-title', { 
            duration: 0.8, 
            opacity: 1, 
            y: 0, 
            ease: "power2.out" 
        })
        .to('#wedding-details .wedding-couple-names', { 
            duration: 0.8, 
            opacity: 1, 
            y: 0, 
            ease: "power2.out" 
        }, "+=0.05")
        .to('#wedding-details .wedding-date', { 
            duration: 0.6, 
            opacity: 1, 
            y: 0, 
            ease: "power2.out" 
        }, "+=0.05")
        .to('#wedding-details .bible-verse', { 
            duration: 0.8, 
            opacity: 1, 
            y: 0, 
            ease: "power2.out" 
        }, "+=0.1")
        .to('#wedding-details .groom-info', { 
            duration: 0.6, 
            opacity: 1, 
            y: 0, 
            ease: "power2.out" 
        }, "+=0.05")
        .to('#wedding-details .bride-info', { 
            duration: 0.6, 
            opacity: 1, 
            y: 0, 
            ease: "power2.out" 
        }, "+=0.05")
        .call(() => {
            // Force show the sticky RSVP button
            const stickyRSVP = document.getElementById('sticky-rsvp');
            if (stickyRSVP) {
                console.log('Forcing RSVP button visible after wedding details load');
                gsap.set(stickyRSVP, { opacity: 1 });
                stickyRSVP.style.pointerEvents = 'auto';
                stickyRSVP.style.display = 'flex';
            }
        }, null, "+=0.2");
    }
    
    // Sticky RSVP Button functionality - Show from wedding details (2nd page) onwards
    const stickyRSVP = document.getElementById('sticky-rsvp');
    
    // Force show RSVP on mobile with multiple triggers
    function forceShowRSVP() {
        if (stickyRSVP) {
            console.log('Forcing RSVP button visible');
            stickyRSVP.style.opacity = '1';
            stickyRSVP.style.pointerEvents = 'auto';
            stickyRSVP.style.display = 'block';
            stickyRSVP.style.visibility = 'visible';
            
            // Double check with GSAP
            gsap.set(stickyRSVP, { 
                opacity: 1,
                display: 'block',
                visibility: 'visible'
            });
        }
    }
    
    // Show RSVP from wedding details section onwards - SIMPLE VERSION
    ScrollTrigger.create({
        trigger: "#wedding-details",
        start: "top 95%",
        onEnter: () => {
            console.log('Wedding details in view - ensuring RSVP button visible');
            forceShowRSVP();
        }
    });
    
    // Additional mobile trigger - force show RSVP after any scroll past hero
    ScrollTrigger.create({
        trigger: "#wedding-details",
        start: "top 100%",
        onEnter: () => {
            forceShowRSVP();
        }
    });
    
    // Mobile specific - show RSVP after page transition
    setTimeout(() => {
        if (window.scrollY > window.innerHeight * 0.5) {
            forceShowRSVP();
        }
    }, 3000);
    
    // Click handler for sticky RSVP button
    const rsvpScrollBtn = document.querySelector('.rsvp-scroll-btn');
    if (rsvpScrollBtn) {
        rsvpScrollBtn.addEventListener('click', function() {
            gsap.to(window, {
                duration: 1.5,
                scrollTo: {
                    y: "#rsvp",
                    offsetY: 0
                },
                ease: "power2.inOut"
            });
        });
    }
    
    // Link buttons don't need JavaScript as they use regular navigation
    
    // Multi-step RSVP Functionality
    
    // Global variables for RSVP data
    let familyRsvpData = {
        attendance: null,
        selectedEvents: [],
        attendingMembers: [],
        dietaryRequirements: '',
        additionalNotes: ''
    };
    
    let generalRsvpData = {
        selectedEvents: [],
        guestInfo: {},
        guestNames: [],
        dietaryRequirements: '',
        additionalNotes: ''
    };
    
    // Family RSVP Functions
    function showFamilyStep(stepNumber) {
        // Hide all steps
        const allSteps = document.querySelectorAll('#family-rsvp-container .rsvp-step');
        allSteps.forEach(step => {
            step.classList.add('hidden');
            step.classList.remove('active');
        });
        
        // Show target step with animation
        const targetStep = document.getElementById(`family-step-${stepNumber}`);
        if (targetStep) {
            targetStep.classList.remove('hidden');
            targetStep.classList.add('active');
            
            // Animate step transition
            gsap.fromTo(targetStep, 
                { opacity: 0, y: 20 },
                { opacity: 1, y: 0, duration: 0.5, ease: "power2.out" }
            );
        }
    }
    
    function initializeFamilyRsvp() {
        // Check if family data exists and show teapai option if family category is "family"
        if (window.weddingFamilyData && window.weddingFamilyData.relationship_type === 'Family') {
            const teapaiOption = document.getElementById('teapai-option');
            if (teapaiOption) {
                teapaiOption.classList.remove('hidden');
            }
        }
        
        // Populate family members list
        if (window.weddingFamilyMembers && window.weddingFamilyMembers.length > 0) {
            const familyMembersList = document.getElementById('family-members-list');
            if (familyMembersList) {
                familyMembersList.innerHTML = '';
                window.weddingFamilyMembers.forEach((member, index) => {
                    const memberDiv = document.createElement('div');
                    memberDiv.className = 'family-member-checkbox flex items-center justify-center';
                    memberDiv.innerHTML = `
                        <label class="flex items-center text-white text-2xs xs:text-xs cursor-pointer bg-white/10 backdrop-blur-sm border border-white/30 rounded px-4 py-3 hover:bg-white/20 transition-all duration-300 w-full max-w-xs">
                            <input type="checkbox" 
                                   name="family_members[]" 
                                   value="${index}" 
                                   class="mr-3 w-4 h-4 text-white bg-white/10 border-white/30 rounded focus:ring-white/50 focus:ring-2">
                            <span class="flex-1 text-center">${member}</span>
                        </label>
                    `;
                    familyMembersList.appendChild(memberDiv);
                });
            }
        }
    }
    
    // General RSVP Functions
    function showGeneralStep(stepNumber) {
        document.querySelectorAll('#general-rsvp-container .rsvp-step').forEach(step => {
            step.classList.add('hidden');
            step.classList.remove('active');
        });
        
        const targetStep = document.getElementById(`general-step-${stepNumber}`);
        if (targetStep) {
            targetStep.classList.remove('hidden');
            targetStep.classList.add('active');
        }
    }
    
    function updateGeneralGuestNames() {
        const guestCount = parseInt(document.getElementById('general-guest-count').value);
        const guestNamesContainer = document.getElementById('general-guest-names');
        
        guestNamesContainer.innerHTML = '';
        
        for (let i = 1; i <= guestCount; i++) {
            const inputDiv = document.createElement('div');
            inputDiv.innerHTML = `
                <input type="text" 
                       name="guest_names[]" 
                       placeholder="Guest ${i} Name" 
                       required 
                       class="w-full px-3 xs:px-4 py-2 xs:py-3 bg-white/10 backdrop-blur-sm border border-white/30 rounded text-white placeholder-white/70 text-2xs xs:text-xs focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent">
            `;
            guestNamesContainer.appendChild(inputDiv);
        }
    }
    
    // Initialize multi-step RSVP
    document.addEventListener('DOMContentLoaded', function() {
        // Check what RSVP containers exist
        const familyContainer = document.getElementById('family-rsvp-container');
        const generalContainer = document.getElementById('general-rsvp-container');
        
        // Initialize family RSVP if on family page
        if (familyContainer) {
            // First initialize family data
            initializeFamilyRsvp();
            
            // Attach event listeners with debugging
            const attendanceBtns = document.querySelectorAll('.attendance-btn');
            console.log('Found attendance buttons:', attendanceBtns.length);
            
            attendanceBtns.forEach((btn, index) => {
                console.log('Attaching listener to button', index, btn.textContent.trim());
                btn.style.border = '2px solid red'; // Debug: make buttons obvious
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Button clicked:', this.dataset.answer, this.textContent.trim());
                    const answer = this.dataset.answer;
                    familyRsvpData.attendance = answer;
                    
                    if (answer === 'yes') {
                        console.log('Going to step 2');
                        showFamilyStep(2);
                    } else {
                        console.log('Submitting decline');
                        submitFamilyRsvp();
                    }
                });
            });
            
            // Family Step 2: Event selection
            document.querySelectorAll('.event-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const event = this.dataset.event;
                    
                    if (event === 'both') {
                        // Select all available events
                        familyRsvpData.selectedEvents = ['church', 'reception'];
                        if (window.weddingFamilyData && window.weddingFamilyData.relationship_type === 'Family') {
                            familyRsvpData.selectedEvents.push('teapai');
                        }
                    } else {
                        familyRsvpData.selectedEvents = [event];
                    }
                    
                    showFamilyStep(3);
                });
            });
            
            // Family Step 3: Submit
            document.getElementById('family-submit-btn').addEventListener('click', function() {
                // Collect attending family members
                const checkedMembers = document.querySelectorAll('#family-members-list input[type="checkbox"]:checked');
                familyRsvpData.attendingMembers = Array.from(checkedMembers).map(cb => cb.value);
                
                // Collect additional info
                familyRsvpData.dietaryRequirements = document.getElementById('family-dietary-requirements').value;
                familyRsvpData.additionalNotes = document.getElementById('family-additional-notes').value;
                
                submitFamilyRsvp();
            });
        }
        
        // Initialize general RSVP
        if (document.getElementById('general-rsvp-container')) {
            // General Step 1: Event selection
            document.querySelectorAll('.general-event-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const event = this.dataset.event;
                    
                    if (event === 'both') {
                        generalRsvpData.selectedEvents = ['church', 'reception'];
                    } else {
                        generalRsvpData.selectedEvents = [event];
                    }
                    
                    showGeneralStep(2);
                });
            });
            
            // General guest count change
            document.getElementById('general-guest-count').addEventListener('change', updateGeneralGuestNames);
            
            // Initialize with 1 guest
            updateGeneralGuestNames();
            
            // General Step 2: Submit
            document.getElementById('general-submit-btn').addEventListener('click', function() {
                // Collect guest info
                generalRsvpData.guestInfo.name = document.getElementById('general-guest-name').value;
                generalRsvpData.guestInfo.email = document.getElementById('general-guest-email').value;
                generalRsvpData.guestInfo.count = document.getElementById('general-guest-count').value;
                
                // Collect guest names
                const guestNameInputs = document.querySelectorAll('#general-guest-names input[name="guest_names[]"]');
                generalRsvpData.guestNames = Array.from(guestNameInputs).map(input => input.value);
                
                // Collect additional info
                generalRsvpData.dietaryRequirements = document.getElementById('general-dietary-requirements').value;
                generalRsvpData.additionalNotes = document.getElementById('general-additional-notes').value;
                
                submitGeneralRsvp();
            });
        }
    });
    
    // Submit functions
    function submitFamilyRsvp() {
        const submitBtn = document.getElementById('family-submit-btn');
        if (submitBtn) {
            submitBtn.textContent = 'SUBMITTING...';
            submitBtn.disabled = true;
        }
        
        // Prepare data for submission
        const formData = {
            action: 'wedding_rsvp_submit',
            guest_id: window.weddingMainGuest ? window.weddingMainGuest.id : null,
            attendance: familyRsvpData.attendance,
            events: familyRsvpData.selectedEvents,
            attending_members: familyRsvpData.attendingMembers,
            dietary_requirements: familyRsvpData.dietaryRequirements,
            additional_notes: familyRsvpData.additionalNotes,
            nonce: '<?php echo wp_create_nonce('wedding_rsvp_nonce'); ?>'
        };
        
        // Submit via AJAX
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showFamilyStep('success');
                document.getElementById('family-rsvp-success').classList.remove('hidden');
            } else {
                alert('Error: ' + (data.data || 'Something went wrong. Please try again.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.textContent = 'SUBMIT RSVP';
                submitBtn.disabled = false;
            }
        });
    }
    
    function submitGeneralRsvp() {
        const submitBtn = document.getElementById('general-submit-btn');
        if (submitBtn) {
            submitBtn.textContent = 'SUBMITTING...';
            submitBtn.disabled = true;
        }
        
        // Prepare data for submission
        const formData = {
            action: 'wedding_general_rsvp_submit',
            guest_name: generalRsvpData.guestInfo.name,
            guest_email: generalRsvpData.guestInfo.email,
            guest_count: generalRsvpData.guestInfo.count,
            guest_names: generalRsvpData.guestNames,
            events: generalRsvpData.selectedEvents,
            dietary_requirements: generalRsvpData.dietaryRequirements,
            additional_notes: generalRsvpData.additionalNotes,
            nonce: '<?php echo wp_create_nonce('wedding_rsvp_nonce'); ?>'
        };
        
        // Submit via AJAX
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('general-step-2').classList.add('hidden');
                document.getElementById('general-rsvp-success').classList.remove('hidden');
            } else {
                alert('Error: ' + (data.data || 'Something went wrong. Please try again.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.textContent = 'SUBMIT RSVP';
                submitBtn.disabled = false;
            }
        });
    }
    
    // Enhanced responsive resize handler
    let resizeTimeout;
    window.addEventListener('resize', function() {
        // Debounce resize events
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            const currentWidth = window.innerWidth;
            const newIsMobile = currentWidth <= 768;
            const newIsTablet = currentWidth > 768 && currentWidth <= 1024;
            const newIsDesktop = currentWidth > 1024;
            
            // Only change image if device type actually changed
            if (newIsMobile !== isMobile || newIsTablet !== isTablet || newIsDesktop !== isDesktop) {
                const newSrc = getResponsiveImageSrc('hero');
                
                if (backgroundImage && backgroundImage.src !== newSrc) {
                    backgroundImage.src = newSrc;
                }
                
                // Update device type variables
                isMobile = newIsMobile;
                isTablet = newIsTablet;
                isDesktop = newIsDesktop;
                
                console.log(`Responsive image updated for ${newIsMobile ? 'mobile' : newIsTablet ? 'tablet' : 'desktop'} (${currentWidth}px)`);
            }
        }, 100);
    });
});
</script>

<?php get_footer(); ?>
