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
    private $table_rsvp_submissions;
    
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
        $this->table_rsvp_submissions = $wpdb->prefix . 'wedding_rsvp_submissions';
        
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
        add_action('wp_ajax_wedding_family_rsvp_submit', array($this, 'handle_family_rsvp_submission'));
        add_action('wp_ajax_nopriv_wedding_family_rsvp_submit', array($this, 'handle_family_rsvp_submission'));
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
        // Add rewrite rule for family codes - more specific pattern
        add_rewrite_rule('^([a-zA-Z0-9\-]+)/?$', 'index.php?family_code=$matches[1]', 'top');
        
        // Register the query variables
        add_filter('query_vars', array($this, 'add_query_vars'));
    }
    
    /**
     * Add custom query variables
     */
    public function add_query_vars($vars) {
        $vars[] = 'family_code';
        $vars[] = 'familly_code'; // Support the misspelled version too
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
            attending_members text,
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
        
        // New comprehensive RSVP submissions table
        $sql_rsvp_submissions = "CREATE TABLE {$this->table_rsvp_submissions} (
            id int(11) NOT NULL AUTO_INCREMENT,
            family_id int(11) NOT NULL,
            guest_id int(11) NOT NULL,
            primary_guest_name varchar(255) NOT NULL,
            family_code varchar(100) NOT NULL,
            attendance_status enum('yes','no') DEFAULT 'no',
            selected_events text NOT NULL COMMENT 'JSON array of selected events',
            attending_members text NOT NULL COMMENT 'JSON array of attending family member names',
            dietary_requirements text,
            additional_notes text,
            wishes text COMMENT 'Wedding wishes/messages',
            submitted_at timestamp DEFAULT CURRENT_TIMESTAMP,
            updated_at timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY unique_family_submission (family_id),
            KEY idx_guest (guest_id),
            KEY idx_family_code (family_code),
            KEY idx_attendance (attendance_status)
        ) $charset_collate;";
        
        // Create tables
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_families);
        dbDelta($sql_guests);
        dbDelta($sql_rsvp);
        dbDelta($sql_events);
        dbDelta($sql_guest_invitations);
        dbDelta($sql_rsvp_submissions);
        
        // Add attending_members column if it doesn't exist
        $column_exists = $wpdb->get_results("SHOW COLUMNS FROM {$this->table_rsvp} LIKE 'attending_members'");
        if (empty($column_exists)) {
            $wpdb->query("ALTER TABLE {$this->table_rsvp} ADD COLUMN attending_members TEXT AFTER attendance_status");
        }
        
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
        $url_slug = '';
        
        // Method 1: Check for query parameter (e.g., ?familly_code=dennis-wijaya)
        if (isset($_GET['familly_code']) && !empty($_GET['familly_code'])) {
            $url_slug = sanitize_text_field($_GET['familly_code']);
            // Remove trailing slash if present
            $url_slug = rtrim($url_slug, '/');
        }
        // Method 2: Check for clean URL (e.g., /dennis-wijaya)
        elseif (isset($_GET['family_code']) && !empty($_GET['family_code'])) {
            $url_slug = sanitize_text_field($_GET['family_code']);
        }
        // Method 3: Parse URL path for /wedding-invitation/dennis-wijaya format
        else {
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
            }
        }
        
        // If we found a URL slug, process it
        if (!empty($url_slug)) {
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
     * Handle Family RSVP form submission (new comprehensive table)
     */
    public function handle_family_rsvp_submission() {
        // Log the start of the function
        error_log('=== Wedding RSVP Submission Started ===');
        error_log('POST data: ' . print_r($_POST, true));
        
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'wedding_rsvp_nonce')) {
            error_log('Nonce verification failed');
            wp_send_json_error('Security check failed');
        }
        
        error_log('Nonce verification passed');
        
        global $wpdb;
        
        // Get family data to validate
        $family_code = sanitize_text_field($_POST['family_code'] ?? '');
        error_log('Family code received: ' . $family_code);
        
        // If no family_code, try to get it from guest name
        if (empty($family_code)) {
            error_log('No family_code provided, checking for alternative ways to identify guest');
            wp_send_json_error('Family code is required');
        }
        
        // First try to get guest data directly (for individual invitations)
        global $wpdb;
        $guest_data = null;
        $family_data = null;
        
        // Convert family code back to guest name (dennis-wijaya -> Dennis Wijaya)
        $guest_name = str_replace('-', ' ', $family_code);
        $guest_name = ucwords($guest_name);
        error_log('Looking for guest name: ' . $guest_name);
        
        // Look for guest by name
        $guest_data = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$this->table_guests} WHERE primary_guest_name = %s",
            $guest_name
        ));
        
        error_log('Guest data found: ' . ($guest_data ? 'YES' : 'NO'));
        if ($guest_data) {
            error_log('Guest data: ' . print_r($guest_data, true));
        }
        
        if (!$guest_data) {
            // Fallback: try to get family data
            $family_data = $this->get_family_by_code($family_code);
            error_log('Family data found: ' . ($family_data ? 'YES' : 'NO'));
            if (!$family_data) {
                error_log('No guest or family data found - sending error');
                wp_send_json_error('Invalid family/guest code');
            }
        }
        
        // 🔍 DEBUG: Log raw POST data to see what's actually being received
        error_log('🔍 === RAW POST DATA DEBUG ===');
        error_log('🔍 Complete $_POST array: ' . print_r($_POST, true));
        error_log('🔍 Raw selected_events: ' . var_export($_POST['selected_events'] ?? 'NOT_SET', true));
        error_log('🔍 Raw attending_members: ' . var_export($_POST['attending_members'] ?? 'NOT_SET', true));
        error_log('🔍 Type of selected_events: ' . gettype($_POST['selected_events'] ?? 'NOT_SET'));
        error_log('🔍 Type of attending_members: ' . gettype($_POST['attending_members'] ?? 'NOT_SET'));
        
        // Sanitize input data
        $guest_id = intval($_POST['guest_id']);
        $attendance_status = sanitize_text_field($_POST['attendance_status']);
        $selected_events = isset($_POST['selected_events']) ? $_POST['selected_events'] : [];
        $attending_members = isset($_POST['attending_members']) ? $_POST['attending_members'] : [];
        
        // 🔍 DEBUG: Check if we need to parse JSON strings
        if (is_string($selected_events)) {
            error_log('🔧 selected_events is a string, attempting JSON decode: ' . $selected_events);
            
            // Remove WordPress automatic slashes first
            $clean_events_string = stripslashes($selected_events);
            error_log('🧹 After stripslashes: ' . $clean_events_string);
            
            $decoded_events = json_decode($clean_events_string, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_events)) {
                $selected_events = $decoded_events;
                error_log('✅ Successfully decoded selected_events: ' . print_r($selected_events, true));
            } else {
                error_log('❌ Failed to decode selected_events JSON, error: ' . json_last_error_msg());
                // Try to parse as comma-separated string
                $selected_events = array_map('trim', explode(',', $clean_events_string));
                error_log('🔧 Parsed as comma-separated: ' . print_r($selected_events, true));
            }
        }
        
        if (is_string($attending_members)) {
            error_log('🔧 attending_members is a string, attempting JSON decode: ' . $attending_members);
            
            // Remove WordPress automatic slashes first  
            $clean_members_string = stripslashes($attending_members);
            error_log('🧹 After stripslashes: ' . $clean_members_string);
            
            $decoded_members = json_decode($clean_members_string, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded_members)) {
                $attending_members = $decoded_members;
                error_log('✅ Successfully decoded attending_members: ' . print_r($attending_members, true));
            } else {
                error_log('❌ Failed to decode attending_members JSON, error: ' . json_last_error_msg());
                // Try to parse as comma-separated string
                $attending_members = array_map('trim', explode(',', $clean_members_string));
                error_log('🔧 Parsed as comma-separated: ' . print_r($attending_members, true));
            }
        }
        
        $dietary_requirements = sanitize_textarea_field($_POST['dietary_requirements'] ?? '');
        $additional_notes = sanitize_textarea_field($_POST['additional_notes'] ?? '');
        $wedding_wishes = sanitize_textarea_field($_POST['wedding_wishes'] ?? '');
        
        error_log('Sanitized data:');
        error_log('- guest_id: ' . $guest_id);
        error_log('- attendance_status: ' . $attendance_status);
        error_log('- selected_events: ' . print_r($selected_events, true));
        error_log('- attending_members: ' . print_r($attending_members, true));
        
        // Validate attendance status
        if (!in_array($attendance_status, ['yes', 'no'])) {
            error_log('Invalid attendance status: ' . $attendance_status);
            wp_send_json_error('Invalid attendance status');
        }
        
        // Use guest data if available, otherwise use family data
        $primary_guest_name = $guest_data ? $guest_data->primary_guest_name : ($family_data ? $family_data->family_name : 'Unknown');
        $family_id = $guest_data ? $guest_data->id : ($family_data ? $family_data->id : 0);
        $actual_guest_id = $guest_data ? $guest_data->id : $guest_id;
        
        error_log('Final data for database:');
        error_log('- primary_guest_name: ' . $primary_guest_name);
        error_log('- family_id: ' . $family_id);
        error_log('- actual_guest_id: ' . $actual_guest_id);
        
        // 🔧 CLEAN JSON ENCODING - Avoid double escaping
        error_log('🔧 Pre-JSON arrays:');
        error_log('🔧 selected_events type: ' . gettype($selected_events) . ', content: ' . print_r($selected_events, true));
        error_log('🔧 attending_members type: ' . gettype($attending_members) . ', content: ' . print_r($attending_members, true));
        
        // Only sanitize if arrays contain strings, avoid over-sanitizing already clean data
        if (is_array($selected_events)) {
            $clean_events = array_map(function($event) {
                // Aggressively remove slashes and clean the data
                if (is_string($event)) {
                    $cleaned = $event;
                    // Remove multiple levels of slashes
                    while (strpos($cleaned, '\\') !== false) {
                        $cleaned = stripslashes($cleaned);
                    }
                    return trim(strip_tags($cleaned));
                }
                return $event;
            }, $selected_events);
        } else {
            $clean_events = [];
        }
        
        if (is_array($attending_members)) {
            $clean_members = array_map(function($member) {
                // Aggressively remove slashes and clean the data
                if (is_string($member)) {
                    $cleaned = $member;
                    // Remove multiple levels of slashes
                    while (strpos($cleaned, '\\') !== false) {
                        $cleaned = stripslashes($cleaned);
                    }
                    return trim(strip_tags($cleaned));
                }
                return $member;
            }, $attending_members);
        } else {
            $clean_members = [];
        }
        
        // Convert arrays to clean JSON without extra escaping
        $selected_events_json = json_encode($clean_events, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $attending_members_json = json_encode($clean_members, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        
        error_log('JSON data:');
        error_log('- selected_events_json: ' . $selected_events_json);
        error_log('- attending_members_json: ' . $attending_members_json);
        
        // 🔧 FINAL SLASH REMOVAL before database insertion
        $final_events_json = wp_unslash($selected_events_json);
        $final_members_json = wp_unslash($attending_members_json);
        
        error_log('🔧 Final JSON after wp_unslash:');
        error_log('- final_events_json: ' . $final_events_json);
        error_log('- final_members_json: ' . $final_members_json);
        
        try {
            error_log('Starting database insert/update...');
            
            // Insert or update the comprehensive RSVP submission
            $result = $wpdb->replace(
                $this->table_rsvp_submissions,
                array(
                    'family_id' => $family_id,
                    'guest_id' => $actual_guest_id,
                    'primary_guest_name' => $primary_guest_name,
                    'family_code' => $family_code,
                    'attendance_status' => $attendance_status,
                    'selected_events' => $final_events_json,
                    'attending_members' => $final_members_json,
                    'dietary_requirements' => $dietary_requirements,
                    'additional_notes' => $additional_notes,
                    'wishes' => $wedding_wishes
                ),
                array('%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );
            
            error_log('Database operation result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            error_log('wpdb->last_error: ' . $wpdb->last_error);
            error_log('wpdb->insert_id: ' . $wpdb->insert_id);
            
            if ($result === false) {
                throw new Exception('Database error: ' . $wpdb->last_error);
            }
            
            error_log('Sending success response...');
            wp_send_json_success(array(
                'message' => 'RSVP submitted successfully',
                'attendance' => $attendance_status,
                'submission_id' => $wpdb->insert_id ?: $wpdb->get_var("SELECT id FROM {$this->table_rsvp_submissions} WHERE family_id = {$family_id}")
            ));
            
        } catch (Exception $e) {
            error_log('Wedding RSVP Submission Error: ' . $e->getMessage());
            error_log('Exception trace: ' . $e->getTraceAsString());
            wp_send_json_error('Failed to save RSVP. Please try again.');
        }
        
        error_log('=== Wedding RSVP Submission Ended ===');
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
        $message .= "Dennis Wijaya & Emilia Pramudi Bewintara";
        
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
                        $family_members[] = sanitize_text_field($csv_data[$guest_key]);
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
                        $family_members[] = sanitize_text_field($csv_data[$guest_key]);
                    }
                }
                
                // Determine relationship type
                $guest_name = $csv_data['guest_name'];
                $relationship_type = 'friend'; // Default
                
                // Family relationship detection
                if (stripos($guest_name, 'wijaya') !== false || stripos($guest_name, 'widjaja') !== false) {
                    $relationship_type = 'groom_family';
                } elseif (stripos($guest_name, 'bewintara') !== false || stripos($guest_name, 'pramudi') !== false) {
                    $relationship_type = 'bride_family';
                }
                
                // Insert guest data
                $guest_data = [
                    'primary_guest_name' => sanitize_text_field($guest_name),
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

function format_smart_wedding_greeting($guest_data) {
    // Simplified function - just takes the guest data directly
    if (empty($guest_data)) {
        return 'DEAR GUEST,';
    }
    
    // Use guest_data directly
    $primary_guest = $guest_data;
    
    // Get family members - they're already an array, no need to decode
    $family_members = [];
    if (!empty($primary_guest->family_members)) {
        // Check if it's already an array or needs decoding
        if (is_array($primary_guest->family_members)) {
            $family_members = $primary_guest->family_members;
        } else {
            $family_members = json_decode($primary_guest->family_members, true) ?? [];
        }
    }
    
    // If only one guest (primary)
    if (empty($family_members)) {
        $name = $primary_guest->primary_guest_name;
        return 'DEAR ' . strtoupper($name) . ',';
    }
    
    // Multiple guests - simple format
    $total_guests = count($family_members) + 1; // +1 for primary guest
    
    // Handle couples (2 people)
    if ($total_guests == 2) {
        $primary_name = $primary_guest->primary_guest_name;
        $secondary_name = $family_members[0]; // Direct string now
        
        // Get last names
        $primary_parts = explode(' ', trim($primary_name));
        $primary_first = $primary_parts[0];
        $primary_last = count($primary_parts) > 1 ? $primary_parts[count($primary_parts) - 1] : '';
        
        $secondary_parts = explode(' ', trim($secondary_name));
        $secondary_first = $secondary_parts[0];
        $secondary_last = count($secondary_parts) > 1 ? $secondary_parts[count($secondary_parts) - 1] : '';
        
        // If same last name, use family format
        if (!empty($primary_last) && $primary_last === $secondary_last) {
            return 'DEAR ' . strtoupper($primary_first) . ' AND ' . strtoupper($secondary_first) . ' ' . strtoupper($primary_last) . ',';
        }
        
        // Different names - use full names
        return 'DEAR ' . strtoupper($primary_first) . ' ' . strtoupper($primary_last) . 
               ' AND ' . strtoupper($secondary_first) . ' ' . strtoupper($secondary_last) . ',';
    }
    
    // Family with children (3+ people)
    if ($total_guests >= 3) {
        $primary_name = $primary_guest->primary_guest_name;
        $primary_parts = explode(' ', trim($primary_name));
        $primary_first = $primary_parts[0];
        $primary_last = count($primary_parts) > 1 ? $primary_parts[count($primary_parts) - 1] : $primary_name;
        
        // Check if there's a spouse (assume first family member)
        $spouse_name = $family_members[0] ?? null;
        if ($spouse_name) {
            $spouse_parts = explode(' ', trim($spouse_name));
            $spouse_last = count($spouse_parts) > 1 ? $spouse_parts[count($spouse_parts) - 1] : $spouse_name;
            
            // If same last name, use family format
            if (!empty($primary_last) && $primary_last === $spouse_last) {
                return 'DEAR ' . strtoupper($primary_first) . ' AND ' . strtoupper($spouse_parts[0]) . ' ' . strtoupper($primary_last) . ' AND FAMILY,';
            }
        }
        
        // Default family format
        return 'DEAR ' . strtoupper($primary_name) . ' AND FAMILY,';
    }
    
    // Fallback
    return 'DEAR ' . strtoupper($primary_guest->primary_guest_name) . ',';
}

// Initialize the plugin
WeddingRSVP::get_instance();
?>
