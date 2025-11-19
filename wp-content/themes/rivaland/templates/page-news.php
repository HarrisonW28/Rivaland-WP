<?php
/**
 * Template Name: News
 * 
 * News page template
 * 
 * @package Rivaland
 */

get_header();
?>

<main>
    <?php
    $news_query = new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    ?>
    
    <section class="news-index">
        <?php
        if ($news_query->have_posts()) {
            while ($news_query->have_posts()) {
                $news_query->the_post();
                get_template_part('template-parts/news', 'card');
            }
            wp_reset_postdata();
        } else {
            echo '<p>No news articles found.</p>';
        }
        ?>
    </section>
</main>

<?php
get_footer();

