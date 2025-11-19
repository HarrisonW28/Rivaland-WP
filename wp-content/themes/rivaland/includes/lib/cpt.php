<?php
/**
 * Custom Post Types & Taxonomies
 * 
 * @package Rivaland
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Project Post Type
 */
function rivaland_register_project_post_type() {
    $labels = [
        'name' => 'Projects',
        'singular_name' => 'Project',
        'menu_name' => 'Projects',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Project',
        'edit_item' => 'Edit Project',
        'new_item' => 'New Project',
        'view_item' => 'View Project',
        'search_items' => 'Search Projects',
        'not_found' => 'No projects found',
        'not_found_in_trash' => 'No projects found in Trash',
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'projects'],
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 20,
        'menu_icon' => 'dashicons-portfolio',
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest' => true,
    ];

    register_post_type('project', $args);
}
add_action('init', 'rivaland_register_project_post_type');

/**
 * Register Project Category Taxonomy
 */
function rivaland_register_project_taxonomy() {
    $labels = [
        'name' => 'Project Categories',
        'singular_name' => 'Project Category',
        'search_items' => 'Search Categories',
        'all_items' => 'All Categories',
        'parent_item' => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item' => 'Edit Category',
        'update_item' => 'Update Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => 'New Category Name',
        'menu_name' => 'Categories',
    ];

    $args = [
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'project-category'],
        'show_in_rest' => true,
    ];

    register_taxonomy('project_category', ['project'], $args);
}
add_action('init', 'rivaland_register_project_taxonomy');