<?php
/**
 * Intro Template Part
 * 
 * @package Rivaland
 */

$intro_section = get_field('intro_section');
$intro_text = $intro_section['intro_text'] ?? '';
$intro_variant = $intro_section['intro_variant'] ?? 'default';
$intro_color = $intro_section['intro_color_scheme'] ?? 'light';

// Default text if no ACF data
if (empty($intro_text) && is_front_page()) {
    $intro_text = "Riva Land is a privately owned, specialist land promoter. We're experts at promoting land through the planning system, fully committed to a transparent, inclusive process that optimises land value.";
} elseif (empty($intro_text) && is_page('about')) {
    $intro_text = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam semper interdum lacinia. Vestibulum pretium ante ut ligula fermentum rhoncus eget id neque. Pellentesque sit amet est a lectus convallis maximus in et felis.";
    $intro_variant = 'about';
    $intro_color = 'light';
}

if ($intro_text) :
?>
<section class="intro<?php echo $intro_variant !== 'default' ? ' intro--' . esc_attr($intro_variant) : ''; ?><?php echo $intro_color !== 'light' ? ' intro--' . esc_attr($intro_color) : ''; ?>">
    <p><?php echo wp_kses_post($intro_text); ?></p>
</section>
<?php endif; ?>

