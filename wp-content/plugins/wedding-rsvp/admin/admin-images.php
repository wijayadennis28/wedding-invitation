<?php
// Handle form submission
if (isset($_POST['submit']) && check_admin_referer('wedding_images_nonce')) {
    $sections = [
        'hero_mobile' => 'Hero Section (Mobile)',
        'hero_desktop' => 'Hero Section (Desktop)',
        'wedding_details' => 'Wedding Details',
        'ceremony_reception' => 'Ceremony & Reception', 
        'rsvp' => 'RSVP',
        'love_story' => 'Love Story',
        'detailed_love_story' => 'Detailed Love Story',
        'final_love_story' => 'Final Love Story',
        'image_slider' => 'Image Slider',
        'rsvp_final' => 'Final RSVP'
    ];
    
    foreach ($sections as $section_key => $section_name) {
        if (!empty($_POST[$section_key . '_image'])) {
            update_option('wedding_' . $section_key . '_image', sanitize_text_field($_POST[$section_key . '_image']));
        }
    }
    
    echo '<div class="notice notice-success"><p>Image settings saved successfully!</p></div>';
}

// Get current settings
$sections = [
    'hero_mobile' => 'Hero Section (Mobile)',
    'hero_desktop' => 'Hero Section (Desktop)',
    'wedding_details' => 'Wedding Details',
    'ceremony_reception' => 'Ceremony & Reception', 
    'rsvp' => 'RSVP',
    'love_story' => 'Love Story',
    'detailed_love_story' => 'Detailed Love Story',
    'final_love_story' => 'Final Love Story',
    'image_slider' => 'Image Slider',
    'rsvp_final' => 'Final RSVP'
];

$current_images = [];
foreach ($sections as $section_key => $section_name) {
    $current_images[$section_key] = get_option('wedding_' . $section_key . '_image', '');
}
?>

<div class="wrap">
    <h1>Wedding Images Settings</h1>
    <p>Upload and assign different background images for each section of your wedding invitation.</p>
    
    <form method="post" action="">
        <?php wp_nonce_field('wedding_images_nonce'); ?>
        
        <table class="form-table">
            <?php foreach ($sections as $section_key => $section_name): ?>
            <tr>
                <th scope="row">
                    <label for="<?php echo $section_key; ?>_image"><?php echo $section_name; ?></label>
                </th>
                <td>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="text" 
                               id="<?php echo $section_key; ?>_image" 
                               name="<?php echo $section_key; ?>_image" 
                               value="<?php echo esc_attr($current_images[$section_key]); ?>" 
                               class="regular-text" 
                               placeholder="Image URL" />
                        
                        <button type="button" 
                                class="button upload-image-button" 
                                data-target="<?php echo $section_key; ?>_image">
                            Select Image
                        </button>
                        
                        <?php if ($current_images[$section_key]): ?>
                        <button type="button" 
                                class="button remove-image-button" 
                                data-target="<?php echo $section_key; ?>_image">
                            Remove
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($current_images[$section_key]): ?>
                    <div style="margin-top: 10px;">
                        <img src="<?php echo esc_url($current_images[$section_key]); ?>" 
                             style="max-width: 200px; height: auto; border: 1px solid #ddd;" 
                             alt="<?php echo $section_name; ?> Preview" />
                    </div>
                    <?php endif; ?>
                    
                    <p class="description">
                        <?php if ($section_key === 'hero_mobile'): ?>
                        Upload an image for the hero section background on mobile devices (portrait orientation).
                        <?php elseif ($section_key === 'hero_desktop'): ?>
                        Upload an image for the hero section background on desktop/tablet devices (landscape orientation).
                        <?php else: ?>
                        Upload an image for the <?php echo $section_name; ?> section background.
                        <?php endif; ?>
                        <?php if (!$current_images[$section_key]): ?>
                        <br><em>Currently using default image<?php echo ($section_key === 'hero_mobile' || $section_key === 'hero_desktop') ? '' : ' (s.jpg)'; ?></em>
                        <?php endif; ?>
                    </p>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <?php submit_button('Save Image Settings'); ?>
    </form>
</div>

<script>
jQuery(document).ready(function($) {
    // Check if wp.media is available
    if (typeof wp === 'undefined' || typeof wp.media === 'undefined') {
        console.error('WordPress media scripts not loaded');
        alert('Error: WordPress media library not available. Please refresh the page.');
        return;
    }
    
    // Media uploader for image selection
    $('.upload-image-button').click(function(e) {
        e.preventDefault();
        
        var targetInput = $(this).data('target');
        var button = $(this);
        
        // Create the media uploader
        var mediaUploader = wp.media({
            title: 'Select Image for ' + button.closest('tr').find('label').text(),
            button: {
                text: 'Use this image'
            },
            multiple: false,
            library: {
                type: 'image'
            }
        });
        
        mediaUploader.on('select', function() {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            console.log('Selected image:', attachment); // Debug log
            $('#' + targetInput).val(attachment.url);
            
            // Add preview image
            var previewHtml = '<div style="margin-top: 10px;"><img src="' + attachment.url + '" style="max-width: 200px; height: auto; border: 1px solid #ddd;" alt="Preview" /></div>';
            $('#' + targetInput).closest('td').find('img').parent().remove();
            $('#' + targetInput).closest('td').append(previewHtml);
            
            // Add remove button if not exists
            if (!$('#' + targetInput).siblings('.remove-image-button').length) {
                $('#' + targetInput).after('<button type="button" class="button remove-image-button" data-target="' + targetInput + '" style="margin-left: 5px;">Remove</button>');
            }
        });
        
        mediaUploader.open();
    });
    
    // Remove image functionality
    $(document).on('click', '.remove-image-button', function(e) {
        e.preventDefault();
        
        var targetInput = $(this).data('target');
        $('#' + targetInput).val('');
        $(this).closest('td').find('img').parent().remove();
        $(this).remove();
    });
});
</script>

<style>
.form-table th {
    width: 200px;
}

.upload-image-button {
    background: #0073aa;
    color: white;
    border-color: #0073aa;
}

.upload-image-button:hover {
    background: #005a87;
    border-color: #005a87;
}

.remove-image-button {
    background: #dc3232;
    color: white;
    border-color: #dc3232;
}

.remove-image-button:hover {
    background: #a02622;
    border-color: #a02622;
}
</style>