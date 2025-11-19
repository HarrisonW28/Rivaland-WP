<?php
/**
 * Button Component
 * 
 * Standardized button component for WordPress template parts
 * 
 * @param array $args {
 *     @type string $text       Button text (required)
 *     @type string $url        Button URL (default: '#')
 *     @type string $variant    Button variant: primary, secondary, outline, ghost (default: 'primary')
 *     @type string $size       Button size: sm, md, lg (default: 'md')
 *     @type bool   $show_icon  Whether to show arrow icon (default: true)
 *     @type string $class      Additional CSS classes
 *     @type string $id         Button ID attribute
 * }
 */
$text = $args['text'] ?? 'Button';
$url = $args['url'] ?? '#';
$variant = $args['variant'] ?? 'primary';
$size = $args['size'] ?? 'md';
$show_icon = $args['show_icon'] ?? true;
$class = $args['class'] ?? '';
$id = $args['id'] ?? '';
?>
<a 
    href="<?php echo esc_url($url); ?>" 
    class="button button--<?php echo esc_attr($variant); ?> button--<?php echo esc_attr($size); ?> <?php echo esc_attr($class); ?>"
    <?php if ($id): ?>id="<?php echo esc_attr($id); ?>"<?php endif; ?>
>
    <?php echo esc_html($text); ?>
    <?php if ($show_icon): ?>
        <span class="button__icon">
            <?php get_template_part('components/icon', 'arrow'); ?>
        </span>
    <?php endif; ?>
</a>

