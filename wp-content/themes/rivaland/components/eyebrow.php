<?php
/**
 * Eyebrow Component
 * 
 * Standardized eyebrow/label component for WordPress template parts
 * 
 * @param array $args {
 *     @type string $text       Eyebrow text (required)
 *     @type string $variant    Color variant: primary, secondary, light, dark (default: 'primary')
 *     @type string $class      Additional CSS classes
 *     @type string $tag        HTML tag to use: p, span, div (default: 'p')
 * }
 */
$text = $args['text'] ?? '';
$variant = $args['variant'] ?? 'primary';
$class = $args['class'] ?? '';
$tag = $args['tag'] ?? 'p';
?>
<<?php echo esc_attr($tag); ?> class="eyebrow eyebrow--<?php echo esc_attr($variant); ?> <?php echo esc_attr($class); ?>">
    <?php echo esc_html($text); ?>
</<?php echo esc_attr($tag); ?>>

