<?php
/**
 * Approach Template Part
 * 
 * @package Rivaland
 */

$approach_section = get_field('approach_section');
$title = $approach_section['title'] ?? 'Our approach';
$items = $approach_section['items'] ?? [];

// Default items if empty
if (empty($items)) {
    $items = [
        [
            'heading' => 'Practical',
            'text' => 'As soon as we have been selected as the preferred Promoter, we work quickly and positively to enhance the process, taking a practical and creative approach to secure a solution that offers real value.',
            'number' => 1,
            'icon' => get_template_directory_uri() . '/assets/svg/gear.svg',
        ],
        [
            'heading' => 'Strategic',
            'text' => 'We develop comprehensive strategies that align with local planning policies and stakeholder interests, ensuring sustainable and viable outcomes for all parties involved.',
            'number' => 2,
            'icon' => get_template_directory_uri() . '/assets/svg/lightbulb.svg',
        ],
    ];
}

if (empty($items)) {
    return;
}
?>

<section class="approach-section">
    <div class="approach-progress"></div>
    <div class="approach-left">
        <h2 class="approach-title">
            <span><?php echo esc_html($title); ?></span>
        </h2>
    </div>
    <div class="approach-divider"></div>
    <div class="approach-container">
        <?php foreach ($items as $item) : 
            $heading = $item['heading'] ?? '';
            $text = $item['text'] ?? '';
            $number = $item['number'] ?? 1;
            $icon = $item['icon'] ?? '';
            
            // Handle icon - could be array (ACF image) or string (URL)
            if (is_array($icon)) {
                $icon_url = $icon['url'] ?? '';
            } else {
                $icon_url = $icon;
            }
        ?>
            <div class="approach-page">
                <div class="approach-right">
                    <h3 class="approach-heading"><?php echo esc_html($heading); ?></h3>
                    <p class="approach-text"><?php echo wp_kses_post($text); ?></p>
                    <div class="approach-number"><?php echo esc_html($number); ?></div>
                    <?php if ($icon_url) : ?>
                        <div class="approach-icon-wrapper">
                            <img src="<?php echo esc_url($icon_url); ?>" alt="<?php echo esc_attr($heading); ?> icon" class="approach-icon">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Mobile Navigation Arrows -->
    <div class="approach-nav">
        <button class="approach-prev" aria-label="Previous approach">
            <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 15.5L0.5 8M0.5 8L8 0.5M0.5 8H18.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <button class="approach-next" aria-label="Next approach">
            <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</section>

