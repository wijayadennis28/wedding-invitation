<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

$table_families = $wpdb->prefix . 'wedding_families';
$table_guests = $wpdb->prefix . 'wedding_guests';
$table_rsvp = $wpdb->prefix . 'wedding_rsvp_responses';
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

// Get responses by event - simplified approach
// First get all active events
$events = $wpdb->get_results("SELECT * FROM $table_events WHERE is_active = 1 ORDER BY event_date, event_time");

$event_stats = array();
foreach ($events as $event) {
    // Get responses for this specific event
    $attending = (int) $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) FROM $table_rsvp r 
        INNER JOIN $table_guests g ON r.guest_id = g.id 
        INNER JOIN $table_families f ON g.family_id = f.id 
        WHERE f.is_active = 1 AND r.event_type = %s AND r.attendance_status = 'yes'
    ", $event->event_type));
    
    $not_attending = (int) $wpdb->get_var($wpdb->prepare("
        SELECT COUNT(*) FROM $table_rsvp r 
        INNER JOIN $table_guests g ON r.guest_id = g.id 
        INNER JOIN $table_families f ON g.family_id = f.id 
        WHERE f.is_active = 1 AND r.event_type = %s AND r.attendance_status = 'no'
    ", $event->event_type));
    
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
    
    <!-- Family Response Overview -->
    <div class="card">
        <h2>Family Response Status</h2>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Family Name</th>
                    <th>Family Code</th>
                    <th>Guests</th>
                    <th>Max Guests</th>
                    <th>Responses</th>
                    <th>Status</th>
                    <th>Wedding URL</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($family_responses as $family): ?>
                <tr>
                    <td><strong><?php echo esc_html($family->family_name); ?></strong></td>
                    <td><code><?php echo esc_html($family->family_code); ?></code></td>
                    <td><?php echo $family->guest_count; ?></td>
                    <td><?php echo $family->max_guests; ?></td>
                    <td><?php echo $family->response_count; ?></td>
                    <td>
                        <?php 
                        $event_count = is_array($event_stats) ? count($event_stats) : 0;
                        $expected_responses = $family->guest_count * $event_count;
                        if ($event_count == 0) {
                            echo '<span style="color: #999; font-style: italic;">No Events</span>';
                        } elseif ($family->response_count == 0) {
                            echo '<span style="color: #d63638; font-weight: bold;">No Response</span>';
                        } elseif ($family->response_count < $expected_responses) {
                            echo '<span style="color: #dba617; font-weight: bold;">Partial</span>';
                        } else {
                            echo '<span style="color: #00a32a; font-weight: bold;">Complete</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="<?php echo home_url('/' . $family->family_code); ?>" target="_blank" class="button button-small">
                            View Site
                        </a>
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
