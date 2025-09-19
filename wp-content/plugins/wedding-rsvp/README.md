# Wedding RSVP Plugin Integration Guide

## Plugin Overview
This wedding RSVP plugin provides a complete family-based invitation system with:
- Custom URLs for each family (e.g., yoursite.com/smith-family)
- Multiple guests per family with individual RSVP responses
- Multiple wedding events (tea ceremony, holy matrimony, reception)
- Admin interface for managing families and viewing responses
- Beautiful responsive RSVP forms

## Installation
1. Upload the `wedding-rsvp` folder to `/wp-content/plugins/`
2. Activate the plugin in WordPress admin
3. Go to "Wedding RSVP" in admin menu to start adding families

## Integration with Your Front-Page.php

### Step 1: Include Template Helpers
Add this to the top of your front-page.php (after the opening <?php tag):

```php
// Include RSVP template helpers if plugin is active
if (function_exists('render_wedding_rsvp_form')) {
    include_once(ABSPATH . 'wp-content/plugins/wedding-rsvp/template-helpers.php');
}
```

### Step 2: Add Conditional Content
Replace your static content with dynamic content based on family:

```php
<?php
// Check if this is a family-specific page
if (is_wedding_family_page()) {
    $family_info = get_family_display_info();
    $guest_names = get_formatted_guest_names();
} else {
    // Default content for general visitors
    $guest_names = "Our Beloved Family & Friends";
}
?>

<!-- Update your greeting section -->
<div class="hero-greeting-title">
    <?php echo esc_html($guest_names); ?>
</div>
```

### Step 3: Add RSVP Section
Add this section where you want the RSVP form to appear:

```php
<?php if (is_wedding_family_page()): ?>
<section id="rsvp" class="rsvp-section">
    <div class="container">
        <?php render_wedding_rsvp_form(); ?>
    </div>
</section>
<?php endif; ?>
```

### Step 4: Add Event Information
Display wedding events dynamically:

```php
<section id="wedding-details">
    <div class="container">
        <h2>Wedding Events</h2>
        <?php if (function_exists('display_wedding_events')): ?>
            <?php display_wedding_events(); ?>
        <?php else: ?>
            <!-- Your static event content as fallback -->
        <?php endif; ?>
    </div>
</section>
```

## Admin Usage

### Adding Families
1. Go to "Wedding RSVP" → "Families" in admin
2. Add family with:
   - Family Code: URL-friendly name (e.g., "smith-family")
   - Family Name: Display name (e.g., "The Smith Family")
   - Max Guests: Maximum number of people invited
   - Primary Phone: Contact number

### Adding Guests
1. In the families list, click "Add Guest" for any family
2. Enter guest name, phone (optional), and mark primary contact if needed
3. Guests will appear in order on the RSVP form

### Viewing Responses
1. Go to "Wedding RSVP" → "Responses" to see all RSVP submissions
2. Filter by family or event type
3. Export to CSV for planning purposes

## URL Structure
- General site: `yoursite.com/`
- Family invitations: `yoursite.com/family-code/`

Examples:
- `yoursite.com/smith-family/`
- `yoursite.com/dennis-emilia/`
- `yoursite.com/johnson-2024/`

## Database Tables Created
- `wp_wedding_guests`: Individual guests
- `wp_wedding_rsvp_responses`: RSVP responses
- `wp_wedding_events`: Wedding events

## Customization
- Modify CSS in `/assets/wedding-rsvp.css`
- Update JavaScript in `/assets/wedding-rsvp.js`
- Customize form templates in `template-helpers.php`

## Example Family Data
```
Family Code: dennis-emilia
Family Name: Dennis & Emilia
Max Guests: 4
Guests:
  1. Dennis Wijaya (Primary Contact)
  2. Emilia Chen
  3. Guest Name 3
  4. Guest Name 4

URL: yoursite.com/dennis-emilia/
```

## Security Features
- CSRF protection with WordPress nonces
- SQL injection prevention with prepared statements
- Input sanitization and validation
- Access control for admin functions

This plugin integrates seamlessly with your existing wedding invitation theme while adding powerful RSVP management capabilities!
