<?php
class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {

    private $submenu_item_count = 0; // Keep track of sub-menu item count
    private $parent_title = ''; // Store the parent menu item title

    // Start the level of the menu (ul).
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $classes = array('sub-menu');

        // Reset submenu item count for each new submenu
        $this->submenu_item_count = 0;

        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "\n$indent<ul$class_names>\n";
    }

   // Start each menu element (li).
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        if (!empty($args->walker->has_children)) {
            $classes[] = 'menu-item-has-children'; // Add class for menu items with children
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

        // Store the parent title for the submenu
        if ($depth === 0 && !empty($args->walker->has_children)) {
            $this->parent_title = $title; // Store the parent title
        }

        // Increment submenu item count if in a submenu
        if ($depth > 0) {
            $this->submenu_item_count++;
        }
    }

    // End the level of the menu (ul).
    function end_lvl(&$output, $depth = 0, $args = null) {
        if ($depth === 0 && $this->submenu_item_count > 6) {
            // If it's the first depth and there are more than 6 items, add the two-col-grid class
            $output = preg_replace('/<ul class="sub-menu">/', '<ul class="sub-menu two-col-grid">', $output, 1);
        }
        parent::end_lvl($output, $depth, $args); // Call the parent method
    }
}

class Mobile_Walker_Nav_Menu extends Walker_Nav_Menu {

    private $parent_title = ''; // Store the parent menu item title

    // Start the level of the menu (ul).
    function start_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);

        // Only add the .sub-page wrapper for sub-menus (depth > 0)
        if ($depth > -1) {
            $output .= "\n$indent<div class=\"sub-page\">\n";
            
            // Add the back button
            $output .= '<div class="back">';
            $output .= '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="22" viewBox="0 0 21 22" fill="none">
                <path d="M16.625 11H4.375M4.375 11L10.5 17.125M4.375 11L10.5 4.875" stroke="#101828" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>';
            $output .= '<span>Back</span>';
            $output .= '</div>';
        }

        // Sub-menu wrapper with default 'sub-menu' class
        $classes = array('sub-menu');
        $class_names = join(' ', apply_filters('nav_menu_submenu_css_class', $classes, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $output .= "\n$indent<ul$class_names>\n";
    }

    // End the level of the menu (ul).
    function end_lvl(&$output, $depth = 0, $args = null) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
        
        // Close the .sub-page wrapper if it's a sub-menu
        if ($depth > 0) {
            $output .= "$indent</div>\n";
        }
    }

    // Start each menu element (li).
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $has_children = !empty($args->walker->has_children);
        if ($has_children) {
            $classes[] = 'menu-item-has-children';
        }

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $output .= $indent . '<li' . $id . $class_names . '>';

        // Add main-menu-item wrapper for items with children
        if ($has_children) {
            $output .= '<div class="main-menu-item">';
        }

        $atts = array();
        $atts['title']  = !empty($item->attr_title) ? $item->attr_title : '';
        $atts['target'] = !empty($item->target) ? $item->target : '';
        $atts['rel']    = !empty($item->xfn) ? $item->xfn : '';
        $atts['href']   = !empty($item->url) ? $item->url : '';

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $title = apply_filters('the_title', $item->title, $item->ID);
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';

        // Add button for items with children
        if ($has_children) {
            $item_output .= '<button class="submenu-toggle" aria-label="Toggle submenu">';
            $item_output .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>';
            $item_output .= '</button>';
            $item_output .= '</div>'; // Close main-menu-item
        }

        $item_output .= $args->after;

        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);

        // Store the parent title for the submenu
        if ($depth === 0 && $has_children) {
            $this->parent_title = $title;
        }
    }
}
