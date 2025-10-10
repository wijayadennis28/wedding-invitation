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
        // Collect family member data from the dynamic fields
        $family_member_data = [];
        if (isset($_POST['guest_names']) && is_array($_POST['guest_names'])) {
            $guest_names = $_POST['guest_names'];
            
            for ($i = 0; $i < count($guest_names); $i++) {
                if (!empty(trim($guest_names[$i]))) {
                    $family_member_data[] = sanitize_text_field(trim($guest_names[$i]));
                }
            }
        }
        
        $guest_data = [
            'family_id' => 0, // Will be updated after insert
            'primary_guest_name' => sanitize_text_field($_POST['primary_guest_name']),
            'phone_number' => sanitize_text_field($_POST['phone_number']),
            'pax_num' => intval($_POST['pax_num']),
            'invitation_type' => sanitize_text_field($_POST['invitation_type']),
            'family_members' => json_encode($family_member_data)
        ];
        
        $result = $wpdb->insert('wp_wedding_guests', $guest_data);
        $guest_id = $wpdb->insert_id;
        
        // Debug output
        if ($wpdb->last_error) {
            echo '<div class="notice notice-error"><p>Database Error: ' . $wpdb->last_error . '</p></div>';
        }
        
        // Update family_id to be the same as guest_id for primary guests
        if ($guest_id) {
            $wpdb->update('wp_wedding_guests', ['family_id' => $guest_id], ['id' => $guest_id]);
            // Get event IDs
            $events = $wpdb->get_results("SELECT id, event_type FROM wp_wedding_events ORDER BY id");
            
            // Create invitations for each event
            foreach ($events as $event) {
                $is_invited = isset($_POST["{$event->event_type}_invited"]) ? 'yes' : 'no';
                $gets_souvenir = isset($_POST["{$event->event_type}_souvenir"]) ? 'yes' : 'no';
                
                $wpdb->insert('wp_wedding_guest_invitations', [
                    'guest_id' => $guest_id,
                    'event_id' => $event->id,
                    'is_invited' => $is_invited,
                    'gets_souvenir' => $gets_souvenir
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
        
        // Collect family member data
        $family_member_data = [];
        if (isset($_POST['guest_names']) && is_array($_POST['guest_names'])) {
            $guest_names = $_POST['guest_names'];
            
            for ($i = 0; $i < count($guest_names); $i++) {
                if (!empty(trim($guest_names[$i]))) {
                    $family_member_data[] = sanitize_text_field(trim($guest_names[$i]));
                }
            }
        }
        
                // Update guest data
        $guest_data = [
            'primary_guest_name' => sanitize_text_field($_POST['primary_guest_name']),
            'phone_number' => sanitize_text_field($_POST['phone_number']),
            'pax_num' => intval($_POST['pax_num']),
            'invitation_type' => sanitize_text_field($_POST['invitation_type']),
            'family_members' => json_encode($family_member_data)
        ];
        
        $result = $wpdb->update('wp_wedding_guests', $guest_data, ['id' => $family_id]);
        
        // Debug output
        if ($wpdb->last_error) {
            echo '<div class="notice notice-error"><p>Database Error: ' . $wpdb->last_error . '</p></div>';
        }
        
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
                        'is_invited' => $is_invited,
                        'gets_souvenir' => $gets_souvenir
                    ], [
                        'guest_id' => $family_id,
                        'event_id' => $event->id
                    ]);
                } else {
                    $wpdb->insert('wp_wedding_guest_invitations', [
                        'guest_id' => $family_id,
                        'event_id' => $event->id,
                        'is_invited' => $is_invited,
                        'gets_souvenir' => $gets_souvenir
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

// Since relationship_type column doesn't exist, set these to 0 or calculate differently
$family_count = 0;
$friend_count = $total_families;

$church_invited = $wpdb->get_var("
    SELECT COUNT(DISTINCT gi.guest_id) 
    FROM wp_wedding_guest_invitations gi
    JOIN wp_wedding_events e ON gi.event_id = e.id
    WHERE e.event_type = 'church' AND gi.is_invited = 'yes'
");

$teapai_invited = $wpdb->get_var("
    SELECT COUNT(DISTINCT gi.guest_id) 
    FROM wp_wedding_guest_invitations gi
    JOIN wp_wedding_events e ON gi.event_id = e.id
    WHERE e.event_type = 'teapai' AND gi.is_invited = 'yes'
");

$reception_invited = $wpdb->get_var("
    SELECT COUNT(DISTINCT gi.guest_id) 
    FROM wp_wedding_guest_invitations gi
    JOIN wp_wedding_events e ON gi.event_id = e.id
    WHERE e.event_type = 'reception' AND gi.is_invited = 'yes'
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
            <span class="stat-number"><?php echo number_format($family_count); ?></span>
            <div class="stat-label">Family Members</div>
        </div>
        <div class="stat-card">
            <span class="stat-number"><?php echo number_format($friend_count); ?></span>
            <div class="stat-label">Friends</div>
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

    <!-- Pagination Controls -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination-controls">
            <div class="pagination-info">
                Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $records_per_page, $total_records); ?> of <?php echo $total_records; ?> families
            </div>
            
            <div class="pagination-links">
                <?php if ($current_page > 1): ?>
                    <a href="<?php echo admin_url('admin.php?page=wedding-families&paged=' . ($current_page - 1) . '&per_page=' . $records_per_page); ?>">&laquo; Previous</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $current_page - 2); $i <= min($total_pages, $current_page + 2); $i++): ?>
                    <?php if ($i == $current_page): ?>
                        <span class="current"><?php echo $i; ?></span>
                    <?php else: ?>
                        <a href="<?php echo admin_url('admin.php?page=wedding-families&paged=' . $i . '&per_page=' . $records_per_page); ?>"><?php echo $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($current_page < $total_pages): ?>
                    <a href="<?php echo admin_url('admin.php?page=wedding-families&paged=' . ($current_page + 1) . '&per_page=' . $records_per_page); ?>">Next &raquo;</a>
                <?php endif; ?>
            </div>
            
            <div class="per-page-selector">
                <label>Show:</label>
                <select onchange="location.href='<?php echo admin_url('admin.php?page=wedding-families&paged=1&per_page='); ?>' + this.value">
                    <option value="10" <?php selected($records_per_page, 10); ?>>10</option>
                    <option value="20" <?php selected($records_per_page, 20); ?>>20</option>
                    <option value="50" <?php selected($records_per_page, 50); ?>>50</option>
                    <option value="100" <?php selected($records_per_page, 100); ?>>100</option>
                </select>
                <span>per page</span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Family Table -->
    <div class="family-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Primary Guest</th>
                    <th>Phone</th>
                    <th>Guests</th>
                    <th>Type</th>
                    <th>Church</th>
                    <th>Teapai</th>
                    <th>Reception</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($families as $family): 
                    // Get event invitations for this family
                    $family_invitations = $wpdb->get_results($wpdb->prepare("
                        SELECT e.event_type, gi.is_invited, gi.gets_souvenir 
                        FROM wp_wedding_guest_invitations gi
                        JOIN wp_wedding_events e ON gi.event_id = e.id
                        WHERE gi.guest_id = %d
                    ", $family->id));
                    
                    // Process invitations into organized array
                    $events_info = [];
                    foreach ($family_invitations as $invitation) {
                        $events_info[$invitation->event_type] = [
                            'invited' => $invitation->is_invited,
                            'souvenir' => $invitation->gets_souvenir
                        ];
                    }
                    
                    // Count family members
                    $family_members = is_array($family->family_members) ? $family->family_members : (is_string($family->family_members) ? json_decode($family->family_members, true) : []);
                    $member_count = 1; // Primary guest
                    if ($family_members) {
                        foreach ($family_members as $member) {
                            if (!empty($member)) $member_count++;
                        }
                    }
                    ?>
                    <tr data-family-id="<?php echo $family->id; ?>">
                        <td><?php echo $family->id; ?></td>
                        <td><strong><?php echo htmlspecialchars($family->primary_guest_name); ?></strong></td>
                        <td><?php echo htmlspecialchars($family->phone_number ?: '-'); ?></td>
                        <td><?php echo $member_count; ?> / <?php echo $family->pax_num; ?></td>
                        <td><span style="background: <?php echo $family->invitation_type == 'Printed' ? '#ffc107' : '#17a2b8'; ?>; color: white; padding: 2px 6px; border-radius: 3px; font-size: 11px;"><?php echo $family->invitation_type; ?></span></td>
                        <td class="event-status">
                            <?php if (isset($events_info['church'])): ?>
                                <span class="<?php echo $events_info['church']['invited'] == 'yes' ? 'status-yes' : 'status-no'; ?>">
                                    <?php echo $events_info['church']['invited'] == 'yes' ? '✓' : '✗'; ?> Inv
                                </span><br>
                                <span class="<?php echo $events_info['church']['souvenir'] == 'yes' ? 'status-yes' : 'status-no'; ?>">
                                    <?php echo $events_info['church']['souvenir'] == 'yes' ? '📦' : '○'; ?> Souvenir
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="event-status">
                            <?php if (isset($events_info['teapai'])): ?>
                                <span class="<?php echo $events_info['teapai']['invited'] == 'yes' ? 'status-yes' : 'status-no'; ?>">
                                    <?php echo $events_info['teapai']['invited'] == 'yes' ? '✓' : '✗'; ?> Inv
                                </span><br>
                                <span class="<?php echo $events_info['teapai']['souvenir'] == 'yes' ? 'status-yes' : 'status-no'; ?>">
                                    <?php echo $events_info['teapai']['souvenir'] == 'yes' ? '📦' : '○'; ?> Souvenir
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="event-status">
                            <?php if (isset($events_info['reception'])): ?>
                                <span class="<?php echo $events_info['reception']['invited'] == 'yes' ? 'status-yes' : 'status-no'; ?>">
                                    <?php echo $events_info['reception']['invited'] == 'yes' ? '✓' : '✗'; ?> Inv
                                </span><br>
                                <span class="<?php echo $events_info['reception']['souvenir'] == 'yes' ? 'status-yes' : 'status-no'; ?>">
                                    <?php echo $events_info['reception']['souvenir'] == 'yes' ? '📦' : '○'; ?> Souvenir
                                </span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button onclick="openEditModal(<?php echo $family->id; ?>)" class="action-btn edit-btn">Edit</button>
                            <button onclick="deleteFamily(<?php echo $family->id; ?>)" class="action-btn delete-btn">Delete</button>
                            <button onclick="sendWhatsAppMessage(<?php echo $family->id; ?>)" class="action-btn whatsapp-btn" style="background: #25D366; color: white;">📱 WhatsApp</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for Add Family Form -->
    <div id="familyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Family</h2>
                <span class="close" onclick="closeModal()">&times;</span>
            </div>
            
            <form method="POST" id="familyForm">
                <input type="hidden" name="action" value="add_family">
                
                <div class="modal-body">
                    <!-- Basic Info Section -->
                    <div class="form-section">
                        <h3>📝 Basic Information</h3>
                        
                        <div class="form-group">
                            <label>Primary Guest Name *</label>
                            <input type="text" name="primary_guest_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" placeholder="+628123456789">
                        </div>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" placeholder="+628123456789">
                        </div>
                        
                        <div class="form-group">
                            <label>Number of Guests *</label>
                            <input type="number" name="pax_num" id="add_pax_num" min="1" max="6" required readonly style="background-color: #f1f1f1; cursor: not-allowed;" value="1">
                            <small style="color: #666; font-style: italic;">Automatically calculated: 1 (primary guest) + family members</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Invitation Type *</label>
                            <select name="invitation_type" required>
                                <option value="Digital">Digital</option>
                                <option value="Printed">Printed</option>
                            </select>
                        </div>
                        </div>
                    </div>
                    
                    <!-- Family Members Section -->
                    <div class="form-section">
                        <h3>👥 Family Members</h3>
                        <p><em>Primary guest is already counted. Add additional family members below:</em></p>
                        
                        <div id="guestFields">
                            <!-- Dynamic guest fields will be added here -->
                        </div>
                        
                        <button type="button" class="add-guest" onclick="addGuestField()">+ Add Family Member</button>
                    </div>
                    
                    <!-- Event Invitations Section -->
                    <div class="form-section">
                        <h3>🎉 Event Invitations</h3>
                        
                        <div class="event-checkboxes">
                            <?php foreach ($events as $event): ?>
                                <div class="event-item">
                                    <h4><?php echo $event->event_name; ?></h4>
                                    <div class="checkbox-group">
                                        <label>
                                            <input type="checkbox" name="<?php echo $event->event_type; ?>_invited">
                                            Invited to this event
                                        </label>
                                    </div>
                                    <div class="checkbox-group">
                                        <label>
                                            <input type="checkbox" name="<?php echo $event->event_type; ?>_souvenir">
                                            Gets souvenir
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="submit-btn">Add Family</button>
                </div>
            </form>
        </div>
    </div>
        
    <!-- Modal for Edit Family Form -->
    <div id="editFamilyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Family</h2>
                <span class="close" onclick="closeEditModal()">&times;</span>
            </div>
            
            <form method="POST" id="editFamilyForm">
                <input type="hidden" name="action" value="edit_family">
                <input type="hidden" name="family_id" id="edit_family_id">
                
                <div class="modal-body">
                    <!-- Basic Info Section -->
                    <div class="form-section">
                        <h3>📝 Basic Information</h3>
                        
                        <div class="form-group">
                            <label>Primary Guest Name *</label>
                            <input type="text" name="primary_guest_name" id="edit_primary_guest_name" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone_number" id="edit_phone_number" placeholder="+628123456789">
                        </div>
                        
                        <div class="form-group">
                            <label>Number of Guests *</label>
                            <input type="number" name="pax_num" id="edit_pax_num" min="1" max="6" required readonly style="background-color: #f1f1f1; cursor: not-allowed;">
                            <small style="color: #666; font-style: italic;">Automatically calculated: 1 (primary guest) + family members</small>
                        </div>
                        
                        <div class="form-group">
                            <label>Invitation Type *</label>
                            <select name="invitation_type" id="edit_invitation_type" required>
                                <option value="Digital">Digital</option>
                                <option value="Printed">Printed</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Family Members Section -->
                    <div class="form-section">
                        <h3>👥 Family Members</h3>
                        <p><em>Primary guest is already counted. Add additional family members below:</em></p>
                        
                        <div id="editGuestFields">
                            <!-- Dynamic guest fields will be added here -->
                        </div>
                        
                        <button type="button" class="add-guest" onclick="addEditGuestField()">+ Add Family Member</button>
                    </div>
                    
                    <!-- Event Invitations Section -->
                    <div class="form-section">
                        <h3>🎉 Event Invitations</h3>
                        
                        <div class="event-checkboxes" id="editEventCheckboxes">
                            <?php foreach ($events as $event): ?>
                                <div class="event-item">
                                    <h4><?php echo $event->event_name; ?></h4>
                                    <div class="checkbox-group">
                                        <label>
                                            <input type="checkbox" name="<?php echo $event->event_type; ?>_invited" id="edit_<?php echo $event->event_type; ?>_invited">
                                            Invited to this event
                                        </label>
                                    </div>
                                    <div class="checkbox-group">
                                        <label>
                                            <input type="checkbox" name="<?php echo $event->event_type; ?>_souvenir" id="edit_<?php echo $event->event_type; ?>_souvenir">
                                            Gets souvenir
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="submit-btn">Update Family</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    
    // Modal functions
    function openModal() {
        document.getElementById('familyModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('familyModal').style.display = 'none';
        document.getElementById('familyForm').reset();
        clearGuestFields();
    }

    // Guest field management
    function addGuestField() {
        const container = document.getElementById('guestFields');
        const index = container.children.length;
        
        const guestDiv = document.createElement('div');
        guestDiv.className = 'guest-field';
        guestDiv.innerHTML = `
            <div class="form-group">
                <label>Family Member ${index + 1}</label>
                <div class="guest-input-group" style="display: flex; gap: 10px; align-items: center;">
                    <input type="text" name="guest_names[]" placeholder="Enter name" style="flex: 1;">
                    <button type="button" class="remove-guest" onclick="removeGuestField(this)">Remove</button>
                </div>
            </div>
        `;
        
        container.appendChild(guestDiv);
        updateGuestCount();
    }

    function removeGuestField(button) {
        button.closest('.guest-field').remove();
        updateGuestLabels();
        updateGuestCount();
    }

    function updateGuestLabels() {
        const guestFields = document.querySelectorAll('#guestFields .guest-field');
        guestFields.forEach((field, index) => {
            const label = field.querySelector('label');
            label.textContent = `Family Member ${index + 1}`;
        });
    }

    function updateGuestCount() {
        const familyMembers = document.querySelectorAll('#guestFields .guest-field').length;
        const totalGuests = 1 + familyMembers; // 1 primary guest + family members
        document.getElementById('add_pax_num').value = totalGuests;
    }

    function clearGuestFields() {
        document.getElementById('guestFields').innerHTML = '';
        updateGuestCount(); // Reset count to 1 when clearing fields
    }

    // Edit Modal Functions
    function openEditModal(familyId) {
        document.getElementById('editFamilyModal').style.display = 'block';
        document.getElementById('edit_family_id').value = familyId;
        
        // Get family data via AJAX and populate the form
        populateEditForm(familyId);
    }

    function closeEditModal() {
        document.getElementById('editFamilyModal').style.display = 'none';
        // Clear the form
        document.getElementById('editFamilyForm').reset();
        clearEditGuestFields();
    }

    function populateEditForm(familyId) {
        // Clear existing data
        clearEditGuestFields();
        
        // Make AJAX request using WordPress AJAX
        const ajaxData = {
            action: 'get_family_data',
            family_id: familyId,
            nonce: '<?php echo wp_create_nonce('get_family_data_nonce'); ?>'
        };
        
        jQuery.post(ajaxurl, ajaxData)
            .done(function(response) {
                if (response.success) {
                    const family = response.data;
                    
                    // Populate basic info
                    document.getElementById('edit_primary_guest_name').value = family.primary_guest_name || '';
                    document.getElementById('edit_phone_number').value = family.phone_number || '';
                    document.getElementById('edit_invitation_type').value = family.invitation_type || 'Digital';
                    
                    // Populate family members
                    if (family.family_members && family.family_members.length > 0) {
                        family.family_members.forEach(memberName => {
                            if (memberName && memberName.trim()) {
                                addEditGuestField();
                                const guestInputs = document.querySelectorAll('#editGuestFields input[name="guest_names[]"]');
                                const lastInput = guestInputs[guestInputs.length - 1];
                                if (lastInput) {
                                    lastInput.value = memberName;
                                }
                            }
                        });
                    }
                    
                    // Update guest count after loading family members
                    updateEditGuestCount();
                    
                    // Reset all checkboxes first
                    document.querySelectorAll('#editEventCheckboxes input[type="checkbox"]').forEach(cb => cb.checked = false);
                    
                    // Populate event invitations
                    if (family.invitations) {
                        Object.keys(family.invitations).forEach(eventType => {
                            const invitation = family.invitations[eventType];
                            
                            const invitedCheckbox = document.getElementById(`edit_${eventType}_invited`);
                            const souvenirCheckbox = document.getElementById(`edit_${eventType}_souvenir`);
                            
                            if (invitedCheckbox) {
                                invitedCheckbox.checked = invitation.invited;
                            }
                            if (souvenirCheckbox) {
                                souvenirCheckbox.checked = invitation.souvenir;
                            }
                        });
                    }
                } else {
                    alert('Error loading family data: ' + (response.data || 'Unknown error'));
                }
            })
            .fail(function(xhr, status, error) {
                alert('Error loading family data: ' + error);
            });
    }

    function addEditGuestField() {
        const container = document.getElementById('editGuestFields');
        const index = container.children.length;
        
        const guestDiv = document.createElement('div');
        guestDiv.className = 'guest-field';
        guestDiv.innerHTML = `
            <div class="form-group">
                <label>Family Member ${index + 1}</label>
                <div class="guest-input-group">
                    <input type="text" name="guest_names[]" placeholder="Enter name">
                    <button type="button" class="remove-guest" onclick="removeEditGuestField(this)">Remove</button>
                </div>
            </div>
        `;
        
        container.appendChild(guestDiv);
        updateEditGuestCount();
    }

    function removeEditGuestField(button) {
        button.closest('.guest-field').remove();
        updateEditGuestLabels();
        updateEditGuestCount();
    }

    function updateEditGuestLabels() {
        const guestFields = document.querySelectorAll('#editGuestFields .guest-field');
        guestFields.forEach((field, index) => {
            const label = field.querySelector('label');
            label.textContent = `Family Member ${index + 1}`;
        });
    }

    function updateEditGuestCount() {
        const familyMembers = document.querySelectorAll('#editGuestFields .guest-field').length;
        const totalGuests = 1 + familyMembers; // 1 primary guest + family members
        document.getElementById('edit_pax_num').value = totalGuests;
    }

    function clearEditGuestFields() {
        document.getElementById('editGuestFields').innerHTML = '';
        updateEditGuestCount(); // Reset count to 1 when clearing fields
    }

    // Delete function
    function deleteFamily(familyId) {
        if (confirm('Are you sure you want to delete this family? This action cannot be undone.')) {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = `
                <input type="hidden" name="action" value="delete_family">
                <input type="hidden" name="family_id" value="${familyId}">
            `;
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('familyModal');
        const editModal = document.getElementById('editFamilyModal');
        if (event.target == modal) {
            closeModal();
        }
        if (event.target == editModal) {
            closeEditModal();
        }
    }
    
    // WhatsApp message function
    function sendWhatsAppMessage(familyId) {
        // Show loading state
        const button = document.querySelector(`[onclick="sendWhatsAppMessage(${familyId})"]`);
        const originalText = button.innerHTML;
        button.innerHTML = '⏳ Loading...';
        button.disabled = true;
        
        // Get family data via AJAX
        fetch(ajaxurl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_whatsapp_message',
                family_id: familyId,
                nonce: '<?php echo wp_create_nonce('whatsapp_message_nonce'); ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            // Restore button
            button.innerHTML = originalText;
            button.disabled = false;
            
            if (data.success) {
                // Clean phone number for WhatsApp API (remove + and spaces)
                const cleanPhone = data.data.phone.replace(/[\s+]/g, '');
                
                // Use the WhatsApp API format that handles emojis better
                const whatsappUrl = `https://api.whatsapp.com/send/?phone=${cleanPhone}&text=${encodeURIComponent(data.data.message)}&type=phone_number&app_absent=0`;
                window.open(whatsappUrl, '_blank');
            } else {
                alert('Error: ' + (data.data || 'Failed to generate message'));
            }
        })
        .catch(error => {
            // Restore button
            button.innerHTML = originalText;
            button.disabled = false;
            console.error('Error:', error);
            alert('Failed to generate WhatsApp message');
        });
    }
</script>
