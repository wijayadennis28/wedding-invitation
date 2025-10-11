<?php
/**
 * Wedding RSVP Template Helper Functions
 * Include this in your front-page.php to render RSVP forms
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Render the RSVP form for the current family
 */
function render_wedding_rsvp_form() {
    $family_data = get_wedding_family_data();
    $guests_data = get_wedding_guests_data();
    $events_data = get_wedding_events_data();
    
    if (!$family_data || empty($guests_data)) {
        return;
    }
    
    // Get the main guest record
    $main_guest = $guests_data[0];
    
    // Extract family members with proper type checking
    if (is_array($main_guest->family_members)) {
        // Already an array, use as-is
        $family_members = $main_guest->family_members;
    } else if (is_string($main_guest->family_members) && !empty($main_guest->family_members)) {
        // It's a JSON string, decode it
        $family_members = json_decode($main_guest->family_members, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // JSON decode failed, treat as single name
            $family_members = [$main_guest->family_members];
        }
    } else {
        // Empty or null, use primary guest name as fallback
        $family_members = [$main_guest->primary_guest_name];
    }
    
    // Ensure we have an array
    if (!is_array($family_members) || empty($family_members)) {
        $family_members = [$main_guest->primary_guest_name];
    }
    
    // Get existing responses for this guest record
    global $wpdb;
    $existing_responses = $wpdb->get_results($wpdb->prepare(
        "SELECT gi.*, e.event_type, e.event_name 
         FROM wp_wedding_guest_invitations gi
         JOIN wp_wedding_events e ON gi.event_id = e.id
         WHERE gi.guest_id = %d",
        $main_guest->id
    ));
    
    // Check which events this guest is invited to
    $invited_events = array();
    foreach ($existing_responses as $invitation) {
        if ($invitation->is_invited === 'yes') {
            $invited_events[] = $invitation->event_type;
        }
    }
    
    // Filter events to only show the ones they're invited to
    $available_events = array_filter($events_data, function($event) use ($invited_events) {
        return in_array($event->event_type, $invited_events);
    });
    
    // Prepare data for JavaScript
    ?>
    <script>
    window.weddingFamilyData = <?php echo json_encode($family_data); ?>;
    window.weddingMainGuest = <?php echo json_encode($main_guest); ?>;
    window.weddingFamilyMembers = <?php echo json_encode($family_members); ?>;
    window.weddingAvailableEvents = <?php echo json_encode($available_events); ?>;
    </script>
    
    <div class="rsvp-messages"></div>
    
    <div class="rsvp-form-container">
        <div class="rsvp-form-header">
            <h2>RSVP</h2>
            <p>Please let us know if you'll be able to celebrate with us</p>
        </div>
        
        <div class="personalized-greeting">
            <h3>Dear</h3>
            <div class="personalized-names"><?php echo esc_html(implode(' & ', $family_members)); ?></div>
        </div>
        
        <form id="wedding-rsvp-form" data-guest-id="<?php echo $main_guest->id; ?>">
            <?php foreach ($available_events as $event): ?>
            <div class="event-response" data-event-type="<?php echo $event->event_type; ?>">
                <div class="event-header">
                    <div class="event-info">
                        <h4><?php echo esc_html($event->event_name); ?></h4>
                        <div class="event-details">
                            <?php if ($event->event_date): ?>
                            <?php echo date('F j, Y', strtotime($event->event_date)); ?>
                            <?php endif; ?>
                            <?php if ($event->event_time): ?>
                            at <?php echo date('g:i A', strtotime($event->event_time)); ?>
                            <?php endif; ?>
                            <?php if ($event->location): ?>
                            <br><?php echo esc_html($event->location); ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <div class="family-members-section">
                    <h5>Who will be attending?</h5>
                    <div class="family-members-list">
                        <?php foreach ($family_members as $index => $member_name): ?>
                        <div class="family-member-response">
                            <label class="member-name"><?php echo esc_html($member_name); ?></label>
                            <div class="attendance-options">
                                <label class="attendance-option">
                                    <input type="radio" 
                                           name="event_<?php echo $event->event_type; ?>_member_<?php echo $index; ?>" 
                                           value="yes" 
                                           class="attendance-radio">
                                    <span>Will Attend</span>
                                </label>
                                
                                <label class="attendance-option">
                                    <input type="radio" 
                                           name="event_<?php echo $event->event_type; ?>_member_<?php echo $index; ?>" 
                                           value="no" 
                                           class="attendance-radio">
                                    <span>Will Not Attend</span>
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="additional-info">
                    <div class="form-group">
                        <label for="dietary_<?php echo $event->event_type; ?>">
                            Dietary Requirements or Allergies
                        </label>
                        <textarea 
                            id="dietary_<?php echo $event->event_type; ?>"
                            name="event_<?php echo $event->event_type; ?>_dietary"
                            placeholder="Please let us know of any dietary restrictions, allergies, or special meal preferences..."
                        ></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="notes_<?php echo $event->event_type; ?>">
                            Additional Notes
                        </label>
                        <textarea 
                            id="notes_<?php echo $event->event_type; ?>"
                            name="event_<?php echo $event->event_type; ?>_notes"
                            placeholder="Any additional notes or special requests..."
                        ></textarea>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            
            <div class="rsvp-submit">
                <button type="submit">Submit RSVP</button>
            </div>
        </form>
    </div>
    
    <div class="rsvp-thank-you">
        <h2>Thank You!</h2>
        <p>Your RSVP has been received. We can't wait to celebrate with you!</p>
        <p>If you need to make any changes, please contact us directly.</p>
    </div>
    
    <style>
    /* Integrate with your existing styles */
    .rsvp-form-container {
        margin: 60px auto;
    }
    
    /* Add styles for better integration with your theme */
    .rsvp-section {
        padding: 80px 0;
        background: rgba(0, 0, 0, 0.1);
    }
    </style>
    <?php
}

/**
 * Get family information for display
 */
function get_family_display_info() {
    $family_data = get_wedding_family_data();
    $guests_data = get_wedding_guests_data();
    
    if (!$family_data || empty($guests_data)) {
        return null;
    }
    
    // Get family members from JSON - with proper type checking
    $main_guest = $guests_data[0];
    
    // Check if family_members is already an array or needs JSON decoding
    $additional_family_members = [];
    if (is_array($main_guest->family_members)) {
        // Already an array, use as-is
        $additional_family_members = $main_guest->family_members;
    } else if (is_string($main_guest->family_members) && !empty($main_guest->family_members)) {
        // It's a JSON string, decode it
        $additional_family_members = json_decode($main_guest->family_members, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            // JSON decode failed, treat as single name
            $additional_family_members = [$main_guest->family_members];
        }
    }
    
    // Ensure additional_family_members is an array and filter out empty values
    if (!is_array($additional_family_members)) {
        $additional_family_members = [];
    }
    $additional_family_members = array_filter($additional_family_members, function($member) {
        return !empty(trim($member));
    });
    
    // Build complete guest names list: primary guest FULL NAME first, then additional family members
    $all_guest_names = [$main_guest->primary_guest_name]; // Always start with full primary guest name
    $all_guest_names = array_merge($all_guest_names, $additional_family_members); // Add additional members
    
    // Total guest count includes primary + additional members
    $total_guest_count = count($all_guest_names);
    
    return array(
        'family_name' => $family_data->family_name,
        'family_code' => $family_data->family_code,
        'max_guests' => $family_data->max_guests,
        'guest_count' => $total_guest_count,
        'guest_names' => $all_guest_names,
        'primary_guest' => $main_guest->primary_guest_name,
        'pax_num' => $main_guest->pax_num,
        'invitation_type' => $main_guest->invitation_type
    );
}

/**
 * Check if this is a valid family URL
 */
function is_wedding_family_page() {
    return !empty(get_wedding_family_data());
}

/**
 * Get formatted guest names for display
 */
function get_formatted_guest_names($style = 'and') {
    $family_info = get_family_display_info();
    
    if (!$family_info || empty($family_info['guest_names'])) {
        return '';
    }
    
    $names = $family_info['guest_names'];
    
    if (count($names) === 1) {
        return $names[0];
    } elseif (count($names) === 2) {
        return implode(' ' . $style . ' ', $names);
    } else {
        // For 3+ people, use "Primary Guest and Family" format
        $family_info = get_family_display_info();
        return $family_info['primary_guest'] . ' ' . $style . ' Family';
    }
}

/**
 * Display wedding events information
 */
function display_wedding_events() {
    $events = get_wedding_events_data();
    
    foreach ($events as $event) {
        echo '<div class="wedding-event">';
        echo '<h3>' . esc_html($event->event_name) . '</h3>';
        
        if ($event->event_date) {
            echo '<p class="event-date">' . date('F j, Y', strtotime($event->event_date)) . '</p>';
        }
        
        if ($event->event_time) {
            echo '<p class="event-time">' . date('g:i A', strtotime($event->event_time)) . '</p>';
        }
        
        if ($event->location) {
            echo '<p class="event-location">' . esc_html($event->location) . '</p>';
        }
        
        if ($event->description) {
            echo '<p class="event-description">' . esc_html($event->description) . '</p>';
        }
        
        echo '</div>';
    }
}
