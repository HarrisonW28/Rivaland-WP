<?php
// Remove actions.
remove_action('wp_head', 'print_emoji_detection_script', 7);

// Add actions.
add_action('wp_footer', 'print_emoji_detection_script', 7);
add_action('wp_enqueue_scripts', 'script_enqueues');
add_action('acf/init', 'acf_add_maps_api_key');
// add_action('acf/init', 'acf_register_blocks');
add_action('admin_head', 'editor_full_width_gutenberg');

/**
 * admin AJAX function example
 * add_action('wp_ajax_example_admin_ajax', 'example_admin_ajax');
 * add_action('wp_ajax_nopriv_example_admin_ajax', 'example_admin_ajax');
 */


 //disable plugin access for non admin users
 function restrict_plugin_access_for_non_admins() {
    if (!current_user_can('administrator')) {
        // Remove plugin menu page for non-admin users
        remove_menu_page('plugins.php');

        // Prevent direct access to plugin admin pages
        if (isset($_GET['page']) && strpos($_GET['page'], 'plugins') !== false) {
            wp_redirect(admin_url());
            exit;
        }
    }
}
add_action('admin_menu', 'restrict_plugin_access_for_non_admins', 100);
