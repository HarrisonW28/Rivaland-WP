<?php get_header(); ?>

<section class="site-page">
  <article <?php post_class(); ?>>
    <div class="post-hero site-container pt-64">
      <p><a href="<?php echo get_permalink(get_option('page_for_posts')); ?>">Back</a></p>

      <h1 class="post-title"><?php the_title(); ?></h1>

      <div class="info">
        <span class="post-date">
            Published on: <?php echo get_the_date(); ?>
        </span>

        <span class="post-author flex items-center mt-16">
          <?php
          $author_id = get_the_author_meta( 'ID' );
          echo get_avatar($author_id, 32);
          ?>
          <span class="author-name ml-8 block">By <span><?php echo get_the_author_meta( 'display_name', $author_id ); ?></span></span>
        </span>


        <div class="categories text-12 my-16 flex flex-row flex-wrap items-center -m-8">
          <?php
          $terms = get_the_category();

          if (!empty($terms) && !is_wp_error($terms)) :
              foreach ($terms as $term) : ?>
                  <span class="badge font-medium px-8 py-2 text-white leading-[22px] bg-gray-700 m-8"><?php echo $term->name; ?></span>
              <?php endforeach;
          endif; ?>
        </div>
      </div>

      <div class="post-excerpt mb-16">
          <?php the_excerpt(); ?>
      </div>

      <?php include THEME_DIRECTORY . '/includes/partials/post-share.php'; ?>
    </div>
    
    <?php 
      if (have_posts()) : while (have_posts()) : the_post();
        the_content(); 
      endwhile; endif;
    ?>
  </article>
</section>

<?php get_footer(); ?>