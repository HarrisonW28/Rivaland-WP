<?php
/**
 * Template Name: Services
 * 
 * Services page template
 * 
 * @package Rivaland
 */

get_header();
?>

<main>
    <?php
    // Intro Section
    get_template_part('template-parts/intro');
    
    // Services Accordion Section (full page)
    $accordion_section = get_field('accordion_section');
    if ($accordion_section || true) { // Always show, has defaults
        get_template_part('template-parts/accordion-page');
    }
    ?>
</main>

<?php
get_footer();

