<?php
/**
 * Project Card Template Part
 * 
 * @package Rivaland
 */

$meta = get_field('project_meta') ?? '';
$location = get_field('project_location') ?? '';
$status = get_field('project_status') ?? '';
$image = get_field('project_image') ?? get_the_post_thumbnail_url() ?? '';
$category_terms = get_the_terms(get_the_ID(), 'project_category');
$category_slug = '';
if ($category_terms && !is_wp_error($category_terms)) {
    $category_slug = $category_terms[0]->slug ?? '';
}

// Handle image - could be array (ACF image) or string (URL)
if (is_array($image)) {
    $image_url = $image['url'] ?? '';
    $image_alt = $image['alt'] ?? get_the_title();
} else {
    $image_url = $image;
    $image_alt = get_the_title();
}

// If no image, use default
if (empty($image_url)) {
    $image_url = get_template_directory_uri() . '/assets/images/project-map-1.jpg';
}

// Get meta from ACF or try to get from category
if (empty($meta) && $category_terms && !is_wp_error($category_terms)) {
    $meta = $category_terms[0]->name ?? '';
}
?>

<article class="project-card project-card--index" data-category="<?php echo esc_attr($category_slug); ?>">
    <?php if ($image_url) : ?>
        <div class="project-card-image">
            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
        </div>
    <?php endif; ?>
    <div class="project-card-content">
        <div class="project-card__info">
            <p class="project-card__meta">
                <?php echo esc_html($meta); ?>
                <?php if ($status) : ?>
                    / <span class="project-status"><?php echo esc_html($status); ?></span>
                <?php endif; ?>
            </p>
            <p class="project-card__location"><?php echo wp_kses_post(nl2br($location)); ?></p>
        </div>
        <svg class="project-card__arrow" width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>
</article>

