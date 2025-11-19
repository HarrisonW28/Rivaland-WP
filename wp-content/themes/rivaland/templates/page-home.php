<?php
/**
 * Template Name: Home
 * 
 * Homepage template
 * 
 * @package Rivaland
 */

get_header();
?>

<?php
// Debug ACF fields (remove after testing)
if (current_user_can('administrator') && isset($_GET['debug_acf'])) {
    get_template_part('template-parts/debug-acf');
}
?>

<main>
    <?php
    // Intro Section
    get_template_part('template-parts/intro');
    
    // Accordion Section
    get_template_part('template-parts/accordion');
    
    // Feature Section
    get_template_part('template-parts/feature');
    
    // Projects Section
    get_template_part('template-parts/projects');
    
    // News Section
    get_template_part('template-parts/news');
    ?>
</main>

<?php
get_footer();

