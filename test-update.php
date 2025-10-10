<?php
// Test script to verify RSVP updates work correctly
require_once 'wp-config.php';

global $wpdb;

$table_rsvp = $wpdb->prefix . 'wedding_rsvp_submissions';

echo "<h1>RSVP Update Test</h1>";

// Show current state
echo "<h2>Current RSVP Records</h2>";
$current = $wpdb->get_results("SELECT id, family_id, attendance_status, selected_events FROM $table_rsvp ORDER BY id");
foreach($current as $record) {
    echo "ID: $record->id, Family: $record->family_id, Attendance: $record->attendance_status, Events: $record->selected_events<br>";
}

echo "<h2>Testing Update Mechanism</h2>";

// Test: Update family 1's response to attending with events
$test_result = $wpdb->replace(
    $table_rsvp,
    array(
        'family_id' => 1,
        'guest_id' => 1,
        'primary_guest_name' => 'Dennis Wijaya',
        'family_code' => 'dennis-wijaya',
        'attendance_status' => 'yes',
        'selected_events' => '["church","reception"]',
        'attending_members' => '["Dennis Wijaya"]',
        'wishes' => 'Test update - attending both events'
    ),
    array('%d', '%d', '%s', '%s', '%s', '%s', '%s', '%s')
);

if ($test_result) {
    echo "<span style='color: green;'>✅ Update successful!</span><br>";
} else {
    echo "<span style='color: red;'>❌ Update failed: " . $wpdb->last_error . "</span><br>";
}

// Show updated state
echo "<h2>After Update</h2>";
$updated = $wpdb->get_results("SELECT id, family_id, attendance_status, selected_events, attending_members FROM $table_rsvp ORDER BY id");
foreach($updated as $record) {
    echo "ID: $record->id, Family: $record->family_id, Attendance: $record->attendance_status, Events: $record->selected_events, Members: $record->attending_members<br>";
}

echo "<h2>Dashboard Event Count Test</h2>";
// Simulate the dashboard counting logic
$events = array(
    (object)array('event_type' => 'church', 'event_name' => 'Holy Matrimony'),
    (object)array('event_type' => 'reception', 'event_name' => 'Wedding Reception'),
    (object)array('event_type' => 'teapai', 'event_name' => 'Tea Ceremony')
);

foreach ($events as $event) {
    $all_submissions = $wpdb->get_results("SELECT selected_events, attendance_status FROM $table_rsvp");
    
    $attending = 0;
    $not_attending = 0;
    
    foreach ($all_submissions as $submission) {
        $selected_events = json_decode($submission->selected_events, true);
        
        if (is_array($selected_events) && in_array($event->event_type, $selected_events)) {
            if ($submission->attendance_status === 'yes') {
                $attending++;
            } else {
                $not_attending++;
            }
        }
    }
    
    echo "$event->event_name: $attending attending, $not_attending not attending<br>";
}

?>