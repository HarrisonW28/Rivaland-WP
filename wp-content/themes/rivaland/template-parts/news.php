<?php
/**
 * News Template Part
 * 
 * @package Rivaland
 */

$news_section = get_field('news_section');
$news_items = $news_section['news_items'] ?? [];

// If no news items in ACF, get recent posts
if (empty($news_items)) {
    $news_query = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 2,
        'post_status' => 'publish',
    ]);
    
    if ($news_query->have_posts()) {
        while ($news_query->have_posts()) {
            $news_query->the_post();
            $news_items[] = [
                'title' => get_the_title(),
                'eyebrow' => get_field('news_eyebrow') ?? 'Project news',
                'image' => get_the_post_thumbnail_url() ?: get_template_directory_uri() . '/assets/images/feature.jpg',
                'url' => get_permalink(),
            ];
        }
        wp_reset_postdata();
    }
    
    // If still empty, use default news items
    if (empty($news_items)) {
        $news_items = [
            [
                'title' => 'Planning land for future generations',
                'eyebrow' => 'Project news',
                'image' => get_template_directory_uri() . '/assets/images/feature.jpg',
                'url' => '#',
            ],
            [
                'title' => 'Planning land for future generations',
                'eyebrow' => 'Project news',
                'image' => get_template_directory_uri() . '/assets/images/feature.jpg',
                'url' => '#',
            ],
        ];
    }
}
?>

<section class="news">
    <?php foreach ($news_items as $item) : 
        $title = $item['title'] ?? '';
        $eyebrow = $item['eyebrow'] ?? 'Project news';
        $image = $item['image'] ?? get_template_directory_uri() . '/assets/images/feature.jpg';
        $url = $item['url'] ?? '#';
    ?>
        <article class="news-card">
            <a href="<?php echo esc_url($url); ?>">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                <div class="news-content">
                    <p class="news-eyebrow"><?php echo esc_html($eyebrow); ?></p>
                    <p class="news-title"><?php echo esc_html($title); ?></p>
                    <svg class="news-arrow" width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </a>
        </article>
    <?php endforeach; ?>
</section>

