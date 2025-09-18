<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

// Pagination settings
$records_per_page = isset($_GET['per_page']) ? max(10, min(100, intval($_GET['per_page']))) : 20;
$current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$offset = ($current_page - 1) * $records_per_page;

// Handle form submissions
if (isset($_POST['action'])) {
    if ($_POST['action'] === 'add_family') {
        // Collect family member names from the dynamic fields
        $family_member_names = [];
        if (isset($_POST['guest_names']) && is_array($_POST['guest_names'])) {
            foreach ($_POST['guest_names'] as $name) {
                if (!empty(trim($name))) {
                    $family_member_names[] = sanitize_text_field(trim($name));
                }
            }
        }
        
        $guest_data = [
            'primary_guest_name' => sanitize_text_field($_POST['primary_guest_name']),
            'phone_number' => sanitize_text_field($_POST['phone_number']),
            'pax_num' => intval($_POST['pax_num']),
            'invitation_type' => sanitize_text_field($_POST['invitation_type']),
            'family_members' => json_encode($family_member_names)
        ];
        
        $result = $wpdb->insert('wp_wedding_guests', $guest_data);
        $guest_id = $wpdb->insert_id;
        
        if ($guest_id) {
            // Get event IDs
            $events = $wpdb->get_results("SELECT id, event_type FROM wp_wedding_events ORDER BY id");
            
            // Create invitations for each event
            foreach ($events as $event) {
                $is_invited = isset($_POST["{$event->event_type}_invited"]) ? 'yes' : 'no';
                $gets_souvenir = isset($_POST["{$event->event_type}_souvenir"]) ? 'yes' : 'no';
                
                $wpdb->insert('wp_wedding_guest_invitations', [
                    'guest_id' => $guest_id,
                    'event_id' => $event->id,
                    'invitation_status' => $is_invited,
                    'souvenir_status' => $gets_souvenir
                ]);
            }
            
            echo '<div class="notice notice-success"><p>Family added successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Error adding family.</p></div>';
        }
    }
    
    // Handle edit family
    if ($_POST['action'] === 'edit_family') {
        $family_id = intval($_POST['family_id']);
        
        // Collect family member names
        $family_member_names = [];
        if (isset($_POST['guest_names']) && is_array($_POST['guest_names'])) {
            foreach ($_POST['guest_names'] as $name) {
                if (!empty(trim($name))) {
                    $family_member_names[] = sanitize_text_field(trim($name));
                }
            }
        }
        
        // Update guest data
        $guest_data = [
            'primary_guest_name' => sanitize_text_field($_POST['primary_guest_name']),
            'phone_number' => sanitize_text_field($_POST['phone_number']),
            'pax_num' => intval($_POST['pax_num']),
            'invitation_type' => sanitize_text_field($_POST['invitation_type']),
            'family_members' => json_encode($family_member_names)
        ];
        
        $result = $wpdb->update('wp_wedding_guests', $guest_data, ['id' => $family_id]);
        
        if ($result !== false) {
            // Get event IDs
            $events = $wpdb->get_results("SELECT id, event_type FROM wp_wedding_events ORDER BY id");
            
            // Update invitations for each event
            foreach ($events as $event) {
                $is_invited = isset($_POST["{$event->event_type}_invited"]) ? 'yes' : 'no';
                $gets_souvenir = isset($_POST["{$event->event_type}_souvenir"]) ? 'yes' : 'no';
                
                // Check if invitation exists
                $existing = $wpdb->get_var($wpdb->prepare("
                    SELECT id FROM wp_wedding_guest_invitations 
                    WHERE guest_id = %d AND event_id = %d
                ", $family_id, $event->id));
                
                if ($existing) {
                    $wpdb->update('wp_wedding_guest_invitations', [
                        'invitation_status' => $is_invited,
                        'souvenir_status' => $gets_souvenir
                    ], [
                        'guest_id' => $family_id,
                        'event_id' => $event->id
                    ]);
                } else {
                    $wpdb->insert('wp_wedding_guest_invitations', [
                        'guest_id' => $family_id,
                        'event_id' => $event->id,
                        'invitation_status' => $is_invited,
                        'souvenir_status' => $gets_souvenir
                    ]);
                }
            }
            
            echo '<div class="notice notice-success"><p>Family updated successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Error updating family.</p></div>';
        }
    }
    
    // Handle delete family
    if ($_POST['action'] === 'delete_family') {
        $family_id = intval($_POST['family_id']);
        
        // Delete invitations first (foreign key constraint)
        $wpdb->delete('wp_wedding_guest_invitations', ['guest_id' => $family_id]);
        
        // Delete the family
        $result = $wpdb->delete('wp_wedding_guests', ['id' => $family_id]);
        
        if ($result) {
            echo '<div class="notice notice-success"><p>Family deleted successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Error deleting family.</p></div>';
        }
    }
}

// Get events for the form
$events = $wpdb->get_results("SELECT * FROM wp_wedding_events ORDER BY id");

// Calculate pagination
$total_records = $wpdb->get_var("SELECT COUNT(*) FROM wp_wedding_guests");
$total_pages = ceil($total_records / $records_per_page);

// Get families for display with pagination
$families = $wpdb->get_results($wpdb->prepare("
    SELECT * FROM wp_wedding_guests 
    ORDER BY id ASC
    LIMIT %d OFFSET %d
", $records_per_page, $offset));

// Calculate stats
$total_families = $wpdb->get_var("SELECT COUNT(*) FROM wp_wedding_guests");
$total_guests = $wpdb->get_var("SELECT SUM(pax_num) FROM wp_wedding_guests");

$church_invited = $wpdb->get_var("
    SELECT COUNT(DISTINCT gi.guest_id) 
    FROM wp_wedding_guest_invitations gi
    JOIN wp_wedding_events e ON gi.event_id = e.id
    WHERE e.event_type = 'church' AND gi.invitation_status = 'yes'
");

$teapai_invited = $wpdb->get_var("
    SELECT COUNT(DISTINCT gi.guest_id) 
    FROM wp_wedding_guest_invitations gi
    JOIN wp_wedding_events e ON gi.event_id = e.id
    WHERE e.event_type = 'teapai' AND gi.invitation_status = 'yes'
");

$reception_invited = $wpdb->get_var("
    SELECT COUNT(DISTINCT gi.guest_id) 
    FROM wp_wedding_guest_invitations gi
    JOIN wp_wedding_events e ON gi.event_id = e.id
    WHERE e.event_type = 'reception' AND gi.invitation_status = 'yes'
");
?>

<div class="wrap">
    <h1 class="wp-heading-inline">Wedding Guest Management</h1>
    <a href="#" class="page-title-action" onclick="openModal()">Add New Family</a>
    <hr class="wp-header-end">

    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .stat-card {
            background: white;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 1px 1px rgba(0,0,0,.04);
        }
        
        .stat-number {
            font-size: 2em;
            font-weight: bold;
            color: #2271b1;
            display: block;
        }
        
        .stat-label {
            color: #646970;
            font-size: 0.9em;
            margin-top: 5px;
        }
        
        .family-table {
            background: white;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
            margin: 20px 0;
        }
        
        .family-table table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .family-table th,
        .family-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #c3c4c7;
        }
        
        .family-table th {
            background: #f6f7f7;
            font-weight: 600;
        }
        
        .family-table tr:hover {
            background: #f6f7f7;
        }
        
        .action-btn {
            padding: 6px 12px;
            margin: 0 2px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
        }
        
        .edit-btn { background: #ffc107; color: #212529; }
        .delete-btn { background: #dc3545; color: white; }
        .edit-btn:hover { background: #e0a800; }
        .delete-btn:hover { background: #c82333; }
        
        .event-status {
            font-size: 12px;
            line-height: 1.4;
        }
        
        .status-yes { color: #00a32a; }
        .status-no { color: #dba617; }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 100000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 2% auto;
            padding: 0;
            border: 1px solid #888;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
        
        .modal-header {
            background: #2271b1;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-header h2 {
            margin: 0;
            color: white;
        }
        
        .close {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            line-height: 1;
        }
        
        .close:hover {
            opacity: 0.7;
        }
        
        .modal-body {
            padding: 30px;
        }
        
        .modal-footer {
            padding: 20px 30px;
            border-top: 1px solid #ddd;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f9f9f9;
        }
        
        .form-section h3 {
            margin-top: 0;
            color: #2271b1;
            border-bottom: 2px solid #2271b1;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #23282d;
        }
        
        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .guest-field {
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }
        
        .guest-input-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .guest-input-group input {
            flex: 1;
        }
        
        .remove-guest {
            padding: 8px 12px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
        }
        
        .add-guest {
            padding: 10px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        
        .event-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .event-item {
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }
        
        .event-item h4 {
            margin-top: 0;
            color: #2271b1;
        }
        
        .checkbox-group {
            margin-bottom: 10px;
        }
        
        .checkbox-group label {
            display: flex;
            align-items: center;
            font-weight: normal;
            margin-bottom: 0;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
            margin-right: 8px;
        }
        
        .submit-btn {
            background: #2271b1;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .cancel-btn {
            background: #6c757d;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .pagination-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            padding: 15px;
            background: white;
            border: 1px solid #c3c4c7;
            border-radius: 4px;
        }
        
        .pagination-info {
            font-size: 14px;
            color: #646970;
        }
        
        .pagination-links {
            display: flex;
            gap: 5px;
        }
        
        .pagination-links a,
        .pagination-links span {
            padding: 8px 12px;
            border: 1px solid #c3c4c7;
            border-radius: 3px;
            text-decoration: none;
            color: #2271b1;
        }
        
        .pagination-links .current {
            background: #2271b1;
            color: white;
        }
        
        .per-page-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .per-page-selector select {
            padding: 4px 8px;
            border: 1px solid #c3c4c7;
            border-radius: 3px;
        }
    </style>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-number"><?php echo number_format($total_families); ?></span>
            <div class="stat-label">Total Families</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo number_format($total_guests); ?></span>
            <div class="stat-label">Total Guests</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo number_format($church_invited); ?></span>
            <div class="stat-label">Church Invitations</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo number_format($teapai_invited); ?></span>
            <div class="stat-label">Teapai Invitations</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo number_format($reception_invited); ?></span>
            <div class="stat-label">Reception Invitations</div>
        </div>
    </div>
            echo '<div class="notice notice-success"><p>Family added successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Error adding family: ' . $wpdb->last_error . '</p></div>';
        }
    }
    
    if ($_POST['action'] === 'add_guest') {
        $family_id = intval($_POST['family_id']);
        $guest_name = sanitize_text_field($_POST['guest_name']);
        $phone_number = sanitize_text_field($_POST['phone_number']);
        $is_primary = isset($_POST['is_primary_contact']) ? 1 : 0;
        
        $table_guests = $wpdb->prefix . 'wedding_guests';
        
        // Get next guest order for this family
        $guest_order = $wpdb->get_var($wpdb->prepare(
            "SELECT COALESCE(MAX(guest_order), 0) + 1 FROM $table_guests WHERE family_id = %d",
            $family_id
        ));
        
        $result = $wpdb->insert(
            $table_guests,
            array(
                'family_id' => $family_id,
                'guest_name' => $guest_name,
                'phone_number' => $phone_number,
                'is_primary_contact' => $is_primary,
                'guest_order' => $guest_order
            )
        );
        
        if ($result) {
            echo '<div class="notice notice-success"><p>Guest added successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>Error adding guest: ' . $wpdb->last_error . '</p></div>';
        }
    }
}

// Get all families
$table_families = $wpdb->prefix . 'wedding_families';
$table_guests = $wpdb->prefix . 'wedding_guests';

$families = $wpdb->get_results("
    SELECT f.*, COUNT(g.id) as guest_count 
    FROM $table_families f 
    LEFT JOIN $table_guests g ON f.id = g.family_id 
    WHERE f.is_active = 1
    GROUP BY f.id 
    ORDER BY f.created_at DESC
");
?>

<div class="wrap">
    <h1>Manage Wedding Families & Guests</h1>
    
    <!-- Add New Family Form -->
    <div class="card" style="max-width: 600px; margin-bottom: 30px;">
        <h2>Add New Family</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="add_family">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="family_code">Family Code (URL)</label></th>
                    <td>
                        <input type="text" id="family_code" name="family_code" class="regular-text" required>
                        <p class="description">This will be used in the URL: yoursite.com/<strong>family-code</strong></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="family_name">Family Name</label></th>
                    <td><input type="text" id="family_name" name="family_name" class="regular-text" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="max_guests">Max Guests</label></th>
                    <td><input type="number" id="max_guests" name="max_guests" min="1" max="20" value="1" required></td>
                </tr>
                <tr>
                    <th scope="row"><label for="phone_primary">Primary Phone</label></th>
                    <td><input type="text" id="phone_primary" name="phone_primary" class="regular-text"></td>
                </tr>
            </table>
            <?php submit_button('Add Family'); ?>
        </form>
    </div>
    
    <!-- Families List -->
    <h2>Existing Families</h2>
    <div class="tablenav top">
        <div class="alignleft actions">
            <p>Total Families: <strong><?php echo count($families); ?></strong></p>
        </div>
    </div>
    
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col">Family Name</th>
                <th scope="col">Family Code</th>
                <th scope="col">Guests</th>
                <th scope="col">Max Guests</th>
                <th scope="col">Primary Phone</th>
                <th scope="col">Wedding URL</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($families as $family): ?>
            <tr>
                <td><strong><?php echo esc_html($family->family_name); ?></strong></td>
                <td><code><?php echo esc_html($family->family_code); ?></code></td>
                <td><?php echo $family->guest_count; ?></td>
                <td><?php echo $family->max_guests; ?></td>
                <td><?php echo esc_html($family->phone_primary); ?></td>
                <td>
                    <a href="<?php echo home_url('/' . $family->family_code); ?>" target="_blank">
                        <?php echo home_url('/' . $family->family_code); ?>
                    </a>
                </td>
                <td>
                    <button type="button" class="button" onclick="toggleGuestForm(<?php echo $family->id; ?>)">
                        Add Guest
                    </button>
                    <button type="button" class="button" onclick="viewGuests(<?php echo $family->id; ?>)">
                        View Guests
                    </button>
                </td>
            </tr>
            
            <!-- Add Guest Form (Hidden by default) -->
            <tr id="guest-form-<?php echo $family->id; ?>" style="display: none;">
                <td colspan="7">
                    <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #007cba;">
                        <h4>Add Guest to <?php echo esc_html($family->family_name); ?></h4>
                        <form method="post" action="" style="display: inline-block; width: 100%;">
                            <input type="hidden" name="action" value="add_guest">
                            <input type="hidden" name="family_id" value="<?php echo $family->id; ?>">
                            <table class="form-table">
                                <tr>
                                    <td style="width: 200px;"><label>Guest Name:</label></td>
                                    <td><input type="text" name="guest_name" required style="width: 250px;"></td>
                                    <td style="width: 150px;"><label>Phone:</label></td>
                                    <td><input type="text" name="phone_number" style="width: 200px;"></td>
                                    <td>
                                        <label>
                                            <input type="checkbox" name="is_primary_contact">
                                            Primary Contact
                                        </label>
                                    </td>
                                    <td>
                                        <input type="submit" class="button button-primary" value="Add Guest">
                                        <button type="button" class="button" onclick="toggleGuestForm(<?php echo $family->id; ?>)">Cancel</button>
                                    </td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </td>
            </tr>
            
            <!-- Guest List (Hidden by default) -->
            <tr id="guest-list-<?php echo $family->id; ?>" style="display: none;">
                <td colspan="7">
                    <div style="background: #f0f8ff; padding: 15px; border-left: 4px solid #00a32a;">
                        <h4>Guests for <?php echo esc_html($family->family_name); ?></h4>
                        <?php 
                        $guests = $wpdb->get_results($wpdb->prepare(
                            "SELECT * FROM $table_guests WHERE family_id = %d ORDER BY guest_order",
                            $family->id
                        ));
                        
                        if ($guests): ?>
                            <ul style="margin: 10px 0;">
                                <?php foreach ($guests as $guest): ?>
                                <li style="margin: 5px 0;">
                                    <strong><?php echo esc_html($guest->guest_name); ?></strong>
                                    <?php if ($guest->phone_number): ?>
                                        - <?php echo esc_html($guest->phone_number); ?>
                                    <?php endif; ?>
                                    <?php if ($guest->is_primary_contact): ?>
                                        <span style="color: #007cba; font-weight: bold;"> (Primary Contact)</span>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p><em>No guests added yet.</em></p>
                        <?php endif; ?>
                        <button type="button" class="button" onclick="viewGuests(<?php echo $family->id; ?>)">Hide</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
function toggleGuestForm(familyId) {
    var form = document.getElementById('guest-form-' + familyId);
    if (form.style.display === 'none') {
        form.style.display = 'table-row';
    } else {
        form.style.display = 'none';
    }
}

function viewGuests(familyId) {
    var list = document.getElementById('guest-list-' + familyId);
    if (list.style.display === 'none') {
        list.style.display = 'table-row';
    } else {
        list.style.display = 'none';
    }
}
</script>
