<?php
/**
 * Hero Template Part
 * 
 * @package Rivaland
 */

$hero_section = get_field('hero_section');
$hero_title = $hero_section['hero_title'] ?? get_the_title();
$hero_highlight = $hero_section['hero_highlight'] ?? '';
$hero_variant = $hero_section['hero_variant'] ?? 'home';
$hero_image = $hero_section['hero_image'] ?? ''; // For services hero-image div

// If no ACF data, use default based on page
if (empty($hero_section)) {
    if (is_front_page()) {
        $hero_title = "Specialist land promotion\nservices to help you gain\nplanning consent";
        $hero_highlight = "planning consent";
        $hero_variant = 'home';
    } elseif (is_page('about')) {
        $hero_title = "A best in class offering with a proven pedigree of success";
        $hero_variant = 'about';
    } elseif (is_page('services') || is_page_template('page-services.php')) {
        $hero_title = "What we do";
        $hero_variant = 'services';
    }
}

// Get hero image for services variant
$hero_image_url = '';
$hero_image_alt = '';
if ($hero_variant === 'services' && !empty($hero_image)) {
    if (is_array($hero_image)) {
        $hero_image_url = $hero_image['url'] ?? '';
        $hero_image_alt = $hero_image['alt'] ?? 'Hero image';
    } else {
        $hero_image_url = $hero_image;
        $hero_image_alt = 'Hero image';
    }
}

// Default services hero image if empty
if ($hero_variant === 'services' && empty($hero_image_url)) {
    $hero_image_url = get_template_directory_uri() . '/assets/images/services-hero.jpg';
    $hero_image_alt = 'Aerial view of residential area';
}
?>

<div class="hero-content">
    <h1>
        <?php
        if ($hero_highlight && strpos($hero_title, $hero_highlight) !== false) {
            // Split title by highlight text
            $title_parts = explode($hero_highlight, $hero_title);
            if (count($title_parts) === 2) {
                echo esc_html($title_parts[0]);
                echo '<span class="highlight">' . esc_html($hero_highlight) . '</span>';
                echo esc_html($title_parts[1]);
            } else {
                // Handle multi-line titles with <br> tags
                $lines = explode("\n", $hero_title);
                foreach ($lines as $line) {
                    if (strpos($line, $hero_highlight) !== false) {
                        $line_parts = explode($hero_highlight, $line);
                        if (count($line_parts) === 2) {
                            echo esc_html($line_parts[0]);
                            echo '<span class="highlight">' . esc_html($hero_highlight) . '</span>';
                            echo esc_html($line_parts[1]);
                        } else {
                            echo esc_html($line);
                        }
                    } else {
                        echo esc_html($line);
                    }
                    if ($line !== end($lines)) {
                        echo '<br>';
                    }
                }
            }
        } else {
            // Handle multi-line titles
            $lines = explode("\n", $hero_title);
            foreach ($lines as $index => $line) {
                echo esc_html($line);
                if ($index < count($lines) - 1) {
                    echo '<br>';
                }
            }
        }
        ?>
    </h1>
</div>

<?php if ($hero_variant === 'services' && !empty($hero_image_url)) : ?>
    <div class="hero-image">
        <img src="<?php echo esc_url($hero_image_url); ?>" alt="<?php echo esc_attr($hero_image_alt); ?>">
    </div>
<?php endif; ?>

