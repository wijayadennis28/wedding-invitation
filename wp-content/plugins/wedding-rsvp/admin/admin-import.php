<?php
// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1>Import Guest List from CSV</h1>
    
    <div class="notice notice-info">
        <p><strong>CSV Format:</strong> Your CSV should have columns: type, guest_name, phone_num, pax_num, guest_2, guest_3, guest_4, guest_5, guest_6, church_inv, church_souvenir, teapai_inv, teapai_souvenir, reception_inv, reception_souvenir</p>
    </div>
    
    <div class="csv-import-section">
        <h2>Upload CSV File</h2>
        <form id="csv-import-form" enctype="multipart/form-data">
            <table class="form-table">
                <tr>
                    <th scope="row">CSV File</th>
                    <td>
                        <input type="file" name="csv_file" id="csv_file" accept=".csv" required>
                        <p class="description">Select your guest list CSV file to import.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Import Options</th>
                    <td>
                        <label>
                            <input type="checkbox" name="clear_existing" id="clear_existing" value="1">
                            Clear existing guest data before import
                        </label>
                        <p class="description">⚠️ This will delete all current guests before importing new data.</p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <button type="submit" class="button button-primary" id="import-btn">
                    Import Guests
                </button>
                <span class="spinner" id="import-spinner"></span>
            </p>
        </form>
        
        <div id="import-results" style="display:none;">
            <h3>Import Results</h3>
            <div id="import-message"></div>
        </div>
    </div>
    
    <div class="csv-sample-section">
        <h2>Sample CSV Format</h2>
        <p>Your CSV should look like this:</p>
        <pre style="background: #f0f0f0; padding: 15px; overflow-x: auto;">
type,guest_name,phone_num,pax_num,guest_2,guest_3,guest_4,guest_5,guest_6,church_inv,church_souvenir,teapai_inv,teapai_souvenir,reception_inv,reception_souvenir
Printed,Dennis Wijaya,+6287876211638,2,Emilia Bewintara,,,,,yes,yes,yes,no,yes,yes
Printed,Nikolas Budy Bewintara,+6281348097788,4,Patricia Lindawati,Eric Manzo Bewintara,Edmund Manuel Bewintara,,,yes,yes,yes,yes,yes,yes
Digital,Janet Lukito,,1,,,,,,yes,yes,no,no,yes,yes
        </pre>
    </div>
    
    <div class="existing-csv-section">
        <h2>Use Existing CSV</h2>
        <?php 
        $csv_file = WEDDING_RSVP_PLUGIN_DIR . 'inv_db.csv';
        if (file_exists($csv_file)): 
        ?>
            <p>There's already a CSV file in your plugin directory: <code>inv_db.csv</code></p>
            <button type="button" class="button button-secondary" id="import-existing-csv">
                Import Existing inv_db.csv
            </button>
        <?php else: ?>
            <p>No existing CSV file found in plugin directory.</p>
        <?php endif; ?>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#csv-import-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData();
        var fileInput = $('#csv_file')[0];
        
        if (fileInput.files.length === 0) {
            alert('Please select a CSV file');
            return;
        }
        
        formData.append('action', 'import_csv_guests');
        formData.append('csv_file', fileInput.files[0]);
        formData.append('clear_existing', $('#clear_existing').is(':checked') ? '1' : '0');
        formData.append('nonce', '<?php echo wp_create_nonce('csv_import_nonce'); ?>');
        
        $('#import-btn').prop('disabled', true);
        $('#import-spinner').addClass('is-active');
        
        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#import-btn').prop('disabled', false);
                $('#import-spinner').removeClass('is-active');
                
                if (response.success) {
                    $('#import-message').html('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
                    if (response.data.errors.length > 0) {
                        $('#import-message').append('<div class="notice notice-warning"><p>Errors: ' + response.data.errors.join(', ') + '</p></div>');
                    }
                } else {
                    $('#import-message').html('<div class="notice notice-error"><p>Error: ' + response.data + '</p></div>');
                }
                
                $('#import-results').show();
            },
            error: function() {
                $('#import-btn').prop('disabled', false);
                $('#import-spinner').removeClass('is-active');
                $('#import-message').html('<div class="notice notice-error"><p>Upload failed. Please try again.</p></div>');
                $('#import-results').show();
            }
        });
    });
    
    $('#import-existing-csv').on('click', function() {
        if (confirm('Import the existing inv_db.csv file? This will add all guests from the file.')) {
            $(this).prop('disabled', true).text('Importing...');
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'import_existing_csv',
                    nonce: '<?php echo wp_create_nonce('csv_import_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Successfully imported guests from inv_db.csv!');
                        location.reload();
                    } else {
                        alert('Error: ' + response.data);
                    }
                },
                error: function() {
                    alert('Import failed. Please try again.');
                },
                complete: function() {
                    $('#import-existing-csv').prop('disabled', false).text('Import Existing inv_db.csv');
                }
            });
        }
    });
});
</script>

<style>
.csv-import-section,
.csv-sample-section,
.existing-csv-section {
    background: #fff;
    border: 1px solid #ccd0d4;
    padding: 20px;
    margin: 20px 0;
    border-radius: 4px;
}

.csv-import-section h2,
.csv-sample-section h2,
.existing-csv-section h2 {
    margin-top: 0;
}

#import-spinner {
    float: none;
    margin-left: 10px;
}

pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>