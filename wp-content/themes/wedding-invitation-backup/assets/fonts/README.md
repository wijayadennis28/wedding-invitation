# Local Fonts

This folder contains locally hosted font files for the wedding invitation theme.

## Fonts Included:

### Allura
- **File**: `Allura-Regular.woff2`
- **Usage**: Main script font for couple's names and decorative text
- **CSS Class**: `.font-allura`

### Dancing Script
- **Files**: 
  - `DancingScript-Regular.woff2` (Regular weight)
  - `DancingScript-Bold.woff2` (Bold weight)
- **Usage**: Alternative script font, used for "&" symbol and accents
- **CSS Class**: `.font-aniyah` (maps to Dancing Script Bold)

## How it works:

1. **fonts.css** - Contains @font-face declarations for all local fonts
2. **functions.php** - Enqueues the fonts.css file
3. **CSS classes** - Available in wedding-styles.css for easy use

## Benefits of Local Fonts:

- ✅ Faster loading (no external requests)
- ✅ Works offline
- ✅ Better performance
- ✅ No dependency on Google Fonts
- ✅ GDPR compliant

## Font Formats:

- **WOFF2**: Modern, compressed format with best browser support
- **font-display: swap**: Ensures text is visible during font load

## Usage in Templates:

```html
<h1 class="font-allura">Couple Names</h1>
<span class="font-aniyah">&</span>
```
