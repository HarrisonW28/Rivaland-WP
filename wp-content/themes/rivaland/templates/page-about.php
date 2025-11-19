<?php
/**
 * Template Name: About
 * 
 * About page template
 * 
 * @package Rivaland
 */

get_header();
?>

<main>
    <?php
    // Intro Section
    get_template_part('template-parts/intro');
    
    // Values Accordion Section
    get_template_part('template-parts/accordion');
    
    // Team Feature Section
    $team_section = get_field('team_section');
    if ($team_section) {
        get_template_part('template-parts/feature-team');
    } else {
        // Show default team section
        get_template_part('template-parts/feature-team');
    }
    
    // Approach Section
    $approach_section = get_field('approach_section');
    if ($approach_section || true) { // Always show, has defaults
        get_template_part('template-parts/approach');
    }
    
    // Testimonials Section
    $testimonials_section = get_field('testimonials_section');
    if ($testimonials_section || true) { // Always show, has defaults
        get_template_part('template-parts/testimonials');
    }
    ?>
</main>

<?php
get_footer();

