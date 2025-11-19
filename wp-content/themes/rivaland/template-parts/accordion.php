<?php
/**
 * Accordion Template Part
 * 
 * @package Rivaland
 */

$accordion_section = get_field('accordion_section');
$accordion_type = $accordion_section['accordion_type'] ?? 'services';
$eyebrow = $accordion_section['eyebrow'] ?? '';
$button_text = $accordion_section['button_text'] ?? 'What we offer';
$button_url = $accordion_section['button_url'] ?? get_permalink(get_page_by_path('services')) ?: '#';
$items = $accordion_section['items'] ?? [];

// Default items if empty
if (empty($items)) {
    $items = [
        [
            'title' => 'Consultancy',
            'content' => 'Riva Land is a privately owned real estate development company based in London. We specialise in the acquisition, planning, and development of residential and mixed-use projects across the UK.',
            'link_text' => 'Discover more',
            'link_url' => '#',
        ],
        [
            'title' => 'Acquisition',
            'content' => 'Our acquisition services help you identify and secure the right land opportunities for your development projects.',
            'link_text' => 'Discover more',
            'link_url' => '#',
        ],
        [
            'title' => 'Planning',
            'content' => 'We navigate the complex planning system to secure planning consent and maximize land value for your projects.',
            'link_text' => 'Discover more',
            'link_url' => '#',
        ],
    ];
}
?>

<section class="accordion accordion--<?php echo esc_attr($accordion_type); ?>">
    <div class="accordion--services__left">
        <div class="accordion--services__card">
            <?php if ($eyebrow) : ?>
                <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <?php endif; ?>
            <?php if ($button_text && $button_url) : ?>
                <a class="button button--primary" href="<?php echo esc_url($button_url); ?>">
                    <?php echo esc_html($button_text); ?>
                    <span class="button__icon">
                        <svg class="icon icon-arrow" width="19" height="16" viewBox="0 0 19 16" fill="none">
                            <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="accordion--services__right">
        <div class="accordion">
            <?php foreach ($items as $index => $item) : 
                $is_active = $index === 0 ? 'active' : '';
                $title = $item['title'] ?? '';
                $content = $item['content'] ?? '';
                $link_text = $item['link_text'] ?? 'Discover more';
                $link_url = $item['link_url'] ?? '#';
            ?>
                <article class="accordion__item <?php echo esc_attr($is_active); ?>">
                    <div class="accordion__header">
                        <p><?php echo esc_html($title); ?></p>
                        <button class="accordion__toggle" aria-label="<?php echo $is_active ? 'collapse' : 'expand'; ?> <?php echo esc_attr(strtolower($title)); ?>">
                            <?php echo $is_active ? '×' : '+'; ?>
                        </button>
                    </div>
                    <div class="accordion__content">
                        <?php 
                        // Handle WYSIWYG content - strip HTML tags if needed, or output as-is
                        $content_output = $content;
                        if (is_string($content_output)) {
                            // If content has HTML, use wp_kses_post, otherwise just output
                            if (strip_tags($content_output) !== $content_output) {
                                echo wp_kses_post($content_output);
                            } else {
                                echo '<p>' . esc_html($content_output) . '</p>';
                            }
                        } else {
                            echo '<p>' . esc_html($content_output) . '</p>';
                        }
                        ?>
                        <?php if ($link_text && $link_url) : ?>
                            <a href="<?php echo esc_url($link_url); ?>" class="accordion--services__button">
                                <?php echo esc_html($link_text); ?>
                                <span class="button__icon">
                                    <svg class="icon icon-arrow" width="19" height="16" viewBox="0 0 19 16" fill="none">
                                        <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </a>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>

