jQuery(document).ready(function($) {
    
    // RSVP Form Handler
    $('#wedding-rsvp-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('action', 'wedding_rsvp_submit');
        formData.append('nonce', wedding_rsvp_ajax.nonce);
        
        // Collect all form data
        var responses = {};
        var familyId = $(this).data('family-id');
        
        $('.guest-responses').each(function() {
            var guestId = $(this).data('guest-id');
            responses[guestId] = {};
            
            $(this).find('.event-response').each(function() {
                var eventType = $(this).data('event-type');
                responses[guestId][eventType] = {
                    status: $(this).find('input[name$="[status]"]:checked').val() || 'no',
                    dietary: $(this).find('textarea[name$="[dietary]"]').val() || '',
                    notes: $(this).find('textarea[name$="[notes]"]').val() || ''
                };
            });
        });
        
        formData.append('family_id', familyId);
        formData.append('responses', JSON.stringify(responses));
        
        // Show loading state
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.text();
        submitBtn.text('Submitting...').prop('disabled', true);
        
        // Submit via AJAX
        $.ajax({
            url: wedding_rsvp_ajax.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    // Show success message
                    showMessage('RSVP submitted successfully! Thank you for your response.', 'success');
                    
                    // Scroll to thank you message or disable form
                    setTimeout(function() {
                        $('.rsvp-form-container').fadeOut(500, function() {
                            $('.rsvp-thank-you').fadeIn(500);
                        });
                    }, 1500);
                } else {
                    showMessage('Error submitting RSVP. Please try again.', 'error');
                    submitBtn.text(originalText).prop('disabled', false);
                }
            },
            error: function() {
                showMessage('Error submitting RSVP. Please try again.', 'error');
                submitBtn.text(originalText).prop('disabled', false);
            }
        });
    });
    
    // Guest attendance change handler
    $('.attendance-radio').on('change', function() {
        var eventContainer = $(this).closest('.event-response');
        var value = $(this).val();
        
        if (value === 'yes') {
            eventContainer.find('.additional-info').slideDown(300);
            eventContainer.addClass('attending');
        } else {
            eventContainer.find('.additional-info').slideUp(300);
            eventContainer.removeClass('attending');
        }
    });
    
    // Initialize form state
    $('.attendance-radio:checked').each(function() {
        if ($(this).val() === 'yes') {
            $(this).closest('.event-response').find('.additional-info').show();
            $(this).closest('.event-response').addClass('attending');
        }
    });
    
    // Guest name personalization
    if (window.weddingGuestNames && window.weddingGuestNames.length > 0) {
        updatePersonalization();
    }
    
    function showMessage(message, type) {
        var messageClass = type === 'success' ? 'notice-success' : 'notice-error';
        var messageHtml = '<div class="rsvp-message ' + messageClass + '">' + message + '</div>';
        
        $('.rsvp-messages').html(messageHtml).show();
        
        // Auto-hide after 5 seconds
        setTimeout(function() {
            $('.rsvp-message').fadeOut();
        }, 5000);
        
        // Scroll to message
        $('html, body').animate({
            scrollTop: $('.rsvp-messages').offset().top - 100
        }, 500);
    }
    
    function updatePersonalization() {
        // Update any personalized text with guest names
        var guestNames = window.weddingGuestNames;
        var nameText = '';
        
        if (guestNames.length === 1) {
            nameText = guestNames[0];
        } else if (guestNames.length === 2) {
            nameText = guestNames.join(' and ');
        } else if (guestNames.length > 2) {
            nameText = guestNames.slice(0, -1).join(', ') + ', and ' + guestNames[guestNames.length - 1];
        }
        
        $('.personalized-names').text(nameText);
    }
    
    // Form validation
    function validateRSVPForm() {
        var isValid = true;
        var hasAtLeastOneResponse = false;
        
        $('.guest-responses').each(function() {
            var guestHasResponse = false;
            
            $(this).find('.event-response').each(function() {
                var selectedStatus = $(this).find('input[name$="[status]"]:checked').val();
                if (selectedStatus) {
                    guestHasResponse = true;
                    hasAtLeastOneResponse = true;
                }
            });
            
            if (!guestHasResponse) {
                $(this).addClass('error');
                isValid = false;
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (!hasAtLeastOneResponse) {
            showMessage('Please respond for at least one guest and one event.', 'error');
            isValid = false;
        }
        
        return isValid;
    }
    
    // Add validation to form submit
    $('#wedding-rsvp-form').on('submit', function(e) {
        if (!validateRSVPForm()) {
            e.preventDefault();
            return false;
        }
    });
    
    // Real-time form validation
    $('.attendance-radio').on('change', function() {
        $(this).closest('.guest-responses').removeClass('error');
    });
});

// Global function to get family data (accessible from your front-page.php)
function getWeddingFamilyData() {
    return window.weddingFamilyData || null;
}

function getWeddingGuestsData() {
    return window.weddingGuestsData || [];
}
