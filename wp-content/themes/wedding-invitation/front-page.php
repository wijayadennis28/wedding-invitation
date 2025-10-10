<?php
// Template helpers are now automatically loaded when the plugin is active
// No need for manual inclusion since the plugin loads them in its init method

get_header(); ?>

<!-- Single Dynamic Div -->
<div class="dynamic-section" style="height: 100px !important;">
    <!-- Dynamic Background Image -->
    <div class="background-container" style="background-image: url('<?php echo get_template_directory_uri(); ?>/assets/images/s.jpg'); background-size: cover;">
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
                    // Debug: Check function availability
                    if (!function_exists('is_wedding_family_page')) {
                        echo '<!-- DEBUG: is_wedding_family_page function NOT found -->';
                        echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                    } elseif (!function_exists('get_wedding_family_data')) {
                        echo '<!-- DEBUG: get_wedding_family_data function NOT found -->';
                        echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                    } elseif (!function_exists('format_smart_wedding_greeting')) {
                        echo '<!-- DEBUG: format_smart_wedding_greeting function NOT found -->';
                        echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                    } elseif (!is_wedding_family_page()) {
                        echo '<!-- DEBUG: Not a wedding family page -->';
                        echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                    } else {
                        echo '<!-- DEBUG: All functions found, proceeding with smart greeting -->';
                        try {
                            $family_data = get_wedding_family_data();
                            
                            echo '<!-- DEBUG: Family data: ' . ($family_data ? 'EXISTS' : 'NULL') . ' -->';
                            
                            if ($family_data) {
                                echo '<!-- DEBUG: Calling format_smart_wedding_greeting with single parameter -->';
                                echo format_smart_wedding_greeting($family_data);
                            } else {
                                echo '<!-- DEBUG: Missing data, using fallback -->';
                                $guest_names = function_exists('get_formatted_guest_names') ? get_formatted_guest_names() : '';
                                if ($guest_names) {
                                    echo 'DEAR ' . strtoupper($guest_names) . ',';
                                } else {
                                    echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                                }
                            }
                        } catch (Exception $e) {
                            echo '<!-- DEBUG: Exception caught: ' . $e->getMessage() . ' -->';
                            echo 'DEAR OUR BELOVED FAMILY & FRIENDS,';
                        }
                    }
                    ?>
                </h2>
                
                <!-- Invitation message at bottom (initially hidden) -->
                <div class="hero-invitation-bottom" style="position: absolute; bottom: -180px; left: 50%; transform: translateX(-50%); width: 100%; text-align: center;">
                    <p class="hero-invitation-message text-xs xs:text-sm sm:text-base md:text-lg text-white leading-relaxed mb-4 xs:mb-6 md:mb-8 max-w-xs xs:max-w-sm sm:max-w-md mx-auto px-3">
                        You are warmly invited to join us on our wedding day.<br>
                        We apologise for any misspellings of names or titles.
                    </p>
                    
                    <button id="hero-open-invitation-btn" class="hero-open-invitation-btn bg-transparent border border-white text-white px-4 xs:px-6 sm:px-8 py-2 xs:py-3 text-xs xs:text-sm font-light tracking-wider xs:tracking-widest hover:bg-white hover:text-black transition-all duration-300">
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
                    <button id="open-invitation-btn" class="open-invitation-btn bg-transparent border border-white text-white px-8 py-3 text-sm font-light tracking-widest hover:bg-white hover:text-black transition-all duration-300">
                        OPEN THE INVITATION
                    </button>
                    <p class="reveal-text text-white text-sm mt-4 italic font-light">
                        Or slide to reveal
                    </p>
                </div>
            </div>
            
            <!-- NEW: Single Wedding Details Container - MOVED TO RSVP SECTION -->
            <!-- All 6 sections now integrated into RSVP section below -->
            
            
        </div>
    </div>
    
</div>

<!-- RSVP Div with Integrated Wedding Sections -->
<div id="rsvp" class="rsvp-section relative" style="margin: 0; padding: 0;">
    <!-- Background Image Container (dynamic, changes based on scroll) -->
    <div class="absolute inset-0 z-0" id="rsvp-background-container">
        <img id="rsvp-background-image" src="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('wedding_details') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" alt="Wedding" class="w-full h-full object-cover responsive-bg-image" loading="lazy" decoding="async">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 to-black/70"></div>
    </div>
    
    <!-- Scrollable Container for All Wedding Content + RSVP -->
    <div class="wedding-and-rsvp-wrapper relative z-10" style="height: 100vh; height: 100dvh; overflow-y: auto; overflow-x: hidden; scroll-behavior: smooth; -webkit-overflow-scrolling: touch; scroll-snap-type: y mandatory;">
        
        <!-- Section 1: Wedding Details -->
        <div class="wedding-content-section" data-section="wedding-details" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('wedding_details') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" style="min-height: auto; padding: 4rem 1.5rem; display: flex; align-items: center; justify-content: center; scroll-snap-align: start; scroll-snap-stop: always;">
            <div class="section-content max-w-lg mx-auto text-center">
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
                <div class="wedding-info-section !mt-12 !xs:mt-16 !md:mt-20 mb-6 xs:mb-8 md:mb-12 text-center">
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

        <!-- Section 2: Ceremony & Reception -->
        <div class="wedding-content-section" data-section="ceremony-reception" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('ceremony_reception') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" style="min-height: auto; padding: 4rem 1.5rem; display: flex; align-items: center; justify-content: center; scroll-snap-align: start; scroll-snap-stop: always;">
            <div class="section-content max-w-lg mx-auto text-center">
                <?php 
                // Check if this is a family page and get invitation details
                $show_church = true; // Default: show both for non-guest list users
                $show_reception = true;
                
                if (function_exists('is_wedding_family_page') && is_wedding_family_page()) {
                    $family_data = function_exists('get_wedding_family_data') ? get_wedding_family_data() : null;
                    
                    if ($family_data && isset($family_data->invitations)) {
                        // Check specific invitations for family users
                        $show_church = isset($family_data->invitations->church) && $family_data->invitations->church->invited;
                        $show_reception = isset($family_data->invitations->reception) && $family_data->invitations->reception->invited;
                        
                        // If neither is specifically invited, show both as fallback
                        if (!$show_church && !$show_reception) {
                            $show_church = true;
                            $show_reception = true;
                        }
                    }
                }
                ?>
                
                <?php if ($show_church): ?>
                <!-- Ceremony Section -->
                <div class="ceremony-section <?php echo ($show_church && $show_reception) ? 'mb-4 xs:mb-6 md:mb-8' : ''; ?>">
                    <p class="ceremony-intro text-2xs xs:text-xs md:text-sm text-white font-light mb-1 xs:mb-2">We request the blessing of your presence as we are united in</p>
                    <h2 class="ceremony-title text-base xs:text-lg md:text-xl tracking-widest mb-1 xs:mb-2 text-white font-semibold">HOLY MATRIMONY</h2>
                    <div class="ceremony-time text-xs xs:text-sm md:text-base tracking-wider mb-3 xs:mb-4 text-white font-medium">9 AM ONWARD</div>
                    <div class="ceremony-location mb-4 xs:mb-6 text-white">
                        <div class="ceremony-place font-semibold tracking-widest text-xs xs:text-sm md:text-lg">GEREJA KATOLIK<br>SANTO LAURENSIUS</div>
                        <div class="ceremony-address text-2xs xs:text-xs md:text-sm font-light italic mt-1 xs:mt-2 mb-2 xs:mb-3">Jl. Sutera Utama No. 2, Alam Sutera<br>Tangerang, Banten 15326</div>
                        <a href="https://maps.app.goo.gl/wWV4HAQFGC6D9Xy76" target="_blank" class="ceremony-map-link text-2xs xs:text-xs underline text-white tracking-wider">VIEW MAP</a>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if ($show_church && $show_reception): ?>
                <!-- Divider between sections -->
                <hr class="ceremony-divider my-4 xs:my-6 md:my-8 border-white/30">
                <?php endif; ?>
                
                <?php if ($show_reception): ?>
                <!-- Reception Section -->
                <div class="reception-section">
                    <p class="reception-intro text-2xs xs:text-xs md:text-sm text-white font-light mb-1 xs:mb-2">We request the pleasure of your company at our</p>
                    <h2 class="reception-title text-base xs:text-lg md:text-xl font-light text-white tracking-widest mb-1 xs:mb-2">EVENING RECEPTION</h2>
                    <div class="reception-time text-xs xs:text-sm md:text-base text-white font-semibold tracking-wider mb-3 xs:mb-4">6 PM ONWARD</div>
                    <div class="reception-location text-white mb-4 xs:mb-6">
                        <div class="reception-place font-semibold tracking-widest text-xs xs:text-sm md:text-lg">JHL SOLITAIRE</div>
                        <div class="reception-address text-2xs xs:text-xs md:text-sm font-light italic mt-1 xs:mt-2 mb-2 xs:mb-3">Jl. Gading Serpong Boulevard,<br>Blok S no. 5, Gading Serpong,<br>Tangerang, Banten 15810</div>
                        <a href="https://maps.app.goo.gl/xPCwuyatC8ghgDd79" target="_blank" class="reception-map-link text-2xs xs:text-xs underline text-white tracking-wider">VIEW MAP</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section 3: Love Story -->
        <div class="wedding-content-section" data-section="love-story" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('love_story') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" style="min-height: auto; padding: 4rem 1.5rem; display: flex; align-items: center; justify-content: center; scroll-snap-align: start; scroll-snap-stop: always;">
            <div class="section-content max-w-lg mx-auto text-center">
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

        <!-- Section 4: Detailed Love Story -->
        <div class="wedding-content-section" data-section="detailed-love-story" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('detailed_love_story') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" style="min-height: auto; padding: 4rem 1.5rem; display: flex; align-items: center; justify-content: center; scroll-snap-align: start; scroll-snap-stop: always;">
            <div class="section-content max-w-lg mx-auto text-center">
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

        <!-- Section 5: Final Love Story -->
        <div class="wedding-content-section" data-section="final-love-story" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('final_love_story') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" style="min-height: 80vh; min-height: 80dvh; padding: 4rem 1.5rem; display: flex; align-items: center; justify-content: center; scroll-snap-align: start; scroll-snap-stop: always;">
            <div class="section-content max-w-lg mx-auto text-center">
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

        <!-- Section 6: Image Slider -->
        <div class="wedding-content-section" data-section="image-slider" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('image_slider') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" style="min-height: auto; padding: 4rem 1.5rem; display: flex; align-items: center; justify-content: center; scroll-snap-align: start; scroll-snap-stop: always;">
            <div class="section-content max-w-lg mx-auto text-center">
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
                <!-- <div class="slider-bottom-quote mb-6 xs:mb-8">
                    <p class="slider-bottom-text text-2xs xs:text-xs sm:text-sm md:text-base text-white font-light italic leading-relaxed text-center">
                        We said yes, <strong>will you say yes to us?</strong>
                    </p>
                </div> -->
            </div>
        </div>

        <!-- Section 7: RSVP Form -->
        <div class="rsvp-form-section" data-section="rsvp-form" data-bg-image="<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('rsvp') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>" style="min-height: auto; padding: 4rem 1.5rem; display: flex; align-items: center; justify-content: center; scroll-snap-align: start; scroll-snap-stop: always;">
            <div class="max-w-xs xs:max-w-sm sm:max-w-lg md:max-w-2xl mx-auto text-center">
            
            <?php if (function_exists('is_wedding_family_page') && is_wedding_family_page()): ?>
                <!-- Family-specific Multi-step RSVP Form -->
                <div id="family-rsvp-container" class="rsvp-content">
                    <!-- Step 1: Will You Be Attending -->
                    <div id="family-step-1" class="rsvp-step active">
                        <h2 class="rsvp-title text-lg xs:text-xl md:text-2xl font-medium text-white mb-2 xs:mb-3 md:mb-4 tracking-wider">
                            We said yes, will you say yes to us?
                        </h2>
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
                            We said yes, will you say yes to us?
                        </h2>
                        <h3 class="guest-list-title text-sm xs:text-base md:text-lg text-white mb-2 xs:mb-3 font-light tracking-wide">
                            GUEST LIST
                        </h3>
                        <p class="guest-list-subtitle text-2xs xs:text-xs text-white/70 mb-6 xs:mb-8 font-light italic">
                            Select who will be attending
                        </p>
                        
                        <div id="family-members-list" class="guest-checkboxes mb-6 xs:mb-8 space-y-3 xs:space-y-4">
                            <?php 
                            if (function_exists('is_wedding_family_page') && is_wedding_family_page()) {
                                $family_data = function_exists('get_wedding_family_data') ? get_wedding_family_data() : null;
                                if ($family_data) {
                                    // Get all family members including primary guest
                                    $all_members = [$family_data->primary_guest_name];
                                    
                                    if (!empty($family_data->family_members)) {
                                        $family_members = is_array($family_data->family_members) ? $family_data->family_members : json_decode($family_data->family_members, true);
                                        if ($family_members) {
                                            $all_members = array_merge($all_members, $family_members);
                                        }
                                    }
                                    
                                    // Display each family member as a checkbox
                                    foreach ($all_members as $index => $member_name) {
                                        echo '<div class="family-member-checkbox flex items-center justify-center">';
                                        echo '<label class="flex items-center text-white text-2xs xs:text-xs cursor-pointer bg-white/10 backdrop-blur-sm border border-white/30 rounded px-4 py-3 hover:bg-white/20 transition-all duration-300 w-full max-w-xs">';
                                        echo '<input type="checkbox" name="family_members[]" value="' . esc_attr($member_name) . '" class="mr-3 w-4 h-4 accent-white border-2 border-white/50 rounded bg-transparent checked:bg-white checked:border-white focus:ring-2 focus:ring-white/50 focus:outline-none">';
                                        echo '<span class="flex-1 text-center">' . esc_html($member_name) . '</span>';
                                        echo '</label>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<p class="text-white/70 text-xs italic">No family data found</p>';
                                }
                            } else {
                                echo '<p class="text-white/70 text-xs italic">Not a family page</p>';
                            }
                            ?>
                        </div>
                        
                        <div class="additional-info-section !mt-8 !xs:mt-10 mb-6 xs:mb-8">
                            <h4 class="additional-info-title text-sm xs:text-base md:text-lg text-white mb-3 xs:mb-4 font-light tracking-wide">
                                Additional Information
                            </h4>
                            <div class="form-group mb-4">
                                <textarea id="family-dietary-requirements" 
                                          name="dietary_requirements" 
                                          placeholder="Dietary requirements or food allergies..." 
                                          rows="3" 
                                          class="w-full px-4 py-3 bg-white/20 backdrop-blur-sm border-2 border-white/50 rounded-lg text-white placeholder-white/80 text-xs xs:text-sm font-light tracking-wider resize-none focus:outline-none focus:ring-2 focus:ring-white/70 focus:border-white hover:bg-white/30 transition-all duration-300"></textarea>
                            </div>
                            <div class="form-group">
                                <textarea id="family-additional-notes" 
                                          name="additional_notes" 
                                          placeholder="Special requests or additional notes..." 
                                          rows="3" 
                                          class="w-full px-4 py-3 bg-white/20 backdrop-blur-sm border-2 border-white/50 rounded-lg text-white placeholder-white/80 text-xs xs:text-sm font-light tracking-wider resize-none focus:outline-none focus:ring-2 focus:ring-white/70 focus:border-white hover:bg-white/30 transition-all duration-300"></textarea>
                            </div>
                        </div>
                        
                        <div class="wishes-section !mt-8 !xs:mt-10 !md:mt-12 mb-6 xs:mb-8">
                            <h4 class="wishes-title text-sm xs:text-base md:text-lg text-white mb-3 xs:mb-4 font-light tracking-wide">
                                Wedding Wishes
                            </h4>
                            <p class="wishes-subtitle text-2xs xs:text-xs text-white/70 mb-4 font-light italic">
                                Share your heartfelt wishes for the happy couple
                            </p>
                            <div class="form-group">
                                <textarea id="family-wedding-wishes" 
                                          name="wedding_wishes" 
                                          placeholder="Write your wedding wishes here..." 
                                          rows="4" 
                                          class="w-full px-8 py-4 bg-white/20 backdrop-blur-sm border-2 border-white/50 rounded-lg text-white placeholder-white/80 text-xs xs:text-sm font-light tracking-wider resize-none focus:outline-none focus:ring-2 focus:ring-white/70 focus:border-white hover:bg-white/30 transition-all duration-300"></textarea>
                            </div>
                        </div>
                        
                        
                        <div class="step-actions space-y-4">
                            <button type="button" id="family-submit-btn" class="submit-btn w-full max-w-sm mx-auto block bg-white/20 backdrop-blur-sm border-2 border-white/50 text-white px-8 py-4 text-xs xs:text-sm font-light tracking-wider hover:bg-white hover:text-black transition-all duration-300 rounded-lg">
                                Submit
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
                    
                    <!-- Decline Message -->
                    <div id="family-rsvp-decline" class="rsvp-step hidden text-center">
                        <div class="thank-you-animation">
                            <h3 class="thank-you-title text-lg xs:text-xl text-white mb-3 xs:mb-4 font-medium tracking-wider">
                                THANK YOU FOR CONFIRMING!
                            </h3>
                            <p class="thank-you-subtitle text-2xs xs:text-xs text-white/90 leading-relaxed italic mb-4 xs:mb-6">
                                You can change your response by<br>
                                visiting or refreshing this page
                            </p>
                            <button type="button" class="back-to-start-btn flex items-center mx-auto text-white text-2xs xs:text-xs font-light tracking-wider hover:text-white/70 transition-all duration-300" onclick="resetFamilyForm()">
                                <i class="fas fa-arrow-left mr-2"></i> BACK
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- General Users Multi-step RSVP Form -->
                <div id="general-rsvp-container" class="rsvp-content">
                    <!-- Step 1: Event Selection (No attendance question) -->
                    <div id="general-step-1" class="rsvp-step active">
                        <h2 class="rsvp-title text-lg xs:text-xl md:text-2xl font-medium text-white mb-2 xs:mb-3 md:mb-4 tracking-wider">
                            We said yes, will you say yes to us?
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
                            We said yes, will you say yes to us?
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
                    
                    <!-- Decline Message -->
                    <div id="general-rsvp-decline" class="rsvp-step hidden text-center">
                        <div class="thank-you-animation">
                            <h3 class="thank-you-title text-lg xs:text-xl text-white mb-3 xs:mb-4 font-medium tracking-wider">
                                THANK YOU FOR CONFIRMING!
                            </h3>
                            <p class="thank-you-subtitle text-2xs xs:text-xs text-white/90 leading-relaxed italic mb-4 xs:mb-6">
                                You can change your response by<br>
                                visiting or refreshing this page
                            </p>
                            <button type="button" class="back-to-start-btn flex items-center mx-auto text-white text-2xs xs:text-xs font-light tracking-wider hover:text-white/70 transition-all duration-300" onclick="resetGeneralForm()">
                                <i class="fas fa-arrow-left mr-2"></i> BACK
                            </button>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            </div>
        </div>
        
    </div>
</div>



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
    margin: 0 !important;
    padding: 0 !important;
    height: 100% !important;
    height: 100vh !important;
    height: 100dvh !important;
    height: -webkit-fill-available !important;
    overflow-x: hidden !important;
    min-height: 100vh !important;
    min-height: 100dvh !important;
    min-height: -webkit-fill-available !important;
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

/* Ensure sections and divs connect seamlessly - NO GAPS */
section, div {
    margin: 0 !important;
    padding: 0 !important;
    display: block;
    position: relative;
}

/* Force exact viewport heights - eliminate all gaps */
.dynamic-section, #wedding-details {
    height: 100vh !important;
    height: 100dvh !important;
    height: calc(var(--vh, 1vh) * 100) !important;
    min-height: 100vh !important;
    min-height: 100dvh !important;
    min-height: calc(var(--vh, 1vh) * 100) !important;
    max-height: 100vh !important;
    max-height: 100dvh !important;
    max-height: calc(var(--vh, 1vh) * 100) !important;
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
    /* Mobile Safari viewport fix */
    height: 100vh;
    height: 100dvh;
    height: -webkit-fill-available;
    min-height: 100vh;
    min-height: 100dvh;
    min-height: -webkit-fill-available;
}

/* Mobile Safari specific viewport fixes */
@supports (-webkit-touch-callout: none) {
    html, body {
        height: -webkit-fill-available !important;
        min-height: -webkit-fill-available !important;
    }
    
    .dynamic-section {
        height: -webkit-fill-available !important;
        min-height: -webkit-fill-available !important;
        max-height: -webkit-fill-available !important;
    }
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

/* RSVP Form Custom Typography */
.rsvp-title {
    font-family: 'Montserrat', sans-serif;
    font-weight: 400;
    letter-spacing: 2px;
}

/* All other RSVP form elements use Playfair Display */
.rsvp-form-section,
.rsvp-content,
.attendance-question,
.event-question,
.guest-list-title,
.guest-info-title,
.guest-list-subtitle,
.attendance-btn,
.event-btn,
.general-event-btn,
.rsvp-submit-btn,
.back-btn,
.family-member-checkbox label,
.thank-you-title,
.thank-you-subtitle,
.rsvp-form input,
.rsvp-form textarea,
.rsvp-form select {
    font-family: 'Playfair Display', serif;
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
        min-height: 100vh !important;
        min-height: 100dvh !important;
        min-height: calc(var(--vh, 1vh) * 100) !important;
        min-height: -webkit-fill-available !important;
        height: 100vh !important;
        height: 100dvh !important;
        height: calc(var(--vh, 1vh) * 100) !important;
        height: -webkit-fill-available !important;
        max-height: 100vh !important;
        max-height: 100dvh !important;
        max-height: calc(var(--vh, 1vh) * 100) !important;
        max-height: -webkit-fill-available !important;
    }
    
    .content-wrapper {
        padding: 1rem;
        height: 100vh !important;
        height: 100dvh !important;
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

/* Wedding Details Container Styles - Single Container with Background Changes */
.wedding-details-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    height: 100dvh;
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.8s ease, visibility 0.8s ease;
}

.wedding-details-container.active {
    opacity: 1;
    visibility: visible;
}

.details-scroll-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

/* Individual content sections in the single container */
.wedding-content-section {
    position: relative;
    min-height: auto;
    padding: 3rem 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.wedding-content-section .section-content {
    max-width: 600px;
    width: 100%;
    text-align: center;
    position: relative;
    z-index: 3;
}

/* Close button for the container */
.close-details-btn {
    position: fixed;
    top: 2rem;
    right: 2rem;
    width: 48px;
    height: 48px;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    transition: all 0.3s ease;
}

.close-details-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: scale(1.1);
}

/* Navigation dots for sections */
.scroll-navigation {
    position: fixed;
    right: 2rem;
    top: 50%;
    transform: translateY(-50%);
    display: flex;
    flex-direction: column;
    gap: 1rem;
    z-index: 1000;
}

/* Mobile adjustments */
@media (max-width: 768px) {
    .close-details-btn {
        top: 1rem;
        right: 1rem;
        width: 40px;
        height: 40px;
    }
    
    .wedding-content-section {
        padding: 2rem 1rem;
    }
    
    .wedding-content-section .section-content {
        max-width: 100%;
        padding: 0 1rem;
    }
}

/* Background container for changing images */
.wedding-details-container::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    z-index: 1;
    transition: opacity 0.8s ease;
}

.wedding-details-container::after {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.7) 100%);
    z-index: 1;
}

/* Smooth content spacing */
.wedding-content-section + .wedding-content-section {
    margin-top: -20vh; /* Overlap sections for closer content */
}

/* Text content adjustments for better readability */
.wedding-content-section h1,
.wedding-content-section h2,
.wedding-content-section h3 {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
}

.wedding-content-section p {
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}

/* Wedding Section Monogram - appears after fly-away animation */
.wedding-section-monogram {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 120px;
    z-index: 999;
    color: white;
    line-height: 1;
    pointer-events: none;
    display: flex;
    justify-content: center;
    align-items: center;
    backdrop-filter: blur(15px);
    -webkit-backdrop-filter: blur(15px);
}

.wedding-section-monogram .monogram-combined {
    position: relative;
    z-index: 1000;
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

/* Thank You Animation Styles */
.thank-you-animation {
    opacity: 0;
    transform: translateY(30px) scale(0.9);
    animation: thankYouFadeIn 0.8s ease-out forwards;
}

.thank-you-title {
    opacity: 0;
    transform: translateY(20px);
    animation: slideInFade 0.6s ease-out 0.2s forwards;
}

.thank-you-subtitle {
    opacity: 0;
    transform: translateY(20px);
    animation: slideInFade 0.6s ease-out 0.4s forwards;
}

.back-to-start-btn {
    opacity: 0;
    transform: translateY(20px);
    animation: slideInFade 0.6s ease-out 0.6s forwards;
}

@keyframes thankYouFadeIn {
    0% {
        opacity: 0;
        transform: translateY(30px) scale(0.9);
    }
    50% {
        opacity: 0.8;
        transform: translateY(-5px) scale(1.02);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes slideInFade {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Add a subtle glow effect to the thank you title */
.thank-you-title {
    text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
    position: relative;
}

.thank-you-title::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    opacity: 0;
    animation: shimmer 2s ease-in-out 0.8s forwards;
}

@keyframes shimmer {
    0% {
        opacity: 0;
        transform: translateX(-100%);
    }
    50% {
        opacity: 1;
        transform: translateX(0%);
    }
    100% {
        opacity: 0;
        transform: translateX(100%);
    }
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

/* Custom checkbox styling for family members */
.family-member-checkbox input[type="checkbox"] {
    appearance: none !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    width: 18px !important;
    height: 18px !important;
    border: 2px solid rgba(255, 255, 255, 0.7) !important;
    border-radius: 4px !important;
    background-color: transparent !important;
    position: relative !important;
    cursor: pointer !important;
    margin-right: 12px !important;
    flex-shrink: 0 !important;
    opacity: 1 !important;
    transform: none !important;
}

.family-member-checkbox input[type="checkbox"]:checked {
    background-color: white !important;
    border-color: white !important;
}

.family-member-checkbox input[type="checkbox"]:checked::after {
    content: '✓' !important;
    position: absolute !important;
    top: -2px !important;
    left: 2px !important;
    color: black !important;
    font-size: 14px !important;
    font-weight: bold !important;
}

.family-member-checkbox input[type="checkbox"]:hover {
    border-color: white !important;
    background-color: rgba(255, 255, 255, 0.1) !important;
}

.family-member-checkbox input[type="checkbox"]:focus {
    outline: 2px solid rgba(255, 255, 255, 0.5) !important;
    outline-offset: 2px !important;
}
</style>

<script>
// Wedding Data Variables for JS
window.templateUri = '<?php echo get_template_directory_uri(); ?>';
window.groomName = '<?php echo get_theme_mod('groom_name', 'Dennis'); ?>';
window.brideName = '<?php echo get_theme_mod('bride_name', 'Emilia'); ?>';
window.isMobile = <?php echo wp_is_mobile() ? 'true' : 'false'; ?>;
window.weddingImages = {
    wedding_details: '<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('wedding_details') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>',
    ceremony_reception: '<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('ceremony_reception') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>',
    rsvp: '<?php echo function_exists('get_wedding_section_image') ? get_wedding_section_image('rsvp') : get_template_directory_uri() . '/assets/images/s.jpg'; ?>'
};

// Set family members data for RSVP form
<?php 
if (function_exists('is_wedding_family_page') && is_wedding_family_page()) {
    $family_data = function_exists('get_wedding_family_data') ? get_wedding_family_data() : null;
    if ($family_data) {
        // Set complete family data for submission
        echo 'window.weddingFamilyData = ' . json_encode($family_data) . ';';
        
        // Set family members list for form display
        if (!empty($family_data->family_members)) {
            $family_members = is_array($family_data->family_members) ? $family_data->family_members : json_decode($family_data->family_members, true);
            if ($family_members) {
                // Add primary guest name to the list
                $all_members = array_merge([$family_data->primary_guest_name], $family_members);
                echo 'window.weddingFamilyMembers = ' . json_encode($all_members) . ';';
            } else {
                echo 'window.weddingFamilyMembers = [' . json_encode($family_data->primary_guest_name) . '];';
            }
        } else {
            echo 'window.weddingFamilyMembers = [' . json_encode($family_data->primary_guest_name) . '];';
        }
    } else {
        echo 'window.weddingFamilyData = null;';
        echo 'window.weddingFamilyMembers = [];';
    }
} else {
    echo 'window.weddingFamilyData = null;';
    echo 'window.weddingFamilyMembers = [];';
}
?>

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
        
        // Additional mobile Safari fix
        if (/iPhone|iPad|iPod/.test(navigator.userAgent)) {
            document.body.style.height = `${window.innerHeight}px`;
            document.documentElement.style.height = `${window.innerHeight}px`;
        }
    }
    
    // Set initial viewport height
    setViewportHeight();
    
    // Update on resize and orientation change
    window.addEventListener('resize', setViewportHeight);
    window.addEventListener('orientationchange', () => {
        setTimeout(() => {
            setViewportHeight();
            // Refresh ScrollTrigger after viewport changes
            if (typeof ScrollTrigger !== 'undefined') {
                ScrollTrigger.refresh();
            }
        }, 100);
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
    
    // RSVP Form Functions
    window.showFamilyStep = function(step) {
        console.log('Showing family step:', step);
        
        // Hide all steps
        document.querySelectorAll('#family-rsvp-container .rsvp-step').forEach(stepEl => {
            stepEl.classList.add('hidden');
            stepEl.classList.remove('active');
        });
        
        // Show the requested step
        const targetStep = document.getElementById(`family-step-${step}`);
        if (targetStep) {
            targetStep.classList.remove('hidden');
            targetStep.classList.add('active');
        }
    };
    
    window.showGeneralStep = function(step) {
        console.log('Showing general step:', step);
        
        // Hide all steps
        document.querySelectorAll('#general-rsvp-container .rsvp-step').forEach(stepEl => {
            stepEl.classList.add('hidden');
            stepEl.classList.remove('active');
        });
        
        // Show the requested step
        const targetStep = document.getElementById(`general-step-${step}`);
        if (targetStep) {
            targetStep.classList.remove('hidden');
            targetStep.classList.add('active');
        }
    };
    
    window.resetFamilyForm = function() {
        // Reset form data
        document.querySelectorAll('#family-rsvp-container input, #family-rsvp-container textarea').forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false;
            } else {
                input.value = '';
            }
        });
        
        // Remove selected states
        document.querySelectorAll('#family-rsvp-container .attendance-btn, #family-rsvp-container .event-btn').forEach(btn => {
            btn.classList.remove('selected', 'bg-white', 'text-black');
        });
        
        // Go back to step 1
        showFamilyStep(1);
    };
    
    window.resetGeneralForm = function() {
        // Reset form data
        document.querySelectorAll('#general-rsvp-container input, #general-rsvp-container textarea, #general-rsvp-container select').forEach(input => {
            if (input.type === 'checkbox' || input.type === 'radio') {
                input.checked = false;
            } else {
                input.value = '';
            }
        });
        
        // Remove selected states
        document.querySelectorAll('#general-rsvp-container .general-event-btn').forEach(btn => {
            btn.classList.remove('selected', 'bg-white', 'text-black');
        });
        
        // Go back to step 1
        showGeneralStep(1);
    };
    
    // Handle family attendance buttons
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('attendance-btn')) {
            const answer = e.target.dataset.answer;
            
            // Remove selection from all attendance buttons
            document.querySelectorAll('.attendance-btn').forEach(btn => {
                btn.classList.remove('selected', 'bg-white', 'text-black');
                btn.classList.add('bg-white/20');
            });
            
            // Add selection to clicked button
            e.target.classList.add('selected', 'bg-white', 'text-black');
            e.target.classList.remove('bg-white/20');
            
            setTimeout(() => {
                if (answer === 'no') {
                    // Store attendance answer
                    familyRsvpData.attendance = 'no';
                    // Directly submit "can't attend" RSVP
                    submitDeclineRsvp();
                } else {
                    // Store attendance answer and proceed to event selection
                    familyRsvpData.attendance = 'yes';
                    showFamilyStep(2);
                }
            }, 800);
        }
        
        // Handle event selection buttons for family
        if (e.target.classList.contains('event-btn')) {
            console.log('🎯 Event button clicked!', e.target);
            console.log('🎯 Button classes:', e.target.classList.toString());
            console.log('🎯 Button data-event:', e.target.dataset.event);
            
            // Remove selection from all event buttons
            document.querySelectorAll('.event-btn').forEach(btn => {
                btn.classList.remove('selected', 'bg-white', 'text-black');
                btn.classList.add('bg-white/10');
            });
            
            // Add selection to clicked button
            e.target.classList.add('selected', 'bg-white', 'text-black');
            e.target.classList.remove('bg-white/10');
            
            // Store selected events in familyRsvpData
            const event = e.target.dataset.event;
            console.log('🎯 Event selected:', event);
            
            if (event === 'both') {
                familyRsvpData.selectedEvents = ['church', 'reception'];
            } else {
                familyRsvpData.selectedEvents = [event];
            }
            
            console.log('📝 Stored selectedEvents:', familyRsvpData.selectedEvents);
            console.log('📝 Full familyRsvpData:', familyRsvpData);
            
            // Continue to guest list
            setTimeout(() => {
                showFamilyStep(3);
            }, 800);
        }
        
        // Handle event selection buttons for general users
        if (e.target.classList.contains('general-event-btn')) {
            // Remove selection from all event buttons
            document.querySelectorAll('.general-event-btn').forEach(btn => {
                btn.classList.remove('selected', 'bg-white', 'text-black');
                btn.classList.add('bg-white/10');
            });
            
            // Add selection to clicked button
            e.target.classList.add('selected', 'bg-white', 'text-black');
            e.target.classList.remove('bg-white/10');
            
            // Continue to guest information
            setTimeout(() => {
                showGeneralStep(2);
            }, 800);
        }
    });
    
    // Ensure hero content is visible and invitation content is hidden on load
    heroContent.style.display = 'block';
    heroContent.style.opacity = '1';
    invitationContent.style.display = 'none';
    
    // Create the main hero animation timeline
    const heroTimeline = gsap.timeline();
    
    console.log('🎬 Starting hero timeline animations...');
    
    // Step 1: Monogram slides down and becomes visible
    heroTimeline.to('.monogram-combined', {
        duration: 1.5,
        y: 0,
        opacity: 1,
        ease: "power2.out",
        onComplete: () => console.log('✅ Monogram animation complete')
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
        ease: "power2.out",
        onComplete: () => console.log('✅ Button animation complete - button should be visible and clickable')
    }, "+=0.3")
    // Enable scrolling after animation completes with hidden scrollbars
    .call(() => {
        // Scroll prevention removed - normal scrolling allowed
        
        // Enable button clicking only after it's fully visible
        const openBtn = document.querySelector('.hero-open-invitation-btn');
        if (openBtn) {
            openBtn.style.pointerEvents = 'auto';
            console.log('✅ Button click enabled - ready for user interaction');
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
    
    // Mobile-specific scroll detection (backup system)
    let isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    let lastScrollTime = 0;
    
    // Enhanced scroll listener for mobile compatibility
    const scrollHandler = () => {
        const now = Date.now();
        if (now - lastScrollTime < 16) return; // Throttle to ~60fps
        lastScrollTime = now;
        
        const scrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        
        // Calculate scroll trigger for other sections
        const weddingDetailsTrigger = windowHeight * 0.8;
        
        if (scrollY > weddingDetailsTrigger) {
            // User has scrolled past hero, keep current state
        } else {
            // Still in hero section
        }
        
        // Mobile fallback animation triggers - not needed for single container
        // All animations are now handled by the wedding details container
    };
    
    // Add scroll listener with passive flag for better mobile performance
    window.addEventListener('scroll', scrollHandler, { passive: true });
    
    // ScrollTrigger animations for all individual sections
    
    // Button click handler for open invitation - Navigate to RSVP Section
    const openInvitationBtn = document.querySelector('.hero-open-invitation-btn');
    if (openInvitationBtn) {
        openInvitationBtn.addEventListener('click', function(e) {
            console.log('🚀 OPEN INVITATION CLICKED - Scrolling to Wedding Sections in RSVP! 🚀');
            
            // Prevent default behavior and stop propagation
            e.preventDefault();
            e.stopPropagation();
            
            // Only run if monogram hasn't been transformed yet
            if (monogramTransformed) return;
            
            const heroMonogram = document.querySelector('.monogram-combined');
            
            // Mark as transformed to prevent duplicate animations
            monogramTransformed = true;
            
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
                        console.log('🎬 Phase 3: Scrolling directly to Wedding Details section');
                        
                        // Enable scrolling again
                        document.body.style.overflow = 'auto';
                        document.documentElement.style.overflow = 'auto';
                        
                        // Find the first wedding section and scroll directly to it
                        const firstWeddingSection = document.querySelector('.wedding-content-section[data-section="wedding-details"]');
                        
                        if (firstWeddingSection) {
                            console.log('📍 Found wedding details section, scrolling to it...');
                            
                            // Use native scroll instead of GSAP for more reliable scrolling
                            firstWeddingSection.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                            
                            console.log('✅ Scrolled to Wedding Details section!');
                        } else {
                            console.error('❌ Could not find wedding details section');
                            // Fallback: scroll to RSVP div
                            const rsvpDiv = document.getElementById('rsvp');
                            if (rsvpDiv) {
                                rsvpDiv.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        }
                        
                        // Create new monogram for wedding sections
                        setTimeout(() => {
                            console.log('📄 Phase 4: Creating new monogram for wedding sections');
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
                                    console.log('✅ Monogram slide-down animation complete - Wedding sections ready!');
                                    // Set global flag that monogram animation is done
                                    window.weddingMonogramComplete = true;
                                    // Trigger any waiting content animations
                                    if (window.triggerContentAnimations) {
                                        window.triggerContentAnimations();
                                    }
                                }
                            });
                        }, 1000); // Wait for scroll to complete
                    }
                });
            }, 500);
        });
    }
    
    // Initialize the wedding details container functionality
    // This will be handled by the separate JS file
    
    // RSVP Section - No ScrollTrigger, elements visible by default
    
    // Sticky RSVP Button functionality
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
        start: "top 80%",
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

    // Mobile-specific ScrollTrigger enhancements
    if (isMobileDevice) {
        console.log('📱 Mobile device detected - applying mobile ScrollTrigger fixes');
        
        // Temporarily enable markers for mobile debugging (remove in production)
        // ScrollTrigger.config({ markers: true });
        
        // Mobile refresh after layout is stable
        setTimeout(() => {
            ScrollTrigger.refresh();
            console.log('📱 Mobile ScrollTrigger refreshed');
        }, 1500);
        
        // Handle mobile viewport changes and orientation
        let mobileRefreshTimeout;
        const mobileRefreshHandler = () => {
            clearTimeout(mobileRefreshTimeout);
            mobileRefreshTimeout = setTimeout(() => {
                ScrollTrigger.refresh();
                console.log('📱 Mobile orientation/resize refresh');
            }, 300);
        };
        
        window.addEventListener('orientationchange', mobileRefreshHandler);
        window.addEventListener('resize', mobileRefreshHandler);
        
        // Mobile touch momentum handling
        let touchRefreshTimeout;
        window.addEventListener('touchend', () => {
            clearTimeout(touchRefreshTimeout);
            touchRefreshTimeout = setTimeout(() => {
                ScrollTrigger.refresh();
            }, 100);
        }, { passive: true });
        
        // Mobile fallback animation handler
        window.addEventListener('mobileScrollTrigger', (e) => {
            const sectionId = e.detail.sectionId;
            console.log(`📱 Executing mobile fallback animation for ${sectionId}`);
            
            // Execute the same animations as ScrollTrigger onEnter
            switch(sectionId) {
                case '#ceremony-reception':
                    const ceremonyTl = gsap.timeline();
                    ceremonyTl.to('#ceremony-reception .ceremony-intro', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" })
                      .to('#ceremony-reception .ceremony-title', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
                      .to('#ceremony-reception .ceremony-time', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
                      .to('#ceremony-reception .ceremony-location', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
                      .to('#ceremony-reception .ceremony-divider', { duration: 0.8, opacity: 1, scaleX: 1, ease: "power2.out" }, "+=0.2")
                      .to('#ceremony-reception .reception-intro', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
                      .to('#ceremony-reception .reception-title', { duration: 0.7, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
                      .to('#ceremony-reception .reception-time', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1")
                      .to('#ceremony-reception .reception-location', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.1");
                    break;
                    
                case '#love-story':
                    const loveTl = gsap.timeline();
                    loveTl.to('#love-story .love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
                      .to('#love-story .video-placeholder', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
                      .to('#love-story .love-quote', { duration: 1, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3");
                    break;
                    
                case '#detailed-love-story':
                    const detailedTl = gsap.timeline();
                    detailedTl.to('#detailed-love-story .detailed-love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
                      .to('#detailed-love-story .narrative-opening', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
                      .to('#detailed-love-story .narrative-college', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
                      .to('#detailed-love-story .narrative-malaysia', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
                      .to('#detailed-love-story .narrative-return', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
                      .to('#detailed-love-story .narrative-2020', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
                      .to('#detailed-love-story .narrative-2022', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4");
                    break;
                    
                case '#final-love-story':
                    const finalTl = gsap.timeline();
                    finalTl.to('#final-love-story .final-love-story-title', { duration: 1, opacity: 1, y: 0, ease: "power2.out" })
                      .to('#final-love-story .narrative-time-kind', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
                      .to('#final-love-story .narrative-2023', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
                      .to('#final-love-story .narrative-august', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
                      .to('#final-love-story .vertical-line', { duration: 1, height: '4rem', opacity: 1, ease: "power2.out" }, "+=0.3")
                      .to('#final-love-story .narrative-proposal', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                      .to('#final-love-story .narrative-forever', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
                      .to('#final-love-story .narrative-yes', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4")
                      .to('#final-love-story .narrative-question', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.4");
                    break;
                    
                case '#image-slider':
                    const sliderTl = gsap.timeline();
                    sliderTl.to('#image-slider .slider-quote-line1', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" })
                      .to('#image-slider .slider-quote-line2', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                      .to('#image-slider .slider-quote-line3', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                      .to('#image-slider .slider-quote-line4', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                      .to('#image-slider .slider-quote-line5', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3")
                      .to('#image-slider .slider-quote-line6', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                      .to('#image-slider .slider-quote-line7', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                      .to('#image-slider .slider-quote-line8', { duration: 0.6, opacity: 1, y: 0, ease: "power2.out" }, "+=0.2")
                      .to('#image-slider .slider-image-placeholder', { duration: 1, opacity: 1, scale: 1, ease: "power2.out" }, "+=0.4")
                      .to('#image-slider .slider-bottom-text', { duration: 0.8, opacity: 1, y: 0, ease: "power2.out" }, "+=0.3");
                    break;
            }
        });
    }
    
    // Desktop refresh (simpler)
    if (!isMobileDevice) {
        setTimeout(() => {
            ScrollTrigger.refresh();
            console.log('🖥️ Desktop ScrollTrigger refreshed');
        }, 1000);
    }
    
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
        console.log('🚀 initializeFamilyRsvp() called');
        console.log('💾 window.weddingFamilyMembers:', window.weddingFamilyMembers);
        
        // Check if family data exists and show teapai option if family category is "family"
        if (window.weddingFamilyData && window.weddingFamilyData.relationship_type === 'Family') {
            const teapaiOption = document.getElementById('teapai-option');
            if (teapaiOption) {
                teapaiOption.classList.remove('hidden');
            }
        }
        
        // Family members are now populated directly by PHP, no JavaScript needed
        console.log('✅ Family members populated by PHP server-side');
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
    console.log('🚀 Initializing RSVP - already in DOMContentLoaded');
    
    // Initialize RSVP functionality
    initializeFamilyRsvp();
    
    // Add event listener for family submit button
    const familySubmitBtn = document.getElementById('family-submit-btn');
    if (familySubmitBtn) {
        console.log('✅ Found family submit button - attaching event listener');
        familySubmitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('🚀 Family submit button clicked!');
            
            // Collect form data from current step (step 3)
            console.log('🔍 Looking for family members in #family-members-list...');
            const familyMembersList = document.getElementById('family-members-list');
            console.log('🔍 Family members list element:', familyMembersList);
            
            const checkedMembers = document.querySelectorAll('#family-members-list input[type="checkbox"]:checked');
            console.log('🔍 Found checked members:', checkedMembers.length);
            checkedMembers.forEach((member, index) => {
                console.log(`🔍 Member ${index + 1}:`, member.value, 'checked:', member.checked);
            });
            
            familyRsvpData.attendingMembers = Array.from(checkedMembers).map(cb => cb.value);
            console.log('📝 Collected attendingMembers:', familyRsvpData.attendingMembers);
            
            // selectedEvents should already be set from step 2, but verify
            console.log('🔍 Current selectedEvents:', familyRsvpData.selectedEvents);
            if (!familyRsvpData.selectedEvents || familyRsvpData.selectedEvents.length === 0) {
                console.warn('⚠️ No events selected! Checking for selected event button...');
                const selectedEventBtn = document.querySelector('.event-btn.selected');
                console.log('🔍 Selected event button:', selectedEventBtn);
                if (selectedEventBtn) {
                    const event = selectedEventBtn.dataset.event;
                    console.log('🔍 Event from button:', event);
                    if (event === 'both') {
                        familyRsvpData.selectedEvents = ['church', 'reception'];
                    } else {
                        familyRsvpData.selectedEvents = [event];
                    }
                    console.log('✅ Recovered selectedEvents:', familyRsvpData.selectedEvents);
                }
            }
            
            // Collect additional info
            familyRsvpData.dietaryRequirements = document.getElementById('family-dietary-requirements')?.value || '';
            familyRsvpData.additionalNotes = document.getElementById('family-additional-notes')?.value || '';
            familyRsvpData.weddingWishes = document.getElementById('family-wedding-wishes')?.value || '';
            
            console.log('📝 Collected form data:', familyRsvpData);
            
            // Call the submit function
            submitFamilyRsvp();
        });
    } else {
        console.error('❌ Family submit button not found!');
    }
    
    // Submit functions
    // Function to directly submit decline RSVP
    function submitDeclineRsvp() {
        console.log('=== STARTING submitDeclineRsvp ===');
        console.log('Submitting decline RSVP...');
        
        // Get the family data
        const familyData = window.weddingFamilyData;
        console.log('Family data:', familyData);
        console.log('Family data keys:', Object.keys(familyData));
        
        if (!familyData) {
            alert('Error: Family data not found');
            return;
        }
        
        // Use primary_guest_name as the family code (convert to URL format)
        const familyCode = familyData.primary_guest_name.toLowerCase().replace(/\s+/g, '-');
        console.log('Converting', familyData.primary_guest_name, 'to family code:', familyCode);
        
        if (!familyCode) {
            console.error('Available family data properties:', Object.keys(familyData));
            console.error('Family data values:', familyData);
            alert('Error: Primary guest name not found in family data. Check console for details.');
            return;
        }
        
        console.log('Using family code:', familyCode);
        
        // Prepare data for decline submission
        const formData = {
            action: 'wedding_family_rsvp_submit',
            family_code: familyCode,
            guest_id: familyData.id,
            attendance_status: 'no',
            selected_events: [],
            attending_members: [],
            dietary_requirements: '',
            additional_notes: '',
            wedding_wishes: '',
            nonce: '<?php echo wp_create_nonce('wedding_rsvp_nonce'); ?>'
        };
        
        console.log('Submitting decline RSVP:', formData);
        console.log('AJAX URL:', '<?php echo admin_url('admin-ajax.php'); ?>');
        
        // Submit via AJAX
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData)
        })
        .then(response => {
            console.log('Raw response status:', response.status);
            console.log('Raw response headers:', response.headers);
            return response.text();
        })
        .then(text => {
            console.log('Raw response text:', text);
            try {
                const data = JSON.parse(text);
                console.log('Parsed JSON response:', data);
                return data;
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.error('Raw text was:', text);
                throw new Error('Invalid JSON response');
            }
        })
        .then(data => {
            console.log('Decline RSVP Response:', data);
            if (data.success) {
                // Show decline message with animation
                showFamilyDeclineStep();
            } else {
                alert('Error: ' + (data.data || 'Something went wrong. Please try again.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
    }
    
    // Function to directly submit accept RSVP
    function submitAcceptRsvp() {
        console.log('=== STARTING submitAcceptRsvp ===');
        
        // Get the family data
        const familyData = window.weddingFamilyData;
        console.log('Family data:', familyData);
        console.log('Family data keys:', Object.keys(familyData));
        
        if (!familyData) {
            alert('Error: Family data not found');
            return;
        }
        
        // Use primary_guest_name as the family code (convert to URL format)
        const familyCode = familyData.primary_guest_name.toLowerCase().replace(/\s+/g, '-');
        console.log('Converting', familyData.primary_guest_name, 'to family code:', familyCode);
        
        if (!familyCode) {
            console.error('Available family data properties:', Object.keys(familyData));
            console.error('Family data values:', familyData);
            alert('Error: Primary guest name not found in family data. Check console for details.');
            return;
        }
        
        console.log('Using family code:', familyCode);
        
        // Collect all events (church, reception, and teapai if available)
        const selectedEvents = ['church', 'reception'];
        // Check if teapai is available for this family
        if (familyData.invitations && familyData.invitations.teapai && familyData.invitations.teapai.invited) {
            selectedEvents.push('teapai');
        }
        
        // Collect all family members (primary guest + family members)
        const attendingMembers = [familyData.primary_guest_name];
        if (familyData.family_members && Array.isArray(familyData.family_members)) {
            attendingMembers.push(...familyData.family_members);
        }
        
        console.log('Selected events:', selectedEvents);
        console.log('Attending members:', attendingMembers);
        
        // Prepare data for accept submission
        const formData = {
            action: 'wedding_family_rsvp_submit',
            family_code: familyCode,
            guest_id: familyData.id,
            attendance_status: 'yes',
            selected_events: selectedEvents,
            attending_members: attendingMembers,
            dietary_requirements: '',
            additional_notes: '',
            wedding_wishes: '',
            nonce: '<?php echo wp_create_nonce('wedding_rsvp_nonce'); ?>'
        };
        
        console.log('Submitting accept RSVP:', formData);
        console.log('AJAX URL:', '<?php echo admin_url('admin-ajax.php'); ?>');
        
        // Submit via AJAX
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(formData)
        })
        .then(response => {
            console.log('Raw response status:', response.status);
            console.log('Raw response headers:', response.headers);
            return response.text();
        })
        .then(text => {
            console.log('Raw response text:', text);
            try {
                const data = JSON.parse(text);
                console.log('Parsed JSON response:', data);
                return data;
            } catch (e) {
                console.error('Failed to parse JSON:', e);
                console.error('Raw text was:', text);
                throw new Error('Invalid JSON response');
            }
        })
        .then(data => {
            console.log('Accept RSVP Response:', data);
            if (data.success) {
                // Show success message
                showFamilySuccessStep();
            } else {
                alert('Error: ' + (data.data || 'Something went wrong. Please try again.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Network error. Please try again.');
        });
    }
    
    // Function to show success step with animation
    function showFamilySuccessStep() {
        // Hide all steps
        document.querySelectorAll('#family-rsvp-container .rsvp-step').forEach(stepEl => {
            stepEl.classList.add('hidden');
            stepEl.classList.remove('active');
        });
        
        // Show success step
        const successStep = document.getElementById('family-rsvp-success');
        if (successStep) {
            successStep.classList.remove('hidden');
            successStep.classList.add('active');
        }
    }
    
    // Function to show decline step with animation
    function showFamilyDeclineStep() {
        // Hide all steps
        document.querySelectorAll('#family-rsvp-container .rsvp-step').forEach(stepEl => {
            stepEl.classList.add('hidden');
            stepEl.classList.remove('active');
        });
        
        // Show decline step
        const declineStep = document.getElementById('family-rsvp-decline');
        if (declineStep) {
            declineStep.classList.remove('hidden');
            declineStep.classList.add('active');
        }
    }

    function submitFamilyRsvp() {
        const submitBtn = document.getElementById('family-submit-btn');
        if (submitBtn) {
            submitBtn.textContent = 'SUBMITTING...';
            submitBtn.disabled = true;
        }
        
        // Get the family data
        const familyData = window.weddingFamilyData;
        if (!familyData) {
            alert('Error: Family data not found');
            if (submitBtn) {
                submitBtn.textContent = 'SUBMIT RSVP';
                submitBtn.disabled = false;
            }
            return;
        }
        
        // Use primary_guest_name as the family code (convert to URL format)
        const familyCode = familyData.primary_guest_name.toLowerCase().replace(/\s+/g, '-');
        console.log('Converting', familyData.primary_guest_name, 'to family code:', familyCode);
        
        if (!familyCode) {
            console.error('Available family data properties:', Object.keys(familyData));
            console.error('Family data values:', familyData);
            alert('Error: Primary guest name not found in family data. Check console for details.');
            if (submitBtn) {
                submitBtn.textContent = 'SUBMIT RSVP';
                submitBtn.disabled = false;
            }
            return;
        }
        
        // Prepare data for new comprehensive submission
        const formData = {
            action: 'wedding_family_rsvp_submit',
            family_code: familyCode,
            guest_id: familyData.id,
            attendance_status: familyRsvpData.attendance,
            selected_events: familyRsvpData.selectedEvents,
            attending_members: familyRsvpData.attendingMembers,
            dietary_requirements: familyRsvpData.dietaryRequirements || '',
            additional_notes: familyRsvpData.additionalNotes || '',
            wedding_wishes: familyRsvpData.weddingWishes || '',
            nonce: '<?php echo wp_create_nonce('wedding_rsvp_nonce'); ?>'
        };
        
        console.log('Submitting comprehensive family RSVP:', formData);
        
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
            console.log('RSVP Response:', data);
            if (data.success) {
                // Check if this was a decline response
                if (familyRsvpData.attendance === 'no') {
                    showFamilyDeclineStep();
                } else {
                    showFamilySuccessStep();
                }
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
    
    // Animated decline functions
    function showFamilyDecline() {
        showFamilyDeclineStep();
        
        // Reset and trigger animations
        const thankYouAnimation = document.querySelector('#family-rsvp-decline .thank-you-animation');
        const title = document.querySelector('#family-rsvp-decline .thank-you-title');
        const subtitle = document.querySelector('#family-rsvp-decline .thank-you-subtitle');
        const backBtn = document.querySelector('#family-rsvp-decline .back-to-start-btn');
        
        if (thankYouAnimation && title && subtitle && backBtn) {
            // Reset animations
            thankYouAnimation.style.animation = 'none';
            title.style.animation = 'none';
            subtitle.style.animation = 'none';
            backBtn.style.animation = 'none';
            
            // Force reflow
            thankYouAnimation.offsetHeight;
            
            // Restart animations
            setTimeout(() => {
                thankYouAnimation.style.animation = 'thankYouFadeIn 0.8s ease-out forwards';
                title.style.animation = 'slideInFade 0.6s ease-out 0.2s forwards';
                subtitle.style.animation = 'slideInFade 0.6s ease-out 0.4s forwards';
                backBtn.style.animation = 'slideInFade 0.6s ease-out 0.6s forwards';
            }, 50);
        }
    }
    
    function showGeneralDecline() {
        showGeneralStep('decline');
        
        // Reset and trigger animations
        const thankYouAnimation = document.querySelector('#general-rsvp-decline .thank-you-animation');
        const title = document.querySelector('#general-rsvp-decline .thank-you-title');
        const subtitle = document.querySelector('#general-rsvp-decline .thank-you-subtitle');
        const backBtn = document.querySelector('#general-rsvp-decline .back-to-start-btn');
        
        if (thankYouAnimation && title && subtitle && backBtn) {
            // Reset animations
            thankYouAnimation.style.animation = 'none';
            title.style.animation = 'none';
            subtitle.style.animation = 'none';
            backBtn.style.animation = 'none';
            
            // Force reflow
            thankYouAnimation.offsetHeight;
            
            // Restart animations
            setTimeout(() => {
                thankYouAnimation.style.animation = 'thankYouFadeIn 0.8s ease-out forwards';
                title.style.animation = 'slideInFade 0.6s ease-out 0.2s forwards';
                subtitle.style.animation = 'slideInFade 0.6s ease-out 0.4s forwards';
                backBtn.style.animation = 'slideInFade 0.6s ease-out 0.6s forwards';
            }, 50);
        }
    }
    
    // Reset form functions
    function resetFamilyForm() {
        // Reset data
        familyRsvpData = {
            attendance: null,
            selectedEvents: [],
            attendingMembers: [],
            dietaryRequirements: '',
            additionalNotes: ''
        };
        
        // Reset form elements
        document.querySelectorAll('#family-rsvp-container input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.querySelectorAll('#family-rsvp-container textarea').forEach(ta => ta.value = '');
        
        // Show first step
        showFamilyStep(1);
    }
    
    function resetGeneralForm() {
        // Reset data
        generalRsvpData = {
            selectedEvents: [],
            guestInfo: {},
            guestNames: [],
            dietaryRequirements: '',
            additionalNotes: ''
        };
        
        // Reset form elements
        document.querySelectorAll('#general-rsvp-container input').forEach(input => input.value = '');
        document.querySelectorAll('#general-rsvp-container textarea').forEach(ta => ta.value = '');
        document.getElementById('general-guest-count').value = '1';
        updateGeneralGuestNames();
        
        // Show first step
        showGeneralStep(1);
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
    
    // RSVP Section Background Changing Functionality (Groove-style)
    const rsvpSection = document.getElementById('rsvp');
    const rsvpBackgroundImage = document.getElementById('rsvp-background-image');
    const rsvpWrapper = document.querySelector('.wedding-and-rsvp-wrapper');
    const rsvpSections = document.querySelectorAll('.wedding-content-section[data-bg-image], .rsvp-form-section[data-bg-image]');
    
    if (rsvpSection && rsvpBackgroundImage && rsvpWrapper && rsvpSections.length > 0) {
        console.log(`🎯 Setting up RSVP section with ${rsvpSections.length} sections and background changes`);
        
        // Background image changing based on scroll position in RSVP wrapper
        rsvpWrapper.addEventListener('scroll', function() {
            const scrollTop = rsvpWrapper.scrollTop;
            const wrapperHeight = rsvpWrapper.clientHeight;
            
            // Find which section is currently in view
            let currentSectionIndex = 0;
            rsvpSections.forEach((section, index) => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.offsetHeight;
                
                if (scrollTop >= sectionTop - wrapperHeight / 2 && 
                    scrollTop < sectionTop + sectionHeight - wrapperHeight / 2) {
                    currentSectionIndex = index;
                }
            });
            
            // Update background image smoothly with fade animation
            const currentSection = rsvpSections[currentSectionIndex];
            if (currentSection && currentSection.dataset.bgImage) {
                const newBgImage = currentSection.dataset.bgImage;
                const currentBg = rsvpBackgroundImage.src;
                
                if (currentBg !== newBgImage) {
                    console.log(`🎨 Starting fade transition to section ${currentSectionIndex} (${currentSection.dataset.section})`);
                    
                    // Use GSAP for smooth fade animation
                    gsap.to(rsvpBackgroundImage, {
                        duration: 0.6,
                        opacity: 0,
                        ease: "power2.out",
                        onComplete: function() {
                            // Change the image source
                            rsvpBackgroundImage.src = newBgImage;
                            
                            // Fade back in
                            gsap.to(rsvpBackgroundImage, {
                                duration: 0.8,
                                opacity: 1,
                                ease: "power2.out",
                                onComplete: function() {
                                    console.log(`✨ Background fade complete for section ${currentSectionIndex}`);
                                }
                            });
                        }
                    });
                }
            }
        });
        
        // ⏰ AUTO BACKGROUND ROTATION - Time-based slideshow
        let autoRotationEnabled = true;
        let currentAutoIndex = 0;
        let isScrolling = false;
        let scrollTimeout;
        
        // Track user scrolling to pause auto rotation
        rsvpWrapper.addEventListener('scroll', function() {
            isScrolling = true;
            clearTimeout(scrollTimeout);
            
            // Resume auto rotation after user stops scrolling
            scrollTimeout = setTimeout(function() {
                isScrolling = false;
                console.log('📖 User finished scrolling, resuming auto background rotation');
            }, 3000); // Wait 3 seconds after scroll stops
        });
        
        // Auto rotate backgrounds every 4 seconds
        function startAutoBackgroundRotation() {
            setInterval(function() {
                // Only rotate if user isn't actively scrolling
                if (!isScrolling && autoRotationEnabled && rsvpSections.length > 0) {
                    // Get all background images from sections
                    const backgroundImages = [];
                    rsvpSections.forEach(section => {
                        if (section.dataset.bgImage) {
                            backgroundImages.push(section.dataset.bgImage);
                        }
                    });
                    
                    if (backgroundImages.length > 0) {
                        // Move to next background
                        currentAutoIndex = (currentAutoIndex + 1) % backgroundImages.length;
                        const nextBgImage = backgroundImages[currentAutoIndex];
                        
                        // Only change if it's different from current
                        if (rsvpBackgroundImage.src !== nextBgImage) {
                            console.log(`⏰ Auto-rotating to background ${currentAutoIndex + 1}/${backgroundImages.length}`);
                            
                            // Use same smooth fade animation
                            gsap.to(rsvpBackgroundImage, {
                                duration: 0.8,
                                opacity: 0,
                                ease: "power2.out",
                                onComplete: function() {
                                    rsvpBackgroundImage.src = nextBgImage;
                                    
                                    gsap.to(rsvpBackgroundImage, {
                                        duration: 1.0,
                                        opacity: 1,
                                        ease: "power2.out",
                                        onComplete: function() {
                                            console.log(`✨ Auto background rotation complete`);
                                        }
                                    });
                                }
                            });
                        }
                    }
                }
            }, 4000); // Change every 4 seconds
        }
        
        // Start the auto rotation
        startAutoBackgroundRotation();
        console.log('⏰ Auto background rotation started (4 second intervals)');
        
        console.log('✅ RSVP section background changing functionality ready!');
    }
});

// 🎬 SCROLL-TRIGGERED CONTENT ANIMATIONS
document.addEventListener('DOMContentLoaded', function() {
    console.log('🎭 Initializing scroll-triggered content animations...');
    
    // Set initial state - hide all content for animation
    gsap.set('.section-content', {
        opacity: 0,
        y: 50,
        scale: 0.95
    });
    
    // Track which sections have been animated to prevent re-animation
    const animatedSections = new Set();
    let contentObserver;
    
    // Function to start content animations (called after monogram completes)
    function startContentAnimations() {
        console.log('🎬 Starting content animations after monogram completion...');
        
        // Create Intersection Observer for scroll animations
        const observerOptions = {
            root: document.querySelector('.wedding-and-rsvp-wrapper'),
            rootMargin: '-10% 0px -30% 0px', // Trigger when 10% visible from top
            threshold: 0.2
        };
        
        contentObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                const sectionContent = entry.target.querySelector('.section-content');
                const sectionName = entry.target.dataset.section;
                
                // Only animate in when entering view for the first time
                if (entry.isIntersecting && !animatedSections.has(sectionName)) {
                    console.log(`🎬 Animating in: ${sectionName} (first time)`);
                    
                    // Mark as animated
                    animatedSections.add(sectionName);
                    
                    // Animate content in with stagger effect
                    gsap.timeline()
                        .to(sectionContent, {
                            duration: 0.8,
                            opacity: 1,
                            y: 0,
                            scale: 1,
                            ease: "power2.out"
                        })
                        .from(sectionContent.querySelectorAll('h1, h2, h3, p, .btn, .grid > div'), {
                            duration: 0.6,
                            opacity: 0,
                            y: 30,
                            stagger: 0.1,
                            ease: "power2.out"
                        }, "-=0.4");
                }
                // No animation out - content stays visible once animated
            });
        }, observerOptions);
        
        // Observe all wedding content sections
        const sectionsToAnimate = document.querySelectorAll('.wedding-content-section, .rsvp-form-section');
        sectionsToAnimate.forEach(section => {
            contentObserver.observe(section);
            console.log(`👁️ Observing section: ${section.dataset.section}`);
        });
        
        console.log(`✨ Scroll animations ready for ${sectionsToAnimate.length} sections!`);
    }
    
    // Check if wedding monogram is already complete, or wait for it
    if (window.weddingMonogramComplete) {
        startContentAnimations();
    } else {
        console.log('⏳ Waiting for wedding monogram to complete before enabling content animations...');
        window.triggerContentAnimations = startContentAnimations;
    }
});

// Beautiful GSAP Timeline Animations - No Emergency Overrides!
// Timeline will run automatically and enable smooth user experience
</script>

<style>
/* Enhanced Smooth Scrolling CSS */
.wedding-and-rsvp-wrapper {
    scroll-padding-top: 0;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* Internet Explorer */
}

.wedding-and-rsvp-wrapper::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

.wedding-content-section, .rsvp-form-section {
    scroll-margin-top: 0;
    transition: opacity 0.3s ease-in-out;
}

/* Smooth section transitions */
.section-content {
    transition: transform 0.3s ease-out, opacity 0.3s ease-out;
}

/* Optimize for mobile smooth scrolling */
@media (max-width: 768px) {
    .wedding-and-rsvp-wrapper {
        scroll-snap-type: none !important; /* Disable scroll snap on mobile */
        scroll-behavior: auto !important; /* More natural scrolling */
        -webkit-overflow-scrolling: touch !important; /* Smooth iOS scrolling */
        overscroll-behavior: contain; /* Prevent bounce effect */
    }
    
    .wedding-content-section, .rsvp-form-section {
        min-height: auto !important; /* Content-based height on mobile */
        padding: 2rem 1rem !important; /* Compact but readable padding */
        scroll-snap-align: none !important; /* Remove snap points */
        scroll-snap-stop: normal !important; /* Allow free scrolling */
    }
    
    .wedding-content-section[data-section="wedding-details"] {
        padding-top: 15vh !important; /* Push first section below monogram */
    }
    
    .section-content {
        max-width: 90% !important; /* Better mobile utilization */
    }
}

/* Keep scroll snap only on desktop */
@media (min-width: 769px) {
    .wedding-and-rsvp-wrapper {
        scroll-snap-type: y mandatory;
        scroll-behavior: smooth;
    }
    
    .wedding-content-section, .rsvp-form-section {
        scroll-snap-align: start;
        scroll-snap-stop: always;
    }
}
</style>

<!-- Wedding Details Container functionality is now inline above -->
<!-- Container opening and background changes handled by main script -->

<?php get_footer(); ?>
