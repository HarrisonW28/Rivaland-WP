<?php
/**
 * Testimonials Template Part
 * 
 * @package Rivaland
 */

$testimonials_section = get_field('testimonials_section');
$items = $testimonials_section['items'] ?? [];

// Default items if empty
if (empty($items)) {
    $items = [
        [
            'quote' => 'Unlike a lot of so called Land Promoters these guys really know their sh*t.',
            'author' => 'Mr A Customer',
            'company' => 'Wellknown Developers',
        ],
        [
            'quote' => 'Rivaland delivered exceptional results on our development project. Their expertise and professionalism made all the difference.',
            'author' => 'Jane Smith',
            'company' => 'Property Developers Ltd',
        ],
    ];
}

if (empty($items)) {
    return;
}
?>

<section class="testimonial-section">
    <div class="testimonial-left">
        <div class="testimonial-nav">
            <button class="prev" aria-label="Previous testimonial">
                <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 15.5L0.5 8M0.5 8L8 0.5M0.5 8H18.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <button class="next" aria-label="Next testimonial">
                <svg width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
    <div class="testimonial-divider"></div>
    <div class="testimonial-right">
        <?php foreach ($items as $index => $item) : 
            $quote = $item['quote'] ?? '';
            $author = $item['author'] ?? '';
            $company = $item['company'] ?? '';
            $is_active = $index === 0 ? 'active' : '';
        ?>
            <div class="testimonial-item <?php echo esc_attr($is_active); ?>">
                <blockquote><?php echo esc_html($quote); ?></blockquote>
                <p class="testimonial-author">
                    <?php echo esc_html($author); ?>
                    <?php if ($company) : ?>
                        / <span class="testimonial-company"><?php echo esc_html($company); ?></span>
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
    </div>
</section>

