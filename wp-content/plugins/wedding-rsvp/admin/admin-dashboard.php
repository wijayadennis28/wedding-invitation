<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

$table_families = $wpdb->prefix . 'wedding_families';
$table_guests = $wpdb->prefix . 'wedding_guests';
$table_rsvp = $wpdb->prefix . 'wedding_rsvp_submissions';
$table_events = $wpdb->prefix . 'wedding_events';

// Get RSVP statistics (ensure we always get at least 0)
$total_families = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_families WHERE is_active = 1") ?: 0;
$total_guests = (int) $wpdb->get_var("
    SELECT COUNT(*) FROM $table_guests g 
    INNER JOIN $table_families f ON g.family_id = f.id 
    WHERE f.is_active = 1
") ?: 0;
$total_responses = (int) $wpdb->get_var("
    SELECT COUNT(*) FROM $table_rsvp r 
    INNER JOIN $table_guests g ON r.guest_id = g.id 
    INNER JOIN $table_families f ON g.family_id = f.id 
    WHERE f.is_active = 1
") ?: 0;

// Get responses by event - fixed to work with JSON selected_events
// First get all active events
$events = $wpdb->get_results("SELECT * FROM $table_events WHERE is_active = 1 ORDER BY event_date, event_time");

echo "<!-- Debug: Table name being used: $table_rsvp -->";
echo "<!-- Debug: Total records in RSVP table: " . $wpdb->get_var("SELECT COUNT(*) FROM $table_rsvp") . " -->";

$event_stats = array();
foreach ($events as $event) {
    echo "<!-- Debug: Processing event: " . $event->event_name . " (type: " . $event->event_type . ") -->";
    
    // Get all RSVP submissions - simplified for debugging
    $all_submissions = $wpdb->get_results("
        SELECT selected_events, attendance_status 
        FROM $table_rsvp
    ");
    
    echo "<!-- Debug: Found " . count($all_submissions) . " total submissions for processing -->";
    
    $attending = 0;
    $not_attending = 0;
    
    foreach ($all_submissions as $submission) {
        // 🔧 FIXED LOGIC: Handle attendance status properly
        if ($submission->attendance_status === 'no') {
            // If not attending overall, count as "not attending" for ALL events
            $not_attending++;
        } else if ($submission->attendance_status === 'yes') {
            // If attending overall, check which specific events they selected
            $selected_events = json_decode($submission->selected_events, true);
            
            if (is_array($selected_events) && in_array($event->event_type, $selected_events)) {
                // They're attending AND this event is selected
                $attending++;
            }
            // Note: If they're attending but this event is NOT selected, we don't count them for this event
        }
    }
    
    $total_responses = $attending + $not_attending;
    
    $event_stats[] = (object) array(
        'event_name' => $event->event_name,
        'event_type' => $event->event_type,
        'attending' => $attending,
        'not_attending' => $not_attending,
        'total_responses' => $total_responses
    );
}

echo "<!-- Debug: Found " . count($event_stats) . " events -->";
echo "<!-- Debug: Event stats: " . json_encode($event_stats) . " -->";

// NEW: Calculate attending members and souvenir counts for Holy Matrimony and Reception
// Get all accepted RSVP submissions (attendance_status = 'yes')
$accepted_submissions = $wpdb->get_results("
    SELECT selected_events, attending_members 
    FROM $table_rsvp 
    WHERE attendance_status = 'yes'
");

// Initialize counters
$holy_matrimony_attending_members = 0;
$reception_attending_members = 0;
$holy_matrimony_souvenirs = 0;
$reception_souvenirs = 0;

foreach ($accepted_submissions as $submission) {
    $selected_events = json_decode($submission->selected_events, true);
    $attending_members = json_decode($submission->attending_members, true);
    
    // Ensure we have valid arrays
    $selected_events = is_array($selected_events) ? $selected_events : array();
    $attending_members = is_array($attending_members) ? $attending_members : array();
    $member_count = count($attending_members);
    
    // Count attending members and souvenirs for Holy Matrimony (church)
    if (in_array('church', $selected_events)) {
        $holy_matrimony_attending_members += $member_count;
        $holy_matrimony_souvenirs++; // 1 souvenir per family/response
    }
    
    // Count attending members and souvenirs for Reception
    if (in_array('reception', $selected_events)) {
        $reception_attending_members += $member_count;
        $reception_souvenirs++; // 1 souvenir per family/response
    }
}

echo "<!-- Debug: Holy Matrimony - Attending Members: $holy_matrimony_attending_members, Souvenirs: $holy_matrimony_souvenirs -->";
echo "<!-- Debug: Reception - Attending Members: $reception_attending_members, Souvenirs: $reception_souvenirs -->";

// Debug: Let's also see the raw submissions data
$debug_submissions = $wpdb->get_results("
    SELECT selected_events, attendance_status 
    FROM $table_rsvp
");
echo "<!-- Debug: Raw submissions count: " . count($debug_submissions) . " -->";
foreach($debug_submissions as $i => $sub) {
    echo "<!-- Debug submission $i: selected_events=" . $sub->selected_events . ", attendance=" . $sub->attendance_status . " -->";
}

// Get families with response status
$family_responses = $wpdb->get_results("
    SELECT 
        f.family_name,
        f.family_code,
        f.max_guests,
        COUNT(DISTINCT g.id) as guest_count,
        COUNT(DISTINCT r.id) as response_count
    FROM $table_families f
    LEFT JOIN $table_guests g ON f.id = g.family_id
    LEFT JOIN $table_rsvp r ON g.id = r.guest_id
    WHERE f.is_active = 1
    GROUP BY f.id
    ORDER BY f.family_name
");

// Ensure event_stats is an array and handle empty results
if (!is_array($event_stats)) {
    $event_stats = array();
}
?>

<div class="wrap">
    <h1>Wedding RSVP Dashboard</h1>
    
    <?php if (empty($event_stats)): ?>
    <div class="notice notice-warning">
        <p><strong>Notice:</strong> No active events found. Please check that events are properly configured and active.</p>
    </div>
    <?php endif; ?>
    
    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="text-align: center; padding: 20px;">
            <h2 style="margin: 0; color: #007cba; font-size: 2.5em;"><?php echo $total_families; ?></h2>
            <p style="margin: 5px 0 0 0; font-weight: bold;">Total Families</p>
        </div>
        <div class="card" style="text-align: center; padding: 20px;">
            <h2 style="margin: 0; color: #00a32a; font-size: 2.5em;"><?php echo $total_guests; ?></h2>
            <p style="margin: 5px 0 0 0; font-weight: bold;">Total Guests</p>
        </div>
        <div class="card" style="text-align: center; padding: 20px;">
            <h2 style="margin: 0; color: #d63638; font-size: 2.5em;"><?php echo $total_responses; ?></h2>
            <p style="margin: 5px 0 0 0; font-weight: bold;">Total Responses</p>
        </div>
        <div class="card" style="text-align: center; padding: 20px;">
            <?php 
            $event_count = is_array($event_stats) ? count($event_stats) : 0;
            $total_expected_responses = $total_guests * $event_count;
            $response_rate = ($total_expected_responses > 0 && $total_responses > 0) ? round(($total_responses / $total_expected_responses) * 100, 1) : 0;
            ?>
            <h2 style="margin: 0; color: #8f5a00; font-size: 2.5em;"><?php echo $response_rate; ?>%</h2>
            <p style="margin: 5px 0 0 0; font-weight: bold;">Response Rate</p>
        </div>
    </div>
    
    <!-- NEW: Holy Matrimony & Reception Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="card" style="text-align: center; padding: 20px; border-left: 4px solid #8b5a2b;">
            <h3 style="margin: 0 0 15px 0; color: #8b5a2b; font-size: 1.2em; font-weight: bold;">Holy Matrimony</h3>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0; color: #8b5a2b; font-size: 2em;"><?php echo $holy_matrimony_attending_members; ?></h2>
                    <p style="margin: 5px 0 0 0; font-size: 0.9em;">Attending Members</p>
                </div>
                <div>
                    <h2 style="margin: 0; color: #8b5a2b; font-size: 2em;"><?php echo $holy_matrimony_souvenirs; ?></h2>
                    <p style="margin: 5px 0 0 0; font-size: 0.9em;">Souvenirs Needed</p>
                </div>
            </div>
        </div>
        <div class="card" style="text-align: center; padding: 20px; border-left: 4px solid #c8102e;">
            <h3 style="margin: 0 0 15px 0; color: #c8102e; font-size: 1.2em; font-weight: bold;">Reception</h3>
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0; color: #c8102e; font-size: 2em;"><?php echo $reception_attending_members; ?></h2>
                    <p style="margin: 5px 0 0 0; font-size: 0.9em;">Attending Members</p>
                </div>
                <div>
                    <h2 style="margin: 0; color: #c8102e; font-size: 2em;"><?php echo $reception_souvenirs; ?></h2>
                    <p style="margin: 5px 0 0 0; font-size: 0.9em;">Souvenirs Needed</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Event Statistics -->
    <div class="card" style="margin-bottom: 30px;">
        <h2>RSVP by Event</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Attending</th>
                    <th>Not Attending</th>
                    <th>Total Responses</th>
                    <th>Response Rate</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($event_stats as $event): ?>
                <tr>
                    <td><strong><?php echo esc_html($event->event_name); ?></strong></td>
                    <td style="color: #00a32a; font-weight: bold;"><?php echo (int)$event->attending; ?></td>
                    <td style="color: #d63638; font-weight: bold;"><?php echo (int)$event->not_attending; ?></td>
                    <td><?php echo (int)$event->total_responses; ?></td>
                    <td>
                        <?php 
                        $rate = $total_guests > 0 ? round(($event->total_responses / $total_guests) * 100, 1) : 0;
                        echo $rate . '%';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Quick Actions -->
    <div style="margin-top: 30px;">
        <h2>Quick Actions</h2>
        <p>
            <a href="<?php echo admin_url('admin.php?page=wedding-families'); ?>" class="button button-primary">
                Manage Families & Guests
            </a>
            <a href="<?php echo admin_url('admin.php?page=wedding-responses'); ?>" class="button button-secondary">
                View All Responses
            </a>
        </p>
    </div>
</div>
