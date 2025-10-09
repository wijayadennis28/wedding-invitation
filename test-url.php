<?php
// Test URL handling for wedding invitation
define('WP_USE_THEMES', false);
require('./wp-load.php');

// Simulate the URL: http://localhost/wedding-invitation/?familly_code=dennis-wijaya/
$_GET['familly_code'] = 'dennis-wijaya/';

// Get the wedding RSVP instance
$wedding_rsvp = WeddingRSVP::get_instance();

echo "Testing URL: ?familly_code=dennis-wijaya/\n";
echo "Before processing:\n";
echo "Global wedding_family_data: " . (isset($GLOBALS['wedding_family_data']) ? 'SET' : 'NOT SET') . "\n";

// Call the URL handler
$wedding_rsvp->handle_wedding_urls();

echo "\nAfter processing:\n";
echo "Global wedding_family_data: " . (isset($GLOBALS['wedding_family_data']) ? 'SET' : 'NOT SET') . "\n";

if (isset($GLOBALS['wedding_family_data'])) {
    echo "Guest name: " . $GLOBALS['wedding_family_data']->primary_guest_name . "\n";
    echo "Family members: " . print_r($GLOBALS['wedding_family_data']->family_members, true) . "\n";
}

// Check if we can find the guest directly
global $wpdb;
$guest = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}wedding_guests WHERE primary_guest_name = 'Dennis Wijaya'");

if ($guest) {
    echo "\nDirect database query found guest:\n";
    echo "ID: " . $guest->id . "\n";
    echo "Name: " . $guest->primary_guest_name . "\n";
    echo "Phone: " . $guest->phone_number . "\n";
    echo "Family members: " . $guest->family_members . "\n";
} else {
    echo "\nNo guest found with name 'Dennis Wijaya' in database\n";
    
    // List all guests to see what names are available
    $all_guests = $wpdb->get_results("SELECT id, primary_guest_name FROM {$wpdb->prefix}wedding_guests LIMIT 10");
    echo "\nFirst 10 guests in database:\n";
    foreach ($all_guests as $g) {
        echo "- " . $g->primary_guest_name . "\n";
    }
}
?>