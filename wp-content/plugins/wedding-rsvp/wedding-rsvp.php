<?php
/**
 * Plugin Name: Wedding RSVP System
 * Plugin URI: https://yourweddingsite.com
 * Description: Complete wedding RSVP management system with family-based invitations and custom URLs
 * Version: 1.0.0
 * Author: Dennis Wijaya
 * License: GPL v2 or later
 * Text Domain: wedding-rsvp
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WEDDING_RSVP_VERSION', '1.0.0');
define('WEDDING_RSVP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WEDDING_RSVP_PLUGIN_URL', plugin_dir_url(__FILE__));

class WeddingRSVP { 
    
    private static $instance = null;
    private $table_families;
    private $table_guests;
    private $table_rsvp;
    private $table_events;
    private $table_guest_invitations;
    
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        global $wpdb;
        
        // Set table names
        $this->table_families = $wpdb->prefix . 'wedding_families';
        $this->table_guests = $wpdb->prefix . 'wedding_guests';
        $this->table_rsvp = $wpdb->prefix . 'wedding_rsvp_responses';
        $this->table_events = $wpdb->prefix . 'wedding_events';
        $this->table_guest_invitations = $wpdb->prefix . 'wedding_guest_invitations';
        
        // Initialize hooks
        $this->init_hooks();
    }
    
    // Prevent cloning of the instance
    private function __clone() {}
    
    // Prevent unserializing of the instance
    private function __wakeup() {}
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        // Core hooks
        add_action('init', array($this, 'init'));
        add_action('init', array($this, 'add_rewrite_rules'));
        add_action('wp', array($this, 'handle_wedding_urls'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // AJAX hooks
        add_action('wp_ajax_wedding_rsvp_submit', array($this, 'handle_rsvp_submission'));
        add_action('wp_ajax_nopriv_wedding_rsvp_submit', array($this, 'handle_rsvp_submission'));
        add_action('wp_ajax_wedding_general_rsvp_submit', array($this, 'handle_general_rsvp_submission'));
        add_action('wp_ajax_nopriv_wedding_general_rsvp_submit', array($this, 'handle_general_rsvp_submission'));
        add_action('wp_ajax_get_family_data', array($this, 'handle_get_family_data'));
        add_action('wp_ajax_get_whatsapp_message', array($this, 'handle_get_whatsapp_message'));
        add_action('wp_ajax_import_csv_guests', array($this, 'handle_import_csv'));
        add_action('wp_ajax_import_existing_csv', array($this, 'handle_import_existing_csv'));
        
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Activation/Deactivation hooks
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Plugin initialization
     */
    public function init() {
        // Load template helpers
        include_once plugin_dir_path(__FILE__) . 'template-helpers.php';
    }
    
    /**
     * Add rewrite rules for family URLs
     */
    public function add_rewrite_rules() {
        // Add rewrite rule for family codes
        add_rewrite_rule('^([^/]+)/?$', 'index.php?family_code=$matches[1]', 'top');
        
        // Register the query variable
        add_filter('query_vars', array($this, 'add_query_vars'));
    }
    
    /**
     * Add custom query variables
     */
    public function add_query_vars($vars) {
        $vars[] = 'family_code';
        return $vars;
    }    /**
     * Plugin activation
     */
    public function activate() {
        $this->create_tables();
        $this->update_existing_tables();
        $this->insert_default_events();
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    /**
     * Create database tables
     */
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Ensure UTF-8 support for emojis
        if (empty($charset_collate)) {
            $charset_collate = "DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        }
        
        // Families table
        $sql_families = "CREATE TABLE {$this->table_families} (
            id int(11) NOT NULL AUTO_INCREMENT,
            family_code varchar(50) NOT NULL UNIQUE,
            family_name varchar(100) NOT NULL,
            max_guests int(11) DEFAULT 1,
            phone_primary varchar(20),
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            is_active boolean DEFAULT TRUE,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Guests table - optimized structure with JSON for family members
        $sql_guests = "CREATE TABLE {$this->table_guests} (
            id int(11) NOT NULL AUTO_INCREMENT,
            family_id int(11) NOT NULL,
            primary_guest_name varchar(100) NOT NULL,
            phone_number varchar(20),
            family_members JSON,
            pax_num int(11),
            invitation_type enum('Printed','Digital'),
            is_primary_contact boolean DEFAULT FALSE,
            guest_order int(11) DEFAULT 1,
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY idx_family_guest (family_id, guest_order)
        ) $charset_collate;";
        
        // RSVP responses table
        $sql_rsvp = "CREATE TABLE {$this->table_rsvp} (
            id int(11) NOT NULL AUTO_INCREMENT,
            family_id int(11) NOT NULL,
            guest_id int(11) NOT NULL,
            event_type varchar(50) NOT NULL,
            attendance_status enum('yes','no') DEFAULT 'no',
            dietary_requirements text,
            additional_notes text,
            responded_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_guest_event (guest_id, event_type),
            KEY idx_family_event (family_id, event_type)
        ) $charset_collate;";
        
        // Wedding events table
        $sql_events = "CREATE TABLE {$this->table_events} (
            id int(11) NOT NULL AUTO_INCREMENT,
            event_type varchar(50) NOT NULL UNIQUE,
            event_name varchar(100) NOT NULL,
            event_date date,
            event_time time,
            location varchar(200),
            description text,
            is_active boolean DEFAULT TRUE,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Guest invitations junction table - normalized relationship
        $sql_guest_invitations = "CREATE TABLE {$this->table_guest_invitations} (
            id int(11) NOT NULL AUTO_INCREMENT,
            guest_id int(11) NOT NULL,
            event_id int(11) NOT NULL,
            is_invited enum('yes','no') DEFAULT 'no',
            gets_souvenir enum('yes','no') DEFAULT 'no',
            created_at timestamp DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_guest_event (guest_id, event_id),
            KEY idx_guest (guest_id),
            KEY idx_event (event_id)
        ) $charset_collate;";
        
        // Create tables
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_families);
        dbDelta($sql_guests);
        dbDelta($sql_rsvp);
        dbDelta($sql_events);
        dbDelta($sql_guest_invitations);
        
        // Clean up legacy data
        $this->cleanup_legacy_data();
    }
    
    /**
     * Clean up legacy data and inconsistencies
     */
    private function cleanup_legacy_data() {
        global $wpdb;
        
        // Clean up any existing "maybe" responses (convert to "no")
        $wpdb->query("UPDATE {$this->table_rsvp} SET attendance_status = 'no' WHERE attendance_status = 'maybe'");
        
        // Clean up orphaned RSVP responses (responses without valid guests/families)
        $wpdb->query("
            DELETE r FROM {$this->table_rsvp} r 
            LEFT JOIN {$this->table_guests} g ON r.guest_id = g.id 
            LEFT JOIN {$this->table_families} f ON g.family_id = f.id 
            WHERE g.id IS NULL OR f.id IS NULL OR f.is_active = 0
        ");
    }

    /**
     * Update existing tables to new structure
     */
    private function update_existing_tables() {
        global $wpdb;

        $guests_table = $wpdb->prefix . 'wedding_guests';
        
        // Check if old structure exists by looking for guest_name column
        $columns = $wpdb->get_results("SHOW COLUMNS FROM $guests_table");
        $has_old_structure = false;
        $old_columns = [];
        
        foreach ($columns as $column) {
            if ($column->Field === 'guest_name') {
                $has_old_structure = true;
            }
            $old_columns[] = $column->Field;
        }
        
        if ($has_old_structure) {
            // Backup existing data before restructuring
            $existing_guests = $wpdb->get_results("SELECT * FROM $guests_table");
            
            // Add new columns if they don't exist
            if (!in_array('primary_guest_name', $old_columns)) {
                $wpdb->query("ALTER TABLE $guests_table ADD COLUMN primary_guest_name VARCHAR(255) NOT NULL DEFAULT ''");
            }
            if (!in_array('family_members', $old_columns)) {
                $wpdb->query("ALTER TABLE $guests_table ADD COLUMN family_members JSON");
            }
            
            // Migrate data from old structure to new structure
            foreach ($existing_guests as $guest) {
                $family_members = [];
                
                // Collect all guest names from old columns
                if (!empty($guest->guest_name)) {
                    $primary_name = $guest->guest_name;
                    if (!empty($guest->guest_2)) $family_members[] = $guest->guest_2;
                    if (!empty($guest->guest_3)) $family_members[] = $guest->guest_3;
                    if (!empty($guest->guest_4)) $family_members[] = $guest->guest_4;
                    if (!empty($guest->guest_5)) $family_members[] = $guest->guest_5;
                    if (!empty($guest->guest_6)) $family_members[] = $guest->guest_6;
                    
                    // Update the record with new structure
                    $family_json = !empty($family_members) ? json_encode($family_members) : null;
                    
                    $wpdb->update(
                        $guests_table,
                        [
                            'primary_guest_name' => $primary_name,
                            'family_members' => $family_json
                        ],
                        ['id' => $guest->id]
                    );
                }
            }
            
            // Remove old columns
            $old_guest_columns = ['guest_name', 'guest_2', 'guest_3', 'guest_4', 'guest_5', 'guest_6'];
            foreach ($old_guest_columns as $col) {
                if (in_array($col, $old_columns)) {
                    $wpdb->query("ALTER TABLE $guests_table DROP COLUMN $col");
                }
            }
        }
    }
    
    /**
     * Insert default wedding events
     */
    public function insert_default_events() {
        global $wpdb;
        
        $default_events = array(
            array(
                'event_type' => 'church',
                'event_name' => 'Holy Matrimony',
                'event_date' => '2025-11-22',
                'event_time' => '09:00:00',
                'location' => 'St. Laurensius Alam Sutera',
                'description' => 'Wedding ceremony'
            ),
            array(
                'event_type' => 'teapai',
                'event_name' => 'Tea Ceremony', 
                'event_date' => '2025-11-22',
                'event_time' => '08:00:00',
                'location' => 'Bride\'s Family Home',
                'description' => 'Traditional Chinese tea ceremony'
            ),
            array(
                'event_type' => 'reception',
                'event_name' => 'Wedding Reception',
                'event_date' => '2025-11-22',
                'event_time' => '18:30:00',
                'location' => 'JHL Solitaire Gading Serpong',
                'description' => 'Wedding dinner and celebration'
            )
        );
        
        foreach ($default_events as $event) {
            // Check if event already exists
            $existing = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$this->table_events} WHERE event_type = %s",
                $event['event_type']
            ));
            
            if (!$existing) {
                $wpdb->insert($this->table_events, $event);
            }
        }
    }
    
    /**
     * Handle family-specific URLs
     */
    public function handle_wedding_urls() {
        // Get the URL path
        $request_uri = trim($_SERVER['REQUEST_URI'], '/');
        $parts = explode('/', $request_uri);
        
        // For URLs like /wedding-invitation/dennis-wijaya, we want the last part
        if (count($parts) >= 2 && $parts[0] === 'wedding-invitation' && !empty($parts[1])) {
            $url_slug = sanitize_text_field($parts[1]);
            
            // Skip if it's a known WordPress path
            $skip_paths = ['wp-admin', 'wp-content', 'wp-includes', 'admin-ajax.php', 'wp-json'];
            if (in_array($url_slug, $skip_paths)) {
                return;
            }
            
            // Convert URL slug back to name format (dennis-wijaya -> Dennis Wijaya)
            $guest_name = str_replace('-', ' ', $url_slug);
            $guest_name = ucwords($guest_name); // Dennis Wijaya
            
            error_log("Wedding URL Debug - Looking for guest: " . $guest_name);
            
            // Find guest by name
            global $wpdb;
            $guest = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$this->table_guests} WHERE primary_guest_name = %s",
                $guest_name
            ));
            
            if ($guest) {
                error_log("Wedding URL Debug - Guest found: " . $guest->primary_guest_name);
                
                // Get invitations for this guest
                $invitations = $wpdb->get_results($wpdb->prepare("
                    SELECT e.event_type, gi.is_invited, gi.gets_souvenir 
                    FROM {$this->table_guest_invitations} gi
                    JOIN {$this->table_events} e ON gi.event_id = e.id
                    WHERE gi.guest_id = %d
                ", $guest->id));
                
                // Build guest data with invitations
                $guest_data = [
                    'id' => $guest->id,
                    'primary_guest_name' => $guest->primary_guest_name,
                    'phone_number' => $guest->phone_number,
                    'pax_num' => $guest->pax_num,
                    'invitation_type' => $guest->invitation_type,
                    'relationship_type' => $guest->relationship_type ?? 'Friend',
                    'family_members' => json_decode($guest->family_members, true) ?? [],
                    'invitations' => []
                ];
                
                foreach ($invitations as $inv) {
                    $guest_data['invitations'][$inv->event_type] = [
                        'invited' => $inv->is_invited === 'yes',
                        'souvenir' => $inv->gets_souvenir === 'yes'
                    ];
                }
                
                // Convert to object for consistency with template usage
                $guest_object = (object) $guest_data;
                $guest_object->invitations = (object) array_map(function($inv) {
                    return (object) $inv;
                }, $guest_data['invitations']);
                
                // Set global data for templates
                global $wedding_family_data, $wedding_guests_data;
                $wedding_family_data = $guest_object;
                $wedding_guests_data = [$guest_object];
                
                error_log("Wedding URL Debug - Global data set for guest with invitations: " . print_r($guest_data['invitations'], true));
                return;
            } else {
                error_log("Wedding URL Debug - No guest found with name: " . $guest_name);
            }
        }
    }
    
    /**
     * Get family by code
     */
    public function get_family_by_code($family_code) {
        global $wpdb;
        
        return $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_families} WHERE family_code = %s AND is_active = 1",
            $family_code
        ));
    }
    
    /**
     * Get family guests
     */
    public function get_family_guests($family_id) {
        global $wpdb;
        
        return $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$this->table_guests} WHERE family_id = %d ORDER BY guest_order ASC",
            $family_id
        ));
    }
    
    /**
     * Get wedding events
     */
    public function get_wedding_events() {
        global $wpdb;
        
        return $wpdb->get_results(
            "SELECT * FROM {$this->table_events} WHERE is_active = 1 ORDER BY event_date ASC, event_time ASC"
        );
    }
    
    /**
     * Load wedding template
     */
    private function load_wedding_template($family_code) {
        include get_template_directory() . '/front-page.php';
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_script('wedding-rsvp-js', WEDDING_RSVP_PLUGIN_URL . 'assets/wedding-rsvp.js', array('jquery'), WEDDING_RSVP_VERSION, true);
        wp_enqueue_style('wedding-rsvp-css', WEDDING_RSVP_PLUGIN_URL . 'assets/wedding-rsvp.css', array(), WEDDING_RSVP_VERSION);
        
        // Localize script for AJAX
        wp_localize_script('wedding-rsvp-js', 'wedding_rsvp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wedding_rsvp_nonce')
        ));
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin pages
        if (strpos($hook, 'wedding-') !== false) {
            // Enqueue WordPress media scripts
            wp_enqueue_media();
            wp_enqueue_script('jquery');
        }
    }
    
    /**
     * Handle RSVP form submission
     */
    public function handle_rsvp_submission() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'wedding_rsvp_nonce')) {
            wp_die('Security check failed');
        }
        
        global $wpdb;
        
        $guest_id = intval($_POST['guest_id']);
        $responses = $_POST['responses']; // Array of event responses
        
        foreach ($responses as $event_type => $response_data) {
            $wpdb->replace(
                $this->table_rsvp,
                array(
                    'guest_id' => $guest_id,
                    'event_type' => $event_type,
                    'attendance_status' => $response_data['status'],
                    'dietary_requirements' => sanitize_textarea_field($response_data['dietary']),
                    'additional_notes' => sanitize_textarea_field($response_data['notes'])
                )
            );
        }
        
        wp_send_json_success('RSVP submitted successfully');
    }
    
    /**
     * Handle General RSVP form submission (for non-family users)
     */
    public function handle_general_rsvp_submission() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'wedding_rsvp_nonce')) {
            wp_send_json_error('Security check failed');
        }
        
        global $wpdb;
        
        // Sanitize input data
        $guest_name = sanitize_text_field($_POST['guest_name']);
        $guest_email = sanitize_email($_POST['guest_email']);
        $guest_count = intval($_POST['guest_count']);
        $events = isset($_POST['events']) ? array_map('sanitize_text_field', $_POST['events']) : array();
        $dietary_requirements = sanitize_textarea_field($_POST['dietary_requirements']);
        $additional_notes = sanitize_textarea_field($_POST['additional_notes']);
        
        // Validate required fields
        if (empty($guest_name) || empty($guest_email) || $guest_count < 1) {
            wp_send_json_error('Please fill in all required fields');
        }
        
        if (empty($events)) {
            wp_send_json_error('Please select at least one event to attend');
        }
        
        // Validate events against available options
        $valid_events = array('church', 'teapai', 'reception');
        foreach ($events as $event) {
            if (!in_array($event, $valid_events)) {
                wp_send_json_error('Invalid event selection');
            }
        }
        
        try {
            // Start transaction
            $wpdb->query('START TRANSACTION');
            
            // Check if guest already exists by email
            $existing_guest = $wpdb->get_row($wpdb->prepare("
                SELECT * FROM {$this->table_guests} WHERE email = %s
            ", $guest_email));
            
            if ($existing_guest) {
                // Update existing guest
                $guest_id = $existing_guest->id;
                $wpdb->update(
                    $this->table_guests,
                    array(
                        'primary_guest_name' => $guest_name,
                        'pax_num' => $guest_count,
                        'phone_number' => '', // Will be empty for general submissions
                        'invitation_type' => 'general',
                        'updated_at' => current_time('mysql')
                    ),
                    array('id' => $guest_id)
                );
            } else {
                // Insert new guest
                $wpdb->insert(
                    $this->table_guests,
                    array(
                        'primary_guest_name' => $guest_name,
                        'email' => $guest_email,
                        'pax_num' => $guest_count,
                        'phone_number' => '', // Will be empty for general submissions
                        'invitation_type' => 'general',
                        'created_at' => current_time('mysql'),
                        'updated_at' => current_time('mysql')
                    )
                );
                $guest_id = $wpdb->insert_id;
            }
            
            if (!$guest_id) {
                throw new Exception('Failed to create/update guest record');
            }
            
            // Clear existing RSVP responses for this guest
            $wpdb->delete($this->table_rsvp, array('guest_id' => $guest_id));
            
            // Insert RSVP responses for each selected event
            foreach ($events as $event_type) {
                $wpdb->insert(
                    $this->table_rsvp,
                    array(
                        'guest_id' => $guest_id,
                        'event_type' => $event_type,
                        'attendance_status' => 'yes',
                        'dietary_requirements' => $dietary_requirements,
                        'additional_notes' => $additional_notes,
                        'created_at' => current_time('mysql'),
                        'updated_at' => current_time('mysql')
                    )
                );
            }
            
            // Commit transaction
            $wpdb->query('COMMIT');
            
            wp_send_json_success('Thank you! Your RSVP has been submitted successfully.');
            
        } catch (Exception $e) {
            // Rollback transaction
            $wpdb->query('ROLLBACK');
            wp_send_json_error('Sorry, there was an error submitting your RSVP. Please try again.');
        }
    }
    
    /**
     * Handle AJAX request for family data
     */
    public function handle_get_family_data() {
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'get_family_data_nonce')) {
            wp_send_json_error('Security check failed');
        }
        
        global $wpdb;
        
        $family_id = intval($_POST['family_id']);
        
        // Get family data
        $family = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM wp_wedding_guests WHERE id = %d
        ", $family_id));
        
        if ($family) {
            // Get invitations
            $invitations = $wpdb->get_results($wpdb->prepare("
                SELECT e.event_type, gi.is_invited, gi.gets_souvenir 
                FROM wp_wedding_guest_invitations gi
                JOIN wp_wedding_events e ON gi.event_id = e.id
                WHERE gi.guest_id = %d
            ", $family_id));
            
            $family_data = [
                'primary_guest_name' => $family->primary_guest_name,
                'phone_number' => $family->phone_number,
                'pax_num' => $family->pax_num,
                'invitation_type' => $family->invitation_type,
                'relationship_type' => $family->relationship_type ?? 'Friend',
                'family_members' => [],
                'invitations' => []
            ];
            
            // Convert family members to array format
            $family_members_raw = json_decode($family->family_members, true);
            if ($family_members_raw && is_array($family_members_raw)) {
                $family_data['family_members'] = array_filter($family_members_raw, function($member) {
                    return !empty(trim($member));
                });
            }
            
            foreach ($invitations as $inv) {
                $family_data['invitations'][$inv->event_type] = [
                    'invited' => $inv->is_invited === 'yes',
                    'souvenir' => $inv->gets_souvenir === 'yes'
                ];
            }
            
            wp_send_json_success($family_data);
        } else {
            wp_send_json_error('Family not found');
        }
    }
    
    /**
     * Handle WhatsApp message generation
     */
    public function handle_get_whatsapp_message() {
        // Check if user has permission
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Permission denied');
        }
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'whatsapp_message_nonce')) {
            wp_send_json_error('Security check failed');
        }
        
        global $wpdb;
        
        $family_id = intval($_POST['family_id']);
        
        // Get family data
        $family = $wpdb->get_row($wpdb->prepare("
            SELECT * FROM wp_wedding_guests WHERE id = %d
        ", $family_id));
        
        if (!$family) {
            wp_send_json_error('Family not found');
        }
        
        // Check if phone number exists
        if (empty($family->phone_number)) {
            wp_send_json_error('No phone number found for this family');
        }
        
        // Clean phone number (remove non-digits except +)
        $phone = preg_replace('/[^\d+]/', '', $family->phone_number);
        
        // Add country code if missing (assume Indonesia +62)
        if (!str_starts_with($phone, '+')) {
            if (str_starts_with($phone, '0')) {
                $phone = '+62' . substr($phone, 1);
            } elseif (str_starts_with($phone, '62')) {
                $phone = '+' . $phone;
            } else {
                $phone = '+62' . $phone;
            }
        }
        
        // Generate personalized greeting
        $family_members = json_decode($family->family_members, true) ?? [];
        $guests_data = [$family]; // Convert to array for the greeting function
        
        $greeting = 'Guest';
        if (function_exists('format_smart_wedding_greeting')) {
            $greeting = format_smart_wedding_greeting($family, $guests_data);
            // Remove "DEAR " prefix and comma for WhatsApp
            $greeting = str_replace(['DEAR ', ','], '', $greeting);
        }
        
        // Generate invitation URL
        $guest_url_name = strtolower(str_replace(' ', '-', $family->primary_guest_name));
        $guest_url_name = preg_replace('/[^a-z0-9\-]/', '', $guest_url_name);
        $invitation_url = home_url('/' . $guest_url_name);
        
        // Create WhatsApp message with simple emojis for testing
        $message = "Dear {$greeting},\n";
        $message .= "With great joy, we invite you to celebrate the wedding of our children\n";
        $message .= "Fransiskus Dennis Wijaya & Maria Emilia Bewintara\n\n";
        $message .= "📅 *Saturday, 22 Nov 2025*\n\n";
        $message .= "⏰ 09.00 – 10.30\n";
        $message .= "📍 St. Laurensius Alam Sutera\n\n";
        $message .= "⏰ 18.30 – 21.00\n";
        $message .= "📍 JHL Solitaire Gading Serpong\n\n";
        $message .= "Please see the full invitation & RSVP via the link below 👇\n";
        $message .= "🔗 {$invitation_url}\n\n";
        $message .= "⚠️ Kindly RSVP through the website by *19 October 2025*\n";
        $message .= "Please note that we may not be able to accommodate confirmations beyond that date.\n\n";
        $message .= "Warm regards,\n";
        $message .= "Mr. Saleh Widjaja & Mrs. Soesi Wijaya";
        
        // Don't encode on server side, let JavaScript handle it properly
        $message = trim($message);
        
        // Set proper headers for UTF-8
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
        }
        
        wp_send_json_success([
            'phone' => $phone,
            'message' => $message,
            'greeting' => $greeting,
            'url' => $invitation_url
        ]);
    }
    
    /**
     * Handle CSV import
     */
    public function handle_import_csv() {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['nonce'], 'csv_import_nonce')) {
            wp_send_json_error('Security check failed');
        }
        
        // Check if file was uploaded
        if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
            wp_send_json_error('No file uploaded or upload error');
        }
        
        $file = $_FILES['csv_file']['tmp_name'];
        
        // Validate file type
        if (pathinfo($_FILES['csv_file']['name'], PATHINFO_EXTENSION) !== 'csv') {
            wp_send_json_error('Please upload a CSV file');
        }
        
        global $wpdb;
        $imported = 0;
        $errors = [];
        
        // Parse CSV
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $header = fgetcsv($handle); // Read header row
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) < 3) continue; // Skip invalid rows
                
                // Map CSV columns (based on your inv_db.csv structure)
                $csv_data = array_combine($header, $data);
                
                // Extract family members
                $family_members = [];
                for ($i = 2; $i <= 6; $i++) {
                    $guest_key = 'guest_' . $i;
                    if (!empty($csv_data[$guest_key])) {
                        $family_members[] = [
                            'name' => sanitize_text_field($csv_data[$guest_key]),
                            'title' => 'Mr.', // Default title
                            'gender' => 'Male' // Default gender
                        ];
                    }
                }
                
                // Determine relationship type based on guest name patterns
                $relationship_type = 'friend'; // Default
                $guest_name = $csv_data['guest_name'];
                if (stripos($guest_name, 'wijaya') !== false || stripos($guest_name, 'widjaja') !== false) {
                    $relationship_type = 'groom_family';
                } elseif (stripos($guest_name, 'bewintara') !== false || stripos($guest_name, 'pramudi') !== false) {
                    $relationship_type = 'bride_family';
                }
                
                // Insert guest data
                $guest_data = [
                    'primary_guest_name' => sanitize_text_field($csv_data['guest_name']),
                    'title' => 'Mr.', // Default title
                    'gender' => 'Male', // Default gender
                    'phone_number' => sanitize_text_field($csv_data['phone_num'] ?? ''),
                    'pax_num' => intval($csv_data['pax_num'] ?? 1),
                    'invitation_type' => sanitize_text_field($csv_data['type'] ?? 'Digital'),
                    'relationship_type' => $relationship_type,
                    'is_primary' => true,
                    'family_members' => !empty($family_members) ? json_encode($family_members) : null
                ];
                
                $result = $wpdb->insert('wp_wedding_guests', $guest_data);
                
                if ($result) {
                    $imported++;
                } else {
                    $errors[] = "Failed to import: " . $csv_data['guest_name'];
                }
            }
            fclose($handle);
        }
        
        wp_send_json_success([
            'imported' => $imported,
            'errors' => $errors,
            'message' => "Successfully imported {$imported} guests"
        ]);
    }
    
    /**
     * Handle importing existing CSV file
     */
    public function handle_import_existing_csv() {
        // Verify nonce for security
        if (!wp_verify_nonce($_POST['nonce'], 'csv_import_nonce')) {
            wp_send_json_error('Security check failed');
        }
        
        $csv_file = WEDDING_RSVP_PLUGIN_DIR . 'inv_db.csv';
        
        if (!file_exists($csv_file)) {
            wp_send_json_error('inv_db.csv file not found in plugin directory');
        }
        
        global $wpdb;
        $imported = 0;
        $errors = [];
        
        // Parse CSV
        if (($handle = fopen($csv_file, 'r')) !== FALSE) {
            $header = fgetcsv($handle); // Read header row
            
            while (($data = fgetcsv($handle)) !== FALSE) {
                if (count($data) < 3) continue; // Skip invalid rows
                
                // Map CSV columns
                $csv_data = array_combine($header, $data);
                
                // Extract family members
                $family_members = [];
                for ($i = 2; $i <= 6; $i++) {
                    $guest_key = 'guest_' . $i;
                    if (!empty($csv_data[$guest_key])) {
                        // Smart title detection
                        $name = $csv_data[$guest_key];
                        $title = 'Mr.';
                        $gender = 'Male';
                        
                        // Female name patterns
                        if (stripos($name, 'emilia') !== false || 
                            stripos($name, 'patricia') !== false || 
                            stripos($name, 'linarti') !== false ||
                            stripos($name, 'estherina') !== false ||
                            stripos($name, 'odelia') !== false ||
                            stripos($name, 'maria') !== false ||
                            stripos($name, 'veronika') !== false ||
                            stripos($name, 'yanti') !== false ||
                            stripos($name, 'clarecia') !== false ||
                            stripos($name, 'janet') !== false) {
                            $title = 'Mrs.';
                            $gender = 'Female';
                        }
                        
                        $family_members[] = [
                            'name' => sanitize_text_field($name),
                            'title' => $title,
                            'gender' => $gender
                        ];
                    }
                }
                
                // Determine relationship type and primary guest details
                $guest_name = $csv_data['guest_name'];
                $relationship_type = 'friend'; // Default
                $title = 'Mr.';
                $gender = 'Male';
                
                // Family relationship detection
                if (stripos($guest_name, 'wijaya') !== false || stripos($guest_name, 'widjaja') !== false) {
                    $relationship_type = 'groom_family';
                } elseif (stripos($guest_name, 'bewintara') !== false || stripos($guest_name, 'pramudi') !== false) {
                    $relationship_type = 'bride_family';
                }
                
                // Gender and title detection for primary guest
                if (stripos($guest_name, 'dennis') !== false || 
                    stripos($guest_name, 'nikolas') !== false ||
                    stripos($guest_name, 'glenn') !== false ||
                    stripos($guest_name, 'albertus') !== false ||
                    stripos($guest_name, 'kevin') !== false ||
                    stripos($guest_name, 'andi') !== false ||
                    stripos($guest_name, 'eric') !== false ||
                    stripos($guest_name, 'edmund') !== false ||
                    stripos($guest_name, 'sebastian') !== false ||
                    stripos($guest_name, 'joseph') !== false) {
                    $title = 'Mr.';
                    $gender = 'Male';
                } elseif (stripos($guest_name, 'emilia') !== false ||
                          stripos($guest_name, 'linarti') !== false ||
                          stripos($guest_name, 'maria') !== false ||
                          stripos($guest_name, 'janet') !== false ||
                          stripos($guest_name, 'clarecia') !== false) {
                    $title = 'Mrs.';
                    $gender = 'Female';
                }
                
                // Insert guest data
                $guest_data = [
                    'primary_guest_name' => sanitize_text_field($guest_name),
                    'title' => $title,
                    'gender' => $gender,
                    'phone_number' => sanitize_text_field($csv_data['phone_num'] ?? ''),
                    'pax_num' => intval($csv_data['pax_num'] ?? 1),
                    'invitation_type' => sanitize_text_field($csv_data['type'] ?? 'Digital'),
                    'relationship_type' => $relationship_type,
                    'is_primary' => true,
                    'family_members' => !empty($family_members) ? json_encode($family_members) : null
                ];
                
                $result = $wpdb->insert('wp_wedding_guests', $guest_data);
                
                if ($result) {
                    $imported++;
                } else {
                    $errors[] = "Failed to import: " . $guest_name;
                }
            }
            fclose($handle);
        }
        
        wp_send_json_success([
            'imported' => $imported,
            'errors' => $errors,
            'message' => "Successfully imported {$imported} guests from inv_db.csv"
        ]);
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            'Wedding RSVP',
            'Wedding RSVP',
            'manage_options',
            'wedding-rsvp',
            array($this, 'admin_page'),
            'dashicons-heart',
            30
        );
        
        add_submenu_page(
            'wedding-rsvp',
            'Manage Families',
            'Families',
            'manage_options',
            'wedding-families',
            array($this, 'families_admin_page')
        );
        
        add_submenu_page(
            'wedding-rsvp',
            'RSVP Responses',
            'Responses',
            'manage_options',
            'wedding-responses',
            array($this, 'responses_admin_page')
        );
        
        add_submenu_page(
            'wedding-rsvp',
            'Image Settings',
            'Images',
            'manage_options',
            'wedding-images',
            array($this, 'images_admin_page')
        );
        
        add_submenu_page(
            'wedding-rsvp',
            'Import CSV',
            'Import CSV',
            'manage_options',
            'wedding-import',
            array($this, 'import_admin_page')
        );
    }
    
    /**
     * Admin page callbacks
     */
    public function admin_page() {
        include WEDDING_RSVP_PLUGIN_DIR . 'admin/admin-dashboard.php';
    }
    
    public function families_admin_page() {
        include WEDDING_RSVP_PLUGIN_DIR . 'admin/admin-families.php';
    }
    
    public function responses_admin_page() {
        include WEDDING_RSVP_PLUGIN_DIR . 'admin/admin-responses.php';
    }
    
    public function images_admin_page() {
        include WEDDING_RSVP_PLUGIN_DIR . 'admin/admin-images.php';
    }
    
    public function import_admin_page() {
        include WEDDING_RSVP_PLUGIN_DIR . 'admin/admin-import.php';
    }
}

// Initialize the plugin
WeddingRSVP::get_instance();

// Helper functions for templates
function get_wedding_family_data() {
    global $wedding_family_data;
    return $wedding_family_data;
}

function get_wedding_guests_data() {
    global $wedding_guests_data;
    return $wedding_guests_data;
}

function get_wedding_events_data() {
    return WeddingRSVP::get_instance()->get_wedding_events();
}

function get_wedding_section_image($section_key, $default = 's.jpg') {
    $custom_image = get_option('wedding_' . $section_key . '_image', '');
    
    if (!empty($custom_image)) {
        return $custom_image;
    }
    
    // Return default image path
    return get_template_directory_uri() . '/assets/images/' . $default;
}

function get_hero_background_image($is_mobile = false) {
    if ($is_mobile) {
        $image_url = get_option('wedding_hero_mobile_image', '');
        if (!empty($image_url)) {
            return $image_url;
        }
        // Fallback to default mobile image
        return get_template_directory_uri() . '/assets/images/s.jpg';
    } else {
        $image_url = get_option('wedding_hero_desktop_image', '');
        if (!empty($image_url)) {
            return $image_url;
        }
        // Fallback to default desktop image
        return get_template_directory_uri() . '/assets/images/s.jpg';
    }
}

function format_smart_wedding_greeting($family_data, $guests_data) {
    if (empty($guests_data)) {
        return 'DEAR GUEST,';
    }
    
    // Primary guest is the main guest
    $primary_guest = $guests_data[0];
    
    // Get family members from JSON
    $family_members = [];
    if (!empty($primary_guest->family_members)) {
        $family_members = json_decode($primary_guest->family_members, true) ?? [];
    }
    
    // If only one guest (primary)
    if (empty($family_members)) {
        $title = $primary_guest->title ?? 'Mr.';
        $name = $primary_guest->primary_guest_name;
        return 'DEAR ' . $title . ' ' . strtoupper($name) . ',';
    }
    
    // Multiple guests - format based on relationships
    $total_guests = count($family_members) + 1; // +1 for primary guest
    
    // Handle couples (2 people)
    if ($total_guests == 2) {
        $primary_title = $primary_guest->title ?? 'Mr.';
        $primary_name = $primary_guest->primary_guest_name;
        $secondary = $family_members[0];
        $secondary_title = $secondary['title'] ?? 'Mrs.';
        $secondary_name = $secondary['name'];
        
        // Extract first and last names
        $primary_parts = explode(' ', trim($primary_name));
        $secondary_parts = explode(' ', trim($secondary_name));
        
        $primary_first = $primary_parts[0];
        $secondary_first = $secondary_parts[0];
        
        // Check if they have the same last name
        $primary_last = count($primary_parts) > 1 ? $primary_parts[count($primary_parts) - 1] : '';
        $secondary_last = count($secondary_parts) > 1 ? $secondary_parts[count($secondary_parts) - 1] : '';
        
        // Professional titles handling
        $professional_titles = ['Dr.', 'Prof.', 'Rev.', 'Hon.', 'Capt.', 'Col.', 'Gen.', 'Adm.'];
        $primary_is_professional = in_array($primary_title, $professional_titles);
        $secondary_is_professional = in_array($secondary_title, $professional_titles);
        
        // Both have same professional title and same last name
        if ($primary_is_professional && $secondary_is_professional && 
            $primary_title === $secondary_title && $primary_last === $secondary_last && !empty($primary_last)) {
            $title_plural = ($primary_title === 'Dr.') ? 'DRS.' : $primary_title . 'S';
            return 'DEAR ' . $title_plural . ' ' . strtoupper($primary_last) . ',';
        }
        
        // Traditional couple formatting (same last name)
        if ($primary_last === $secondary_last && !empty($primary_last) && 
            (($primary_title === 'Mr.' && $secondary_title === 'Mrs.') || 
             ($primary_title === 'Mrs.' && $secondary_title === 'Mr.'))) {
            return 'DEAR MR. AND MRS. ' . strtoupper($primary_last) . ',';
        }
        
        // Different names or professional titles
        if ($primary_is_professional || $secondary_is_professional || $primary_last !== $secondary_last) {
            return 'DEAR ' . $primary_title . ' ' . strtoupper($primary_first) . ' ' . strtoupper($primary_last) . 
                   ' AND ' . $secondary_title . ' ' . strtoupper($secondary_first) . ' ' . strtoupper($secondary_last) . ',';
        }
        
        // Default couple format
        return 'DEAR ' . $primary_title . ' ' . strtoupper($primary_first) . 
               ' AND ' . $secondary_title . ' ' . strtoupper($secondary_first) . ' ' . strtoupper($primary_last) . ',';
    }
    
    // Family with children (3+ people)
    if ($total_guests >= 3) {
        $professional_titles = ['Dr.', 'Prof.', 'Rev.', 'Hon.', 'Capt.', 'Col.', 'Gen.', 'Adm.'];
        $primary_title = $primary_guest->title ?? 'Mr.';
        $primary_name = $primary_guest->primary_guest_name;
        $primary_parts = explode(' ', trim($primary_name));
        $primary_first = $primary_parts[0];
        $primary_last = count($primary_parts) > 1 ? $primary_parts[count($primary_parts) - 1] : $primary_name;
        
        // Check if there's a spouse (assume first family member)
        $spouse = $family_members[0] ?? null;
        if ($spouse) {
            $spouse_title = $spouse['title'] ?? 'Mrs.';
            
            // Traditional family format with full primary name
            if (($primary_title === 'Mr.' && $spouse_title === 'Mrs.') || 
                ($primary_title === 'Mrs.' && $spouse_title === 'Mr.')) {
                return 'DEAR ' . $primary_title . ' ' . strtoupper($primary_name) . ' AND FAMILY,';
            }
            
            // Professional family format with full primary name
            if (in_array($primary_title, $professional_titles)) {
                return 'DEAR ' . $primary_title . ' ' . strtoupper($primary_name) . ' AND FAMILY,';
            }
        }
        
        // Default family format with full primary name
        return 'DEAR ' . $primary_title . ' ' . strtoupper($primary_name) . ' AND FAMILY,';
    }
    
    // Fallback
    return 'DEAR ' . ($primary_guest->title ?? 'Mr.') . ' ' . strtoupper($primary_guest->primary_guest_name) . ',';
}

// Initialize the plugin
WeddingRSVP::get_instance();
?>
