# WordPress Custom Logo Setup

## Overview

The site logo now uses WordPress's native **Custom Logo** feature from the Customizer instead of ACF settings. This is the standard WordPress way to handle site logos.

## How to Set the Logo

1. Go to **Appearance > Customize** in WordPress Admin
2. Click on **Site Identity**
3. Click **Select logo** button
4. Upload or select your logo image
5. Click **Publish**

The logo will automatically appear in:
- Header navigation
- Mobile menu
- Footer

## Theme Support

The theme now supports custom logos with:
- Flexible height and width
- Maximum recommended size: 400px width × 100px height
- SVG support (if uploaded)

## Fallback

If no custom logo is set, the theme uses the default logo:
- `assets/svg/logo.svg`

## Code Implementation

### Header Logo
```php
if (has_custom_logo()) {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image($custom_logo_id, 'full', false, [
        'class' => 'custom-logo',
        'alt' => get_bloginfo('name', 'display'),
    ]);
    echo '<a href="' . esc_url(home_url('/')) . '">' . $logo . '</a>';
} else {
    // Fallback to default
}
```

### Mobile Logo
The mobile menu logo also uses the custom logo if set, otherwise falls back to the default SVG.

### Footer Logo
The footer logo uses the same custom logo or default fallback.

## Benefits

✅ **Standard WordPress approach** - Uses built-in Customizer
✅ **Better UX** - Logo appears in Customizer preview
✅ **No ACF dependency** - Works without ACF plugin
✅ **Automatic alt text** - Uses site name for accessibility
✅ **Consistent** - Same logo across header, mobile menu, and footer

## Removing ACF Logo Field

If you had an ACF options page with a logo field, you can:
1. Remove it from ACF (optional)
2. The theme will now use WordPress Customizer logo instead

The ACF logo field is no longer used, but won't cause any issues if it remains.

