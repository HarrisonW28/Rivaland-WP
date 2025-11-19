
      <?php 
        $footer_logo = get_field('site_logo', 'option');
        $slogan = get_field('slogan', 'option');
        $social_media = get_field('social_media', 'option');
        $newsletter_text = get_field('newsletter_text', 'option');
        $newsletter_form_id = get_field('newsletter_form_id', 'option');
        $copyright_text = get_field('copyright_text', 'option');
        $designed_by = get_field('designed_by', 'option');

        $form = '[gravityform id="' . $newsletter_form_id . '" ajax="true" title="false" description="false"]';
      ?>
      
      <footer class="site-footer bg-gray-100">
        <div class="site-container">
          <div class="main-content flex flex-col md:flex-row flex-wrap lg:flex-nowrap justify-between py-40 md:py-64 md:gap-32">
            <div class="left-column max-w-[300px] mb-40 md:mb-0">
              <a class="site-logo" href="<?php echo home_url(); ?>">
                <?php if($footer_logo): ?>
                  <img class="" src="<?php echo $footer_logo['url']; ?>" alt="">
                <?php endif; ?>
              </a>

              <p class="text-sm !text-14 my-16"><?php echo $slogan; ?></p>

              <?php if($social_media): ?>
                <div class="right-container flex social-icons">
                  <?php foreach ($social_media as $row): ?>
                    <?php
                    $image_url = $row['icon']['url'];
                    
                    if($row['url'] && $image_url):
                    ?>

                      <a class="mr-16 last:mr-0" href="<?php echo esc_url($row['url']); ?>" target="_blank">
                      <div class="mask-icon w-24 h-24 bg-current" style="mask-image: url(<?php echo esc_url($image_url); ?>); -webkit-mask-image: url(<?php echo esc_url($image_url); ?>);"></div>
                      </a>

                    <?php endif; endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <div class="footer-column-1 mb-40 md:mb-0">
              <span class=" mb-16 block">Menu 1</span>
              <nav class="site-footer__navigation site-footer__navigation--col-1 mt-24">
                <?php
                  echo wp_nav_menu([
                    'theme_location' => 'footer_col_1',
                    'menu_class' => 'site-footer__menu grid text-sm gap-16',
                    'container' => false
                  ]);
                ?>
              </nav>
            </div>

            <div class="footer-column-2 mb-40 md:mb-0">
              <span class=" mb-16 block">Menu 1</span>
              <nav class="site-footer__navigation site-footer__navigation--col-2 mt-24">
                <?php
                  echo wp_nav_menu([
                    'theme_location' => 'footer_col_2',
                    'menu_class' => 'site-footer__menu grid text-sm gap-16',
                    'container' => false
                  ]);
                ?>
              </nav>
            </div>

            <div class="newsletter-container sm:max-w-[300px]">
              <span class="!text-16 mb-16">Newsletter</span>

              <p class="text-14 mt-16 mb-24"><?php echo $newsletter_text; ?></p>

              <div class="form-container">
                <?php echo do_shortcode($form); ?>
              </div>
            </div>
          </div>
        </div>

        <div class="bottom-container flex flex-row text-12">
          <div class="site-container py-24 flex flex-col sm:flex-row items-center justify-between">
            <span class="">@ <?php echo date('Y') . ' ' . $copyright_text; ?></span>

            <nav class="site-footer__legal-wrapper">
              <?php
                echo wp_nav_menu([
                  'theme_location' => 'footer_col_1',
                  'menu_class' => 'site-footer__legal-menu flex flex-row text-sm gap-16',
                  'container' => false
                ]);
              ?>
            </nav>

            <span><?php echo $designed_by; ?></span>
          </div>
        </div>
      </footer>
    </article>

    <?php wp_footer(); ?>
  </body>
</html>