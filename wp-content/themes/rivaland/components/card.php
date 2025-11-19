<?php
/**
 * Card Component
 * 
 * Standardized card component for WordPress template parts
 * 
 * @param array $args {
 *     @type string $type       Card type: project, news, service, feature (default: 'default')
 *     @type array  $image      Image array with 'url' and 'alt' keys
 *     @type string $eyebrow    Eyebrow/label text
 *     @type string $title      Card title
 *     @type string $text       Card description text
 *     @type array  $link       Link array with 'url' and 'text' keys
 *     @type string $meta       Meta text (for project cards)
 *     @type string $location   Location text (for project cards)
 *     @type string $status     Status text (for project cards)
 *     @type string $variant    Color variant: light, dark (default: 'light')
 *     @type string $class      Additional CSS classes
 * }
 */
$type = $args['type'] ?? 'default';
$image = $args['image'] ?? null;
$eyebrow = $args['eyebrow'] ?? null;
$title = $args['title'] ?? null;
$text = $args['text'] ?? null;
$link = $args['link'] ?? null;
$meta = $args['meta'] ?? null;
$location = $args['location'] ?? null;
$status = $args['status'] ?? null;
$variant = $args['variant'] ?? 'light';
$class = $args['class'] ?? '';
?>
<article class="card card--<?php echo esc_attr($type); ?> card--<?php echo esc_attr($variant); ?> <?php echo esc_attr($class); ?>">
    <?php if ($image): ?>
        <div class="card__image">
            <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt'] ?? ''); ?>">
        </div>
    <?php endif; ?>
    
    <div class="card__content">
        <?php if ($eyebrow): ?>
            <p class="card__eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <?php endif; ?>
        
        <?php if ($title): ?>
            <h3 class="card__title"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>
        
        <?php if ($meta): ?>
            <p class="card__meta">
                <?php echo esc_html($meta); ?>
                <?php if ($status): ?>
                    <span class="card__status"><?php echo esc_html($status); ?></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>
        
        <?php if ($location): ?>
            <p class="card__location"><?php echo wp_kses_post(nl2br($location)); ?></p>
        <?php endif; ?>
        
        <?php if ($text): ?>
            <p class="card__text"><?php echo esc_html($text); ?></p>
        <?php endif; ?>
        
        <?php if ($link): ?>
            <a href="<?php echo esc_url($link['url']); ?>" class="card__link">
                <?php echo esc_html($link['text']); ?>
                <span class="card__arrow">
                    <?php get_template_part('components/icon', 'arrow'); ?>
                </span>
            </a>
        <?php endif; ?>
        
        <?php if ($type === 'project' && !$link): ?>
            <span class="card__arrow">
                <?php get_template_part('components/icon', 'arrow'); ?>
            </span>
        <?php endif; ?>
    </div>
</article>

