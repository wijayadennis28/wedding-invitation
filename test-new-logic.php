<?php
// Test the new dashboard logic
require_once 'wp-config.php';

global $wpdb;

$table_rsvp = $wpdb->prefix . 'wedding_rsvp_submissions';
$table_events = $wpdb->prefix . 'wedding_events';

echo "<h1>Test New Dashboard Logic</h1>";

// Show current RSVP data
echo "<h2>Current RSVP Data</h2>";
$submissions = $wpdb->get_results("SELECT * FROM $table_rsvp");
foreach($submissions as $sub) {
    echo "ID: $sub->id, Family: $sub->family_id, Attendance: $sub->attendance_status, Events: $sub->selected_events<br>";
}

// Test the new counting logic
echo "<h2>New Event Counting Logic</h2>";
$events = $wpdb->get_results("SELECT * FROM $table_events WHERE is_active = 1 ORDER BY event_date, event_time");

foreach ($events as $event) {
    echo "<h3>Processing: $event->event_name (type: $event->event_type)</h3>";
    
    $all_submissions = $wpdb->get_results("SELECT selected_events, attendance_status FROM $table_rsvp");
    
    $attending = 0;
    $not_attending = 0;
    
    foreach ($all_submissions as $submission) {
        echo "Processing submission: attendance=$submission->attendance_status, events=$submission->selected_events<br>";
        
        if ($submission->attendance_status === 'no') {
            // If not attending overall, count as "not attending" for ALL events
            $not_attending++;
            echo "&nbsp;&nbsp;→ Not attending overall, counted as 'not attending' for $event->event_name<br>";
        } else if ($submission->attendance_status === 'yes') {
            // If attending overall, check which specific events they selected
            $selected_events = json_decode($submission->selected_events, true);
            
            if (is_array($selected_events) && in_array($event->event_type, $selected_events)) {
                // They're attending AND this event is selected
                $attending++;
                echo "&nbsp;&nbsp;→ Attending and selected this event, counted as 'attending' for $event->event_name<br>";
            } else {
                echo "&nbsp;&nbsp;→ Attending but didn't select this event, not counted<br>";
            }
        }
    }
    
    $total_responses = $attending + $not_attending;
    echo "<strong>Result: $attending attending, $not_attending not attending, $total_responses total</strong><br><br>";
}

?>