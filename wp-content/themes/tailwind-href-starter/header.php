<!DOCTYPE html>
<html <?php language_attributes(); ?>>
  <head>
    <title><?php wp_title(); ?></title>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>

    <?php wp_head(); ?>
  </head>

  <?php
    $phone_number = get_field('telephone_number', 'option');
    $email = get_field('email_address', 'option');
    $header_cta = get_field('header_cta', 'option');
    $site_logo = get_field('site_logo', 'option');
    $social_media = get_field('social_media', 'option');
  ?>

  <body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

      <header class="site-header w-screen h-auto left-0 z-[99999] sticky top-0">
        <div class="main-menu-outer-container w-full bg-gray-100">
          <div class="site-container relative z-[99999] py-20">
            <div class="flex flex-row justify-between items-center">
              <a class="site-logo" href="<?php echo home_url(); ?>">
                <?php if($site_logo): ?>
                  <img class=" h-auto" src="<?php echo $site_logo['url']; ?>" alt="">
                <?php endif; ?>
              </a>

              <div class="menu-container hidden lg:flex flex-row items-center mr-auto ml-32">
                <?php
                  echo wp_nav_menu([
                    'theme_location' => 'header',
                    'menu_class' => 'site-header__menu flex flex-row ',
                    'container' => false,
                    'walker'    => new Custom_Walker_Nav_Menu(),
                  ]);
                ?>
              </div>
              
              <?php if($header_cta):
                echo acf_link($header_cta, 'btn !hidden lg:!flex');
              endif; ?>

              <div class="hamburger lg:!hidden">
                <span></span>
                <span></span>
                <span></span>
              </div>
            </div>
          </div>
        </div>

        <div class="mobile-menu fixed top-0 w-screen" data-lenis-prevent>
          <div class="site-container h-full w-full">
            <nav class="site-header__navigation--mobile h-full flex flex-col">
              <?php
                echo wp_nav_menu([
                  'theme_location' => 'header',
                  'menu_class' => 'site-header__menu-mobile flex flex-col ',
                  'container' => false,
                  'walker'    => new Mobile_Walker_Nav_Menu(),
                ]);
              ?>
            </nav>
          </div>
        </div>
      </header>
