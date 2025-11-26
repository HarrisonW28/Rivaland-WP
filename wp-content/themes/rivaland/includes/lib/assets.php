<?php
/**
 * Check if page needs specific scripts based on template or content
 */
function rivaland_needs_script($script_name) {
  // Always load these scripts
  $always_load = ['mobile-menu', 'mobile-layout', 'footer'];
  if (in_array($script_name, $always_load)) {
    return true;
  }
  
  // Check page templates using multiple methods for reliability
  $template = get_page_template_slug();
  $is_home_template = is_page_template('templates/page-home.php');
  $is_about_template = is_page_template('templates/page-about.php');
  $is_services_template = is_page_template('templates/page-services.php');
  $is_projects_template = is_page_template('templates/page-projects.php');
  
  // Check ACF fields (may not be available during enqueue, but try anyway)
  $has_accordion = function_exists('get_field') ? get_field('accordion_section') : false;
  $has_approach = function_exists('get_field') ? get_field('approach_section') : false;
  $has_projects = function_exists('get_field') ? get_field('projects_section') : false;
  $has_testimonials = function_exists('get_field') ? get_field('testimonials_section') : false;
  
  // Determine if script is needed
  switch ($script_name) {
    case 'accordion':
      return $is_home_template || $is_about_template || $is_services_template || !empty($has_accordion);
    
    case 'approach':
      return $is_about_template || !empty($has_approach);
    
    case 'projects-filter':
      return $is_home_template || $is_projects_template || !empty($has_projects);
    
    case 'testimonials':
      return $is_about_template || !empty($has_testimonials);
    
    default:
      return true; // Load by default if unsure
  }
}

function script_enqueues() {
  if (wp_script_is('jquery', 'registered')) {
    wp_deregister_script('jquery');
  }

  //Main scripts.
  wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js', array(), '3.3.1', true);
  wp_enqueue_script('main-scripts', get_template_directory_uri() . '/dist/main.min.js', array('jquery'), '1.0.0', true);
  
  // Note: All Rivaland vanilla JS scripts are injected directly in footer
  // (see rivaland_fallback_scripts) to mimic HTML version behavior
  // and avoid WordPress enqueue issues. We don't enqueue them here.

  //Stylesheets.
  wp_enqueue_style('main-styles', get_template_directory_uri() . '/dist/main.min.css', false, '1.0.0', 'all');
  
  // Google Fonts
  wp_enqueue_style(
    'google-fonts',
    'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap',
    [],
    null
  );
  /**
   * Example of how to expose endpoints/data to a script for use with admin AJAX.
   * wp_localize_script('main-scripts', 'ajaxData', array(
   *   'ajax_url' => admin_url('admin-ajax.php?action=action_name')
   * ));
   */

  /**
   * Example of how to include Google Maps on a certain template.
   * MAPS_API_KEY is defined in definitions.php.
   *
   * if (is_page_template(array('templates/with-map.php'))) {
   *   wp_enqueue_script('google-maps', '//maps.googleapis.com/maps/api/js?key=' . MAPS_API_KEY, array(), '3.0', true);
   * }
   */
}

/**
 * Inject all Rivaland vanilla JS scripts directly in footer
 * This ensures scripts load reliably - works like the HTML version
 * Bypasses WordPress enqueue system to avoid conflicts
 */
function rivaland_fallback_scripts() {
  // Only on frontend
  if (is_admin()) {
    return;
  }
  
  // All Rivaland vanilla JS scripts - inject directly like HTML version
  $rivaland_scripts = [
    'mobile-menu' => '/js/mobile-menu.js',
    'mobile-layout' => '/js/mobile-layout.js',
    'accordion' => '/js/accordion.js',
    'projects-filter' => '/js/projects-filter.js',
    'approach' => '/js/approach.js',
    'testimonials' => '/js/testimonials.js',
    'footer' => '/js/footer.js',
  ];
  
  foreach ($rivaland_scripts as $handle => $file) {
    // Check if script is needed
    if (!rivaland_needs_script($handle)) {
      continue;
    }
    
    $file_path = get_template_directory() . $file;
    if (file_exists($file_path)) {
      $script_url = get_template_directory_uri() . $file;
      $version = filemtime($file_path);
      // Inject directly - don't rely on wp_enqueue_script
      // This mimics how it worked in the HTML version
      echo '<script src="' . esc_url($script_url) . '?v=' . esc_attr($version) . '"></script>' . "\n";
    }
  }
}
add_action('wp_footer', 'rivaland_fallback_scripts', 5); // Early priority to load before other scripts

function remove_unnecessary_script() {
  wp_deregister_script('ghostkit'); // Replace 'script-handle' with the actual handle of the script you want to deregister.
}
add_action('wp_enqueue_scripts', 'remove_unnecessary_script', 100);
