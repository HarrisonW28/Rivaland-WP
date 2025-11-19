<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<?php
// Get hero variant from page template or ACF
$hero_section = get_field('hero_section');
$hero_variant = $hero_section['hero_variant'] ?? 'home';
$hero_color = $hero_section['hero_color_scheme'] ?? 'dark';
$hero_background_image = $hero_section['hero_background_image'] ?? '';

// Set default based on page if no ACF data
if (empty($hero_section)) {
    if (is_front_page() || is_page_template('page-home.php')) {
        $hero_variant = 'home';
        $hero_color = 'dark';
    } elseif (is_page('about') || is_page_template('page-about.php')) {
        $hero_variant = 'about';
        $hero_color = 'dark';
    } elseif (is_page('services') || is_page_template('page-services.php')) {
        $hero_variant = 'services';
        $hero_color = 'dark';
    }
}

$hero_class = "hero hero--{$hero_variant} hero--{$hero_color}";

// Get background image URL
$background_image_url = '';
if (!empty($hero_background_image)) {
    if (is_array($hero_background_image)) {
        $background_image_url = $hero_background_image['url'] ?? '';
    } else {
        $background_image_url = $hero_background_image;
    }
}

// Default background images based on variant
if (empty($background_image_url)) {
    if ($hero_variant === 'home') {
        // Home hero always has a background image (default or ACF)
        $background_image_url = get_template_directory_uri() . '/assets/images/header.jpg';
    }
    // Services and About don't use background images by default (they use solid colors)
}

// Build inline style for background image
// Apply for home and services variants (if background image is set)
$hero_style = '';
if (!empty($background_image_url) && ($hero_variant === 'home' || $hero_variant === 'services')) {
    $hero_style = 'style="background-image: url(' . esc_url($background_image_url) . ');"';
}
?>

<header class="<?php echo esc_attr($hero_class); ?>" <?php echo $hero_style; ?>>
    <nav class="nav">
        <div class="logo">
            <?php
            // Use WordPress custom logo from Customizer
            if (has_custom_logo()) {
                $custom_logo_id = get_theme_mod('custom_logo');
                $logo = wp_get_attachment_image($custom_logo_id, 'full', false, [
                    'class' => 'custom-logo',
                    'alt' => get_bloginfo('name', 'display'),
                ]);
                echo '<a href="' . esc_url(home_url('/')) . '" class="custom-logo-link" rel="home">' . $logo . '</a>';
            } else {
                // Fallback to default logo
                $default_logo = get_template_directory_uri() . '/assets/svg/logo.svg';
                echo '<a href="' . esc_url(home_url('/')) . '"><img src="' . esc_url($default_logo) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '"></a>';
            }
            ?>
        </div>

        <button class="menu-toggle" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <?php
        $contact_url = get_permalink(get_page_by_path('contact')) ?: '#';
        
        // Get mobile logo
        $mobile_logo = '';
        if (has_custom_logo()) {
            $mobile_logo = wp_get_attachment_image(get_theme_mod('custom_logo'), 'full', false, [
                'class' => 'mobile-logo-img',
                'alt' => get_bloginfo('name', 'display'),
            ]);
        } else {
            $default_logo = get_template_directory_uri() . '/assets/svg/logo.svg';
            $mobile_logo = '<img src="' . esc_url($default_logo) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '" class="mobile-logo-img">';
        }
        
        wp_nav_menu([
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => 'nav-links',
            'items_wrap' => '<ul class="nav-links">%3$s<li class="mobile-divider"></li><li class="mobile-contact"><a href="' . esc_url($contact_url) . '" class="contact-btn-mobile"><span class="contact-text">Contact us</span><span class="contact-arrow"><img src="' . esc_url(get_template_directory_uri() . '/assets/svg/arrow.svg') . '" alt="arrow" class="button-arrow"></span></a></li><li class="mobile-logo">' . $mobile_logo . '</li></ul>',
            'fallback_cb' => false,
        ]);
        ?>

        <a href="<?php echo esc_url($contact_url); ?>" class="contact-btn">
            Contact us 
            <svg class="arrow" width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </nav>

    <?php
    // Hero content - always show on pages
    if (is_front_page() || is_page() || is_page_template()) {
        get_template_part('template-parts/hero');
    }
    ?>
</header>
