<?php
    function registerBlockTypes() {
    $block_directory = get_template_directory() . '/includes/blocks';
    $block_url = get_template_directory_uri() . '/includes/blocks';
    $directory = new DirectoryIterator($block_directory);

    foreach ($directory as $fileinfo) {
        if ($fileinfo->isDir() && !$fileinfo->isDot()) {
            $block_name = $fileinfo->getFilename();
            $block_json_file = "$block_directory/$block_name/block.json";

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


                // Register script for the block
                wp_register_script(
                    "$block_name-scripts",
                    "$block_url/$block_name/dist/block.js",
                    [],
                    filemtime("$block_directory/$block_name/dist/block.js"),
                    true
                );

                // Register the block with dynamic supports
                register_block_type($block_json_file, [
                    'supports' => $supports,
                    'script' => "$block_name-scripts",
                ]);
            }
        }
    }
}

add_action('init', 'registerBlockTypes');


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