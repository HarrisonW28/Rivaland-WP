<?php get_header(); ?>

<?php 
  $post_filters = get_field('post_filters', 'option');
?>

<section class="site-page py-96 relative z-10">
  <div class="site-container" id="main">
    <h1 class="mb-90">News</h1>
    
    <?php if($post_filters): ?>
      <div class="filter-container mb-48 xl:mb-64 max-w-[720px] mx-auto" data-lenis-prevent>
        <?php echo do_shortcode($post_filters); ?>
      </div>
    <?php endif; ?>

    <?php if (have_posts()) : ?>
      <ul class="post-list grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-y-32 sm:gap-y-48 xl:gap-y-64 gap-x-24 delay-4">
        <?php while (have_posts()) : the_post(); 
          $post_id = get_the_ID();
          
          get_template_part('template-parts/cards/news', 'card', [
            'post_id' => $post_id,
          ]);

        endwhile; ?>
      </ul>

      <?php if($wp_query->max_num_pages > 1): ?>
        <div class="pagination">

          <?php
            $pagination_args = array(
              'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
              'current'      => max(1, get_query_var('paged')),
              'format'       => '?paged=%#%',
              'show_all'     => false,
              'end_size'     => 3,
              'mid_size'     => 3,
              'prev_next'    => true,
              'prev_text'    => __('<svg xmlns="http://www.w3.org/2000/svg" width="21" height="22" viewBox="0 0 21 22" fill="none">
      <path d="M16.625 11.4812H4.375M4.375 11.4812L10.5 17.6062M4.375 11.4812L10.5 5.3562" stroke="#344054" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg> <span>Previous</span>'),
              'next_text'    => __('<span>Next</span> <svg xmlns="http://www.w3.org/2000/svg" width="21" height="22" viewBox="0 0 21 22" fill="none">
      <path d="M4.375 11.4812H16.625M16.625 11.4812L10.5 5.3562M16.625 11.4812L10.5 17.6062" stroke="#344054" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>'),
            );

            echo paginate_links($pagination_args);

          ?>
        </div>
      <?php endif; ?>

    <?php else : ?>
      <article>
        <h2 class="text-purple animate-title">Sorry, there's nothing here yet!</h2>
        <p class="mt-12">Please check again at a later date.</p>
      </article>
    <?php endif; ?>
  </div>
</section>

<?php get_footer(); ?>
