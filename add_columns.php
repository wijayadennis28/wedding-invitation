<?php
require_once 'wp-config.php';
require_once 'wp-includes/wp-db.php';
$wpdb = new wpdb(DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);

echo "Adding family member columns to wp_wedding_guests table...\n";

// Add columns for additional family members and invitation details
$alterations = [
    'ADD COLUMN guest_2 varchar(100) NULL AFTER phone_number',
    'ADD COLUMN guest_3 varchar(100) NULL AFTER guest_2',
    'ADD COLUMN guest_4 varchar(100) NULL AFTER guest_3',
    'ADD COLUMN guest_5 varchar(100) NULL AFTER guest_4',
    'ADD COLUMN guest_6 varchar(100) NULL AFTER guest_5',
    'ADD COLUMN pax_num int(11) NULL AFTER guest_6',
    'ADD COLUMN invitation_type enum("Printed","Digital") NULL AFTER pax_num',
    'ADD COLUMN church_inv enum("yes","no") DEFAULT "no" AFTER invitation_type',
    'ADD COLUMN church_souvenir enum("yes","no") DEFAULT "no" AFTER church_inv',
    'ADD COLUMN teapai_inv enum("yes","no") DEFAULT "no" AFTER church_souvenir',
    'ADD COLUMN teapai_souvenir enum("yes","no") DEFAULT "no" AFTER teapai_inv',
    'ADD COLUMN reception_inv enum("yes","no") DEFAULT "no" AFTER teapai_souvenir',
    'ADD COLUMN reception_souvenir enum("yes","no") DEFAULT "no" AFTER reception_inv'
];

foreach ($alterations as $alter) {
    $sql = "ALTER TABLE wp_wedding_guests $alter";
    $result = $wpdb->query($sql);
    
    if ($result === false) {
        echo "Error: " . $wpdb->last_error . "\n";
        echo "SQL: $sql\n";
    } else {
        echo "✓ Added: $alter\n";
    }
}

echo "\nTable structure updated successfully!\n";

// Show the new table structure
echo "\nNew table structure:\n";
$columns = $wpdb->get_results("DESCRIBE wp_wedding_guests");
foreach ($columns as $column) {
    echo $column->Field . " - " . $column->Type . "\n";
}
?>
