<?php
/**
 * Feature Team Template Part
 * 
 * @package Rivaland
 */

$team_section = get_field('team_section');
$heading = $team_section['heading'] ?? 'Led by Andy Wilkins';
$description = $team_section['description'] ?? '';
$image = $team_section['image'] ?? '';
$button_text = $team_section['button_text'] ?? 'Connect on LinkedIn';
$button_url = $team_section['button_url'] ?? '#';

// Default description if empty
if (empty($description)) {
    $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam semper interdum lacinia. Vestibulum pretium ante ut ligula fermentum rhoncus eget id neque. Pellentesque sit amet est a lectus convallis maximus in et felis. Suspendisse potenti. Donec id erat eget nulla dapibus bibendum. Cras feugiat condimentum enim dignissim varius. Integer tincidunt condimentum odio et tristique.";
}

// Default image if empty
if (empty($image)) {
    $image = get_template_directory_uri() . '/assets/images/headshot.png';
}

// Handle image - could be array (ACF image) or string (URL)
if (is_array($image)) {
    $image_url = $image['url'] ?? '';
    $image_alt = $image['alt'] ?? '';
} else {
    $image_url = $image;
    $image_alt = '';
}
?>

<section class="feature feature--team feature--light">
    <div class="feature-image">
        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt ?: $heading); ?>">
    </div>
    <div class="feature-copy">
        <h2 class="team-heading"><?php echo esc_html($heading); ?></h2>
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
            <a class="link-button team-button" href="<?php echo esc_url($button_url); ?>">
                <?php echo esc_html($button_text); ?> 
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/arrow.svg'); ?>" alt="arrow" class="button-arrow">
            </a>
        <?php endif; ?>
    </div>
</section>

