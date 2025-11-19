<?php
/**
 * Feature Template Part (50/50 Image Text Block)
 * 
 * @package Rivaland
 */

$feature_section = get_field('feature_section');
$eyebrow = $feature_section['eyebrow'] ?? 'Unrivalled expertise';
$description = $feature_section['description'] ?? '';
$button_text = $feature_section['button_text'] ?? 'More about us';
$button_url = $feature_section['button_url'] ?? get_permalink(get_page_by_path('about')) ?: '#';
$image = $feature_section['image'] ?? '';
$image_position = $feature_section['image_position'] ?? 'left';
$variant = $feature_section['variant'] ?? 'default';

// Default description if empty
if (empty($description)) {
    $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam semper interdum lacinia. Vestibulum pretium ante ut ligula fermentum rhoncus eget id neque. Pellentesque sit amet lacus convallis maximus in id felis. Suspendisse potenti. Donec et erat eget nulla dapibus bibendum. Cras feugiat condimentum enim dignissim varius. Integer tincidunt condimentum odio et tristique.";
}

// Default image if empty
if (empty($image)) {
    $image = get_template_directory_uri() . '/assets/images/feature.jpg';
}

$image_class = $image_position === 'right' ? 'feature--services' : '';
?>

<section class="feature <?php echo esc_attr($image_class); ?> feature--<?php echo esc_attr($variant); ?>">
    <div class="feature__image">
        <?php if ($image) : 
            if (is_array($image)) {
                $image_url = $image['url'];
                $image_alt = $image['alt'] ?? '';
            } else {
                $image_url = $image;
                $image_alt = '';
            }
        ?>
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
        <?php endif; ?>
    </div>
    <div class="feature__copy">
        <?php if ($eyebrow) : ?>
            <p class="feature__eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <?php endif; ?>
        <?php 
        // Handle WYSIWYG content
        if ($description) {
            if (is_string($description) && strip_tags($description) !== $description) {
                // Has HTML, output as-is
                echo wp_kses_post($description);
            } else {
                // Plain text, wrap in paragraph
                echo '<p>' . esc_html($description) . '</p>';
            }
        }
        ?>
        <?php if ($button_text && $button_url) : ?>
            <a class="link-button" href="<?php echo esc_url($button_url); ?>">
                <?php echo esc_html($button_text); ?> 
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/arrow.svg'); ?>" alt="arrow" class="button-arrow">
            </a>
        <?php endif; ?>
    </div>
</section>

