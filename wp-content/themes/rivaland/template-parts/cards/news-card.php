<?php 

/**
 * Controls the cards for the case study card
 */
$post_id = $args['post_id'] ?? "";

?>

<a href="<?php echo get_the_permalink($post_id); ?>" class="single-case-study cursor-pointer flex flex-col image-zoom" data-aos="fade-up">
    <div class="inner-container">
        <div class="image-container relative w-full h-auto aspect-[8/5] max-h-[432px] rounded-lg mb-16 lg:mb-24">
            <img class="absolute w-full h-full" src="<?php echo get_the_post_thumbnail_url($post_id); ?>" alt="">
        </div>

        <div class="heading-container flex flex-row">
            <span class="h5"><?php echo get_the_title($post_id); ?></span>
        </div>

        <div class="copy sm mt-12 line-clamp-3">
            <p><?php echo get_the_excerpt($post_id); ?></p>
        </div>

        <div class="categories text-12 mt-8 lg:mt-16 flex flex-row flex-wrap items-center -m-8">
            <?php
            $terms = get_the_category($post_id);

            if (!empty($terms) && !is_wp_error($terms)) :
                foreach ($terms as $term) : ?>
                    <span class="badge font-medium px-8 py-2 text-white leading-[22px] bg-gray-700 m-8"><?php echo $term->name; ?></span>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</a>
