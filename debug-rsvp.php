<?php
// Simple debug script to check RSVP data
require_once 'wp-config.php';

global $wpdb;

echo "<h1>RSVP Debug Information</h1>";

$table_rsvp = $wpdb->prefix . 'wedding_rsvp_submissions';
$table_events = $wpdb->prefix . 'wedding_events';
$table_families = $wpdb->prefix . 'wedding_families';
$table_guests = $wpdb->prefix . 'wedding_guests';

echo "<h2>Table Names</h2>";
echo "RSVP Submissions: $table_rsvp<br>";
echo "Events: $table_events<br>";
echo "Families: $table_families<br>";
echo "Guests: $table_guests<br>";

echo "<h2>RSVP Submissions Table</h2>";
$submissions = $wpdb->get_results("SELECT * FROM $table_rsvp");
echo "Total records: " . count($submissions) . "<br><br>";

foreach($submissions as $sub) {
    echo "ID: $sub->id<br>";
    echo "Family ID: $sub->family_id<br>";
    echo "Guest ID: $sub->guest_id<br>";
    echo "Attendance: $sub->attendance_status<br>";
    echo "Selected Events: " . htmlspecialchars($sub->selected_events) . "<br>";
    echo "Attending Members: " . htmlspecialchars($sub->attending_members) . "<br>";
    echo "Submitted: $sub->submitted_at<br>";
    echo "<hr>";
}

echo "<h2>Events Table</h2>";
$events = $wpdb->get_results("SELECT * FROM $table_events WHERE is_active = 1");
foreach($events as $event) {
    echo "Event: $event->event_name (type: $event->event_type)<br>";
}

echo "<h2>Families Table</h2>";
$families = $wpdb->get_results("SELECT * FROM $table_families WHERE is_active = 1");
foreach($families as $family) {
    echo "Family ID: $family->id, Name: $family->family_name (code: $family->family_code)<br>";
}

echo "<h2>All Families (including inactive)</h2>";
$all_families = $wpdb->get_results("SELECT * FROM $table_families");
foreach($all_families as $family) {
    echo "Family ID: $family->id, Name: $family->family_name (code: $family->family_code), Active: $family->is_active<br>";
}

echo "<h2>Test Join Query</h2>";
$join_test = $wpdb->get_results("
    SELECT r.id, r.family_id, r.guest_id, r.attendance_status, r.selected_events,
           g.id as guest_table_id, g.family_id as guest_family_id,
           f.id as family_table_id, f.family_name, f.is_active
    FROM $table_rsvp r 
    LEFT JOIN $table_guests g ON r.guest_id = g.id 
    LEFT JOIN $table_families f ON g.family_id = f.id
");

foreach($join_test as $row) {
    echo "RSVP ID: $row->id, Family ID: $row->family_id, Guest ID: $row->guest_id<br>";
    echo "Guest Table ID: $row->guest_table_id, Guest Family ID: $row->guest_family_id<br>";
    echo "Family Table ID: $row->family_table_id, Family Name: $row->family_name, Active: $row->is_active<br>";
    echo "Attendance: $row->attendance_status, Events: $row->selected_events<br>";
    echo "<hr>";
}
?>