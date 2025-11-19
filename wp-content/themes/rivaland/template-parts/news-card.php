<?php
/**
 * News Card Template Part
 * 
 * @package Rivaland
 */

$eyebrow = get_field('news_eyebrow') ?? 'Project news';
$image = get_the_post_thumbnail_url() ?: get_template_directory_uri() . '/assets/images/feature.jpg';
?>

<article class="news-card">
    <a href="<?php the_permalink(); ?>">
        <img src="<?php echo esc_url($image); ?>" alt="<?php the_title_attribute(); ?>">
        <div class="news-content">
            <p class="news-eyebrow"><?php echo esc_html($eyebrow); ?></p>
            <p class="news-title"><?php the_title(); ?></p>
            <svg class="news-arrow" width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
    </a>
</article>

