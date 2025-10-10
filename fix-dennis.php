<?php
// Update Dennis back to not attending
require_once 'wp-config.php';

global $wpdb;

$table_rsvp = $wpdb->prefix . 'wedding_rsvp_submissions';

echo "<h1>Fix Dennis's Attendance Status</h1>";

// Show current state
echo "<h2>Current Status</h2>";
$current = $wpdb->get_results("SELECT * FROM $table_rsvp WHERE family_id = 1");
foreach($current as $record) {
    echo "ID: $record->id, Attendance: $record->attendance_status, Events: $record->selected_events<br>";
}

// Update to not attending
$result = $wpdb->update(
    $table_rsvp,
    array(
        'attendance_status' => 'no',
        'selected_events' => '[]',
        'attending_members' => '[]'
    ),
    array('family_id' => 1),
    array('%s', '%s', '%s'),
    array('%d')
);

if ($result !== false) {
    echo "<br><span style='color: green;'>✅ Updated Dennis to not attending</span><br>";
} else {
    echo "<br><span style='color: red;'>❌ Update failed: " . $wpdb->last_error . "</span><br>";
}

// Show updated state
echo "<h2>After Update</h2>";
$updated = $wpdb->get_results("SELECT * FROM $table_rsvp WHERE family_id = 1");
foreach($updated as $record) {
    echo "ID: $record->id, Attendance: $record->attendance_status, Events: $record->selected_events<br>";
}

?>