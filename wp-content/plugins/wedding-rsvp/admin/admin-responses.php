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

// Get filter parameters
$selected_family = isset($_GET['family_filter']) ? intval($_GET['family_filter']) : '';
$selected_event = isset($_GET['event_filter']) ? sanitize_text_field($_GET['event_filter']) : '';

// Build query
$where_conditions = array();
$where_params = array();

if ($selected_family) {
    $where_conditions[] = "r.family_id = %d";
    $where_params[] = $selected_family;
}

if ($selected_event) {
    $where_conditions[] = "r.event_type = %s";
    $where_params[] = $selected_event;
}

$where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

// Get RSVP responses
$query = "
    SELECT 
        r.*,
        f.family_name,
        f.family_code,
        g.guest_name,
        g.phone_number,
        e.event_name,
        e.event_date,
        e.event_time
    FROM $table_rsvp r
    JOIN $table_families f ON r.family_id = f.id
    JOIN $table_guests g ON r.guest_id = g.id
    JOIN $table_events e ON r.event_type = e.event_type
    $where_clause
    ORDER BY r.responded_at DESC
";

if (!empty($where_params)) {
    $responses = $wpdb->get_results($wpdb->prepare($query, $where_params));
} else {
    $responses = $wpdb->get_results($query);
}

// Get families for filter dropdown
$families = $wpdb->get_results("SELECT id, family_name FROM $table_families WHERE is_active = 1 ORDER BY family_name");

// Get events for filter dropdown
$events = $wpdb->get_results("SELECT event_type, event_name FROM $table_events WHERE is_active = 1 ORDER BY event_date");
?>

<div class="wrap">
    <h1>RSVP Responses</h1>
    
    <!-- Filters -->
    <div class="tablenav top">
        <form method="get" action="">
            <input type="hidden" name="page" value="wedding-responses">
            <div class="alignleft actions">
                <select name="family_filter">
                    <option value="">All Families</option>
                    <?php foreach ($families as $family): ?>
                    <option value="<?php echo $family->id; ?>" <?php selected($selected_family, $family->id); ?>>
                        <?php echo esc_html($family->family_name); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                
                <select name="event_filter">
                    <option value="">All Events</option>
                    <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event->event_type; ?>" <?php selected($selected_event, $event->event_type); ?>>
                        <?php echo esc_html($event->event_name); ?>
                    </option>
                    <?php endforeach; ?>
                </select>
                
                <input type="submit" class="button" value="Filter">
                <a href="<?php echo admin_url('admin.php?page=wedding-responses'); ?>" class="button">Clear</a>
            </div>
        </form>
        
        <div class="alignright actions">
            <button type="button" class="button" onclick="exportResponses()">Export CSV</button>
        </div>
    </div>
    
    <!-- Results Summary -->
    <?php if (!empty($responses)): ?>
    <div style="background: #f0f8ff; padding: 15px; margin-bottom: 20px; border-left: 4px solid #007cba;">
        <strong>Results:</strong> <?php echo count($responses); ?> responses found
        <?php if ($selected_family || $selected_event): ?>
        (filtered)
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <!-- Responses Table -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">Family</th>
                <th scope="col">Guest Name</th>
                <th scope="col">Event</th>
                <th scope="col">Attendance</th>
                <th scope="col">Dietary Requirements</th>
                <th scope="col">Notes</th>
                <th scope="col">Responded</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($responses)): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px;">
                    <em>No RSVP responses found.</em>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($responses as $response): ?>
                <tr>
                    <td>
                        <strong><?php echo esc_html($response->family_name); ?></strong><br>
                        <small><code><?php echo esc_html($response->family_code); ?></code></small>
                    </td>
                    <td>
                        <?php echo esc_html($response->guest_name); ?>
                        <?php if ($response->phone_number): ?>
                        <br><small><?php echo esc_html($response->phone_number); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo esc_html($response->event_name); ?></strong><br>
                        <small>
                            <?php echo date('M j, Y', strtotime($response->event_date)); ?>
                            <?php if ($response->event_time): ?>
                            at <?php echo date('g:i A', strtotime($response->event_time)); ?>
                            <?php endif; ?>
                        </small>
                    </td>
                    <td>
                        <?php
                        $status_colors = array(
                            'yes' => '#00a32a',
                            'no' => '#d63638'
                        );
                        $status_labels = array(
                            'yes' => 'Attending',
                            'no' => 'Not Attending'
                        );
                        $color = $status_colors[$response->attendance_status] ?? '#666';
                        $label = $status_labels[$response->attendance_status] ?? $response->attendance_status;
                        ?>
                        <span style="color: <?php echo $color; ?>; font-weight: bold;">
                            <?php echo esc_html($label); ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($response->dietary_requirements): ?>
                            <?php echo esc_html($response->dietary_requirements); ?>
                        <?php else: ?>
                            <em>None specified</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($response->additional_notes): ?>
                            <?php echo esc_html($response->additional_notes); ?>
                        <?php else: ?>
                            <em>No notes</em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo date('M j, Y g:i A', strtotime($response->responded_at)); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <!-- Summary Statistics -->
    <?php if (!empty($responses)): ?>
    <div style="margin-top: 30px;">
        <h2>Summary</h2>
        <?php
        $attending = array_filter($responses, function($r) { return $r->attendance_status === 'yes'; });
        $not_attending = array_filter($responses, function($r) { return $r->attendance_status === 'no'; });
        ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
            <div class="card" style="text-align: center; padding: 15px;">
                <h3 style="margin: 0; color: #00a32a;"><?php echo count($attending); ?></h3>
                <p style="margin: 5px 0 0 0;">Attending</p>
            </div>
            <div class="card" style="text-align: center; padding: 15px;">
                <h3 style="margin: 0; color: #d63638;"><?php echo count($not_attending); ?></h3>
                <p style="margin: 5px 0 0 0;">Not Attending</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function exportResponses() {
    // Create CSV export functionality
    var form = document.createElement('form');
    form.method = 'post';
    form.action = '<?php echo admin_url('admin-ajax.php'); ?>';
    
    var actionInput = document.createElement('input');
    actionInput.type = 'hidden';
    actionInput.name = 'action';
    actionInput.value = 'export_wedding_responses';
    form.appendChild(actionInput);
    
    var familyFilter = document.querySelector('select[name="family_filter"]');
    if (familyFilter && familyFilter.value) {
        var familyInput = document.createElement('input');
        familyInput.type = 'hidden';
        familyInput.name = 'family_filter';
        familyInput.value = familyFilter.value;
        form.appendChild(familyInput);
    }
    
    var eventFilter = document.querySelector('select[name="event_filter"]');
    if (eventFilter && eventFilter.value) {
        var eventInput = document.createElement('input');
        eventInput.type = 'hidden';
        eventInput.name = 'event_filter';
        eventInput.value = eventFilter.value;
        form.appendChild(eventInput);
    }
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
