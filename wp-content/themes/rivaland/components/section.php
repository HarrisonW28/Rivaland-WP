<?php
/**
 * Section Component
 * 
 * Standardized section wrapper component for WordPress template parts
 * 
 * @param array $args {
 *     @type string $type       Section type: intro, services, projects, news, feature, testimonial (default: 'default')
 *     @type string $variant    Color variant: light, dark (default: 'light')
 *     @type string $class      Additional CSS classes
 *     @type string $id         Section ID attribute
 *     @type string $content    Section content (HTML)
 * }
 */
$type = $args['type'] ?? 'default';
$variant = $args['variant'] ?? 'light';
$class = $args['class'] ?? '';
$id = $args['id'] ?? '';
$content = $args['content'] ?? '';
?>
<section 
    class="section section--<?php echo esc_attr($type); ?> section--<?php echo esc_attr($variant); ?> <?php echo esc_attr($class); ?>"
    <?php if ($id): ?>id="<?php echo esc_attr($id); ?>"<?php endif; ?>
>
    <div class="section__content">
        <?php echo $content; ?>
    </div>
</section>

