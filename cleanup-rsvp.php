<?php
// Cleanup script for duplicate RSVP records
require_once 'wp-config.php';

global $wpdb;

$table_rsvp = $wpdb->prefix . 'wedding_rsvp_submissions';
$table_families = $wpdb->prefix . 'wedding_families';

echo "<h1>RSVP Cleanup Script</h1>";

// Show current state
echo "<h2>Current RSVP Records</h2>";
$current_records = $wpdb->get_results("SELECT * FROM $table_rsvp ORDER BY id");
foreach($current_records as $record) {
    echo "ID: $record->id, Family ID: $record->family_id, Guest ID: $record->guest_id, Attendance: $record->attendance_status, Events: $record->selected_events<br>";
}

// Check which families actually exist
echo "<h2>Valid Families</h2>";
$valid_families = $wpdb->get_results("SELECT id, family_name, family_code FROM $table_families WHERE is_active = 1");
foreach($valid_families as $family) {
    echo "Family ID: $family->id, Name: $family->family_name, Code: $family->family_code<br>";
}

// Find orphaned records (records with family_id that doesn't exist)
echo "<h2>Orphaned RSVP Records</h2>";
$orphaned = $wpdb->get_results("
    SELECT r.* 
    FROM $table_rsvp r 
    LEFT JOIN $table_families f ON r.family_id = f.id 
    WHERE f.id IS NULL OR f.is_active = 0
");

if ($orphaned) {
    echo "Found " . count($orphaned) . " orphaned records:<br>";
    foreach($orphaned as $record) {
        echo "ID: $record->id, Family ID: $record->family_id (INVALID), Guest ID: $record->guest_id, Attendance: $record->attendance_status<br>";
    }
    
    // Show cleanup option
    echo "<br><strong>Cleanup Actions Available:</strong><br>";
    echo "1. Delete orphaned records<br>";
    echo "2. Fix family_id in orphaned records<br>";
} else {
    echo "No orphaned records found.<br>";
}

// Show duplicate families
echo "<h2>Duplicate RSVP Records for Same Family</h2>";
$duplicates = $wpdb->get_results("
    SELECT family_id, COUNT(*) as count 
    FROM $table_rsvp 
    GROUP BY family_id 
    HAVING COUNT(*) > 1
");

if ($duplicates) {
    foreach($duplicates as $dup) {
        echo "Family ID $dup->family_id has $dup->count records<br>";
        $records = $wpdb->get_results("SELECT * FROM $table_rsvp WHERE family_id = $dup->family_id ORDER BY id");
        foreach($records as $record) {
            echo "&nbsp;&nbsp;- ID: $record->id, Attendance: $record->attendance_status, Events: $record->selected_events, Submitted: $record->submitted_at<br>";
        }
    }
}

// Provide cleanup suggestions
echo "<h2>Suggested Actions</h2>";
echo "1. Delete record ID 24 (orphaned record with family_id = 3)<br>";
echo "2. Keep record ID 25 (valid record with family_id = 1)<br>";
echo "<br>";
echo "To execute cleanup, uncomment the following lines in this script:<br>";
echo "<pre>";
echo "// \$wpdb->delete(\$table_rsvp, array('id' => 24));";
echo "</pre>";

// Uncomment this line to actually delete the orphaned record:
$wpdb->delete($table_rsvp, array('id' => 24));
echo "<br><strong style='color: green;'>Deleted orphaned record ID 24.</strong>";

// Show final state
echo "<h2>Final RSVP Records After Cleanup</h2>";
$final_records = $wpdb->get_results("SELECT * FROM $table_rsvp ORDER BY id");
foreach($final_records as $record) {
    echo "ID: $record->id, Family ID: $record->family_id, Guest ID: $record->guest_id, Attendance: $record->attendance_status, Events: $record->selected_events<br>";
}

?>