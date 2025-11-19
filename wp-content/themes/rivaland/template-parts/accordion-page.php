<?php
/**
 * Accordion Page Template Part (Full-width accordion)
 * 
 * @package Rivaland
 */

$accordion_section = get_field('accordion_section');
$accordion_type = $accordion_section['accordion_type'] ?? 'page';
$items = $accordion_section['items'] ?? [];

// Default items if empty
if (empty($items)) {
    $items = [
        [
            'title' => 'Consultancy',
            'preview_text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam semper interdum lacinia. Vestibulum pretium ante ut ligula fermentum rhoncus eget id neque. Pellentesque sit amet est a lectus convallis maximus in et felis. Suspendisse potenti. Donec id erat eget nulla dapibus bibendum. Cras feugiat condimentum enim dignissim varius. Integer tincidunt condimentum odio et tristique.',
            'content' => 'Integer enim nisi, semper vel nulla eu, egestas suscipit nisi. Donec iaculis interdum dignissim. Vestibulum id interdum elit. Integer dapibus iaculis nulla, ac maximus ante mollis nec. Maecenas sagittis neque sed accumsan ultrices. Sed fermentum felis sit amet interdum viverra. Cras porta commodo volutpat. Nam et molestie massa, quis bibendum neque. Nam iaculis nisi in sem posuere, vel egestas mauris facilisis. Donec metus mi, tempus vitae libero vel, lacinia condimentum justo.',
            'image' => '',
            'button_text' => 'Enquire about Consultancy',
            'button_url' => '#',
        ],
    ];
}

if (empty($items)) {
    return;
}
?>

<section class="accordion accordion--page accordion--dark">
    <div class="accordion--page__left">
        <!-- Empty left column - aligns with intro text padding -->
    </div>
    <div class="accordion--page__right">
        <div class="accordion">
            <?php foreach ($items as $index => $item) : 
                $is_active = $index === 0 ? 'active' : '';
                $title = $item['title'] ?? '';
                $preview_text = $item['preview_text'] ?? '';
                $content = $item['content'] ?? '';
                $image = $item['image'] ?? '';
                $button_text = $item['button_text'] ?? '';
                $button_url = $item['button_url'] ?? '#';
                
                // Handle image
                if (is_array($image)) {
                    $image_url = $image['url'] ?? '';
                    $image_alt = $image['alt'] ?? '';
                } else {
                    $image_url = $image;
                    $image_alt = '';
                }
            ?>
                <article class="accordion__item <?php echo esc_attr($is_active); ?>">
                    <div class="accordion__header">
                        <p><?php echo esc_html($title); ?></p>
                        <button class="accordion__toggle" aria-label="<?php echo $is_active ? 'collapse' : 'expand'; ?> <?php echo esc_attr(strtolower($title)); ?>">
                            <?php echo $is_active ? '×' : '+'; ?>
                        </button>
                    </div>
                    <div class="accordion__content">
                        <div class="accordion--page__content-wrapper">
                            <div class="accordion--page__content-text">
                                <p class="service-text-preview"><?php echo wp_kses_post($preview_text); ?></p>
                                <div class="service-text-full">
                                    <?php echo wp_kses_post($content); ?>
                                </div>
                                <a href="#" class="service-read-more" aria-label="read more">
                                    <span class="read-more-text">Read more</span>
                                    <span class="read-more-icon">+</span>
                                </a>
                                <?php if ($button_text && $button_url) : ?>
                                    <a href="<?php echo esc_url($button_url); ?>" class="accordion--page__button accordion--page__button-mobile">
                                        <?php echo esc_html($button_text); ?>
                                        <span class="button__icon">
                                            <svg class="icon icon-arrow" width="19" height="16" viewBox="0 0 19 16" fill="none">
                                                <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php if ($image_url) : ?>
                                <div class="accordion--page__content-image">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt ?: $title); ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

