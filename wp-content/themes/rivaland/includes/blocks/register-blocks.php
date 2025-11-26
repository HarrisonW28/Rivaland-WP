<?php
    // Store block script handles for frontend enqueuing
    $GLOBALS['acf_block_scripts'] = [];

    function registerBlockTypes() {
    $block_directory = get_template_directory() . '/includes/blocks';
    $block_url = get_template_directory_uri() . '/includes/blocks';
    $directory = new DirectoryIterator($block_directory);

    foreach ($directory as $fileinfo) {
        if ($fileinfo->isDir() && !$fileinfo->isDot()) {
            $block_name = $fileinfo->getFilename();
            $block_json_file = "$block_directory/$block_name/block.json";
            $block_js_file = "$block_directory/$block_name/dist/block.js";

            if (file_exists($block_json_file)) {
                // Read the block.json file
                $block_json_data = json_decode(file_get_contents($block_json_file), true);

                // Extract supports from block.json
                $supports = isset($block_json_data['supports']) ? $block_json_data['supports'] : [];

                // Ensure 'ghostkit' key exists with specific structure
                if (!isset($supports['ghostkit']) || !is_array($supports['ghostkit'])) {
                    $supports['ghostkit'] = [];
                }

                // Add 'spacing' => true to 'ghostkit'
                $supports['ghostkit']['spacings'] = true;

                // Register script for the block if it exists
                if (file_exists($block_js_file)) {
                    $script_handle = "$block_name-scripts";
                    $script_version = filemtime($block_js_file);
                    
                    wp_register_script(
                        $script_handle,
                        "$block_url/$block_name/dist/block.js",
                        array('jquery'),
                        $script_version,
                        true
                    );

                    // Store the script handle for this block
                    $GLOBALS['acf_block_scripts']["acf/$block_name"] = $script_handle;
                }

                // Register the block with dynamic supports
                register_block_type($block_json_file, [
                    'supports' => $supports,
                ]);
            }
        }
    }
}

add_action('init', 'registerBlockTypes');

/**
 * Enqueue block scripts on the frontend when blocks are rendered
 */
function enqueue_acf_block_scripts($block_content, $block) {
    // Only enqueue on frontend, not in editor or REST API
    if (is_admin() || wp_is_json_request() || (defined('REST_REQUEST') && REST_REQUEST)) {
        return $block_content;
    }

    // Initialize global if not set
    if (!isset($GLOBALS['acf_block_scripts'])) {
        $GLOBALS['acf_block_scripts'] = [];
    }

    // Check if this is an ACF block and if we have a script for it
    if (isset($block['blockName']) && 
        strpos($block['blockName'], 'acf/') === 0 && 
        isset($GLOBALS['acf_block_scripts'][$block['blockName']])) {
        
        $script_handle = $GLOBALS['acf_block_scripts'][$block['blockName']];
        
        // Enqueue script if not already enqueued or done
        if (!wp_script_is($script_handle, 'enqueued') && !wp_script_is($script_handle, 'done')) {
            wp_enqueue_script($script_handle);
        }
    }

    return $block_content;
}
add_filter('render_block', 'enqueue_acf_block_scripts', 10, 2);


    /**
     * Load global Tailwind classes and container styles etc
     */
    function mytheme_enqueue_block_editor_assets() {
        wp_enqueue_style(
            'mytheme-editor-styles',
            get_stylesheet_directory_uri() . '/dist/editor-styles.min.css',
            [],
            '1.0',
            'all'
        );
    }
    add_action( 'enqueue_block_editor_assets', 'mytheme_enqueue_block_editor_assets' );


    /**
     * Restrict block editor to only blocks we have created,
     * if you want to add additional core blocks into
     * this array, you can find a list of all of the 
     * core blocks on this URL:
     * https://developer.wordpress.org/block-editor/reference-guides/core-blocks/
     * 
     * If the blocks you want to show are a part of a plugin,
     * you will have to refer to that plugin's documentation
     */
    function edit_allowed_block_list($block_editor_context, $editor_context) {
        $block_directory = get_template_directory() . '/includes/blocks';
        $directory = new DirectoryIterator($block_directory);
        $blocks = [];

        foreach($directory as $fileinfo) {
            if ($fileinfo->isDir() && !$fileinfo->isDot()) {
                $block_name = $fileinfo->getFilename();
                $blocks[] = "acf/$block_name";
            }
        }
        return $blocks;
    }
    add_filter('allowed_block_types_all', 'edit_allowed_block_list', 10, 2);