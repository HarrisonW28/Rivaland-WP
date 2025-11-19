<?php
/**
 * Arrow Icon Component
 * 
 * Standardized arrow icon component for WordPress template parts
 * 
 * @param array $args {
 *     @type string $direction  Arrow direction: right, left, up, down (default: 'right')
 *     @type string $class       Additional CSS classes
 *     @type string $size        Icon size: sm, md, lg (default: 'sm')
 * }
 */
$direction = $args['direction'] ?? 'right';
$class = $args['class'] ?? '';
$size = $args['size'] ?? 'sm';
?>
<svg 
    class="icon icon-arrow icon-arrow--<?php echo esc_attr($direction); ?> icon--<?php echo esc_attr($size); ?> <?php echo esc_attr($class); ?>" 
    width="19" 
    height="16" 
    viewBox="0 0 19 16" 
    fill="none" 
    xmlns="http://www.w3.org/2000/svg"
    aria-hidden="true"
>
    <?php if ($direction === 'left'): ?>
        <path d="M8 15.5L0.5 8M0.5 8L8 0.5M0.5 8H18.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
    <?php elseif ($direction === 'up'): ?>
        <path d="M15.5 8L8 0.5M8 0.5L0.5 8M8 0.5V15.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
    <?php elseif ($direction === 'down'): ?>
        <path d="M3.5 8L11 15.5M11 15.5L18.5 8M11 15.5V0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
    <?php else: // right (default) ?>
        <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
    <?php endif; ?>
</svg>

