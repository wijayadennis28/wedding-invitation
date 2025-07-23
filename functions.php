<?php
/**
 * Wedding Invitation Theme Functions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme setup
function wedding_invitation_setup() {
    // Add theme support
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('custom-logo');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'wedding-invitation'),
    ));
}
add_action('after_setup_theme', 'wedding_invitation_setup');

// Enqueue styles and scripts
function wedding_invitation_scripts() {
    // Google Fonts for Allura and Aniyah
    wp_enqueue_style('google-fonts', '<link href="https://fonts.googleapis.com/css2?family=Allura&family=Montserrat:wght@100..900&family=Playfair+Display:ital,wght@1,400..900&display=swap" rel="stylesheet" rel="stylesheet">', array(), '1.0.0');
    wp_enqueue_style('aniyah-font', 'https://fonts.cdnfonts.com/css/aniyah-personal-use', array(), '1.0.0');
    wp_enqueue_style('quiche-font', 'https://fonts.cdnfonts.com/css/quiche', array(), '1.0.0');
                
                
    
    // GSAP from CDN - make sure they load in footer
    wp_enqueue_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', array(), '3.12.2', true);
    wp_enqueue_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', array('gsap'), '3.12.2', true);
    
    // Add inline script to check GSAP loading
    wp_add_inline_script('gsap', 'console.log("GSAP loaded successfully");');
    wp_add_inline_script('gsap-scrolltrigger', 'console.log("ScrollTrigger loaded successfully");');
    
    // AOS (Animate On Scroll) - commented out since we're using GSAP
    // wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css', array(), '2.3.1');
    // wp_enqueue_script('aos-js', 'https://unpkg.com/aos@2.3.1/dist/aos.js', array(), '2.3.1', true);
    
    // Custom theme styles
    wp_enqueue_style('wedding-styles', get_template_directory_uri() . '/assets/css/wedding-styles.css', array(), '1.0.0');
    
    // Custom theme scripts - commented out since we're using inline scripts in front-page.php
    // wp_enqueue_script('wedding-scripts', get_template_directory_uri() . '/assets/js/wedding-scripts.js', array('gsap'), '1.0.0', true);
    
    // Localize script for AJAX - commented out since we disabled wedding-scripts
    // wp_localize_script('wedding-scripts', 'wedding_ajax', array(
    //     'ajax_url' => admin_url('admin-ajax.php'),
    //     'nonce' => wp_create_nonce('wedding_nonce')
    // ));
}
add_action('wp_enqueue_scripts', 'wedding_invitation_scripts');

// Custom post types for wedding content
function wedding_invitation_post_types() {
    // Love Story Timeline
    register_post_type('love_story', array(
        'labels' => array(
            'name' => 'Love Story',
            'singular_name' => 'Love Story Item'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-heart'
    ));
    
    // Wedding Events
    register_post_type('wedding_event', array(
        'labels' => array(
            'name' => 'Wedding Events',
            'singular_name' => 'Wedding Event'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-calendar-alt'
    ));
    
    // Photo Gallery
    register_post_type('photo_gallery', array(
        'labels' => array(
            'name' => 'Photo Gallery',
            'singular_name' => 'Photo'
        ),
        'public' => true,
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'menu_icon' => 'dashicons-camera'
    ));
}
add_action('init', 'wedding_invitation_post_types');

// RSVP Form Handler
function handle_rsvp_submission() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'wedding_nonce')) {
        wp_die('Security check failed');
    }
    
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $attending = sanitize_text_field($_POST['attending']);
    $guests = intval($_POST['guests']);
    $dietary = sanitize_textarea_field($_POST['dietary']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Save to database or send email
    $rsvp_data = array(
        'name' => $name,
        'email' => $email,
        'attending' => $attending,
        'guests' => $guests,
        'dietary' => $dietary,
        'message' => $message,
        'date' => current_time('mysql')
    );
    
    // You can save to database or send email here
    // For now, we'll just return success
    
    wp_send_json_success(array(
        'message' => 'Thank you for your RSVP! We can\'t wait to celebrate with you.'
    ));
}
add_action('wp_ajax_handle_rsvp', 'handle_rsvp_submission');
add_action('wp_ajax_nopriv_handle_rsvp', 'handle_rsvp_submission');

// Add custom fields support
function wedding_invitation_add_meta_boxes() {
    add_meta_box(
        'wedding_page_settings',
        'Wedding Page Settings',
        'wedding_page_settings_callback',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'wedding_invitation_add_meta_boxes');

function wedding_page_settings_callback($post) {
    wp_nonce_field('wedding_page_settings', 'wedding_page_settings_nonce');
    
    $page_animation = get_post_meta($post->ID, '_page_animation', true);
    $background_color = get_post_meta($post->ID, '_background_color', true);
    $text_color = get_post_meta($post->ID, '_text_color', true);
    
    echo '<table class="form-table">';
    echo '<tr>';
    echo '<th><label for="page_animation">Page Animation</label></th>';
    echo '<td>';
    echo '<select name="page_animation" id="page_animation">';
    echo '<option value="fade-in"' . selected($page_animation, 'fade-in', false) . '>Fade In</option>';
    echo '<option value="slide-up"' . selected($page_animation, 'slide-up', false) . '>Slide Up</option>';
    echo '<option value="zoom-in"' . selected($page_animation, 'zoom-in', false) . '>Zoom In</option>';
    echo '</select>';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label for="background_color">Background Color</label></th>';
    echo '<td><input type="color" name="background_color" id="background_color" value="' . esc_attr($background_color) . '" /></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th><label for="text_color">Text Color</label></th>';
    echo '<td><input type="color" name="text_color" id="text_color" value="' . esc_attr($text_color) . '" /></td>';
    echo '</tr>';
    echo '</table>';
}

function save_wedding_page_settings($post_id) {
    if (!isset($_POST['wedding_page_settings_nonce']) || !wp_verify_nonce($_POST['wedding_page_settings_nonce'], 'wedding_page_settings')) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (isset($_POST['page_animation'])) {
        update_post_meta($post_id, '_page_animation', sanitize_text_field($_POST['page_animation']));
    }
    
    if (isset($_POST['background_color'])) {
        update_post_meta($post_id, '_background_color', sanitize_text_field($_POST['background_color']));
    }
    
    if (isset($_POST['text_color'])) {
        update_post_meta($post_id, '_text_color', sanitize_text_field($_POST['text_color']));
    }
}
add_action('save_post', 'save_wedding_page_settings');
?>
