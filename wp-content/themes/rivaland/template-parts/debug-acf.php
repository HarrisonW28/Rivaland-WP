<?php
/**
 * Debug ACF Fields Template Part
 * 
 * Add this to your page template temporarily to see what ACF data is available
 * 
 * @package Rivaland
 */

if (!current_user_can('administrator')) {
    return;
}

$hero_section = get_field('hero_section');
$intro_section = get_field('intro_section');
$accordion_section = get_field('accordion_section');
$feature_section = get_field('feature_section');
$projects_section = get_field('projects_section');
$news_section = get_field('news_section');
?>

<div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 2px solid #333; font-family: monospace; font-size: 12px;">
    <h2 style="margin-top: 0;">ACF Field Debug (Admin Only)</h2>
    
    <h3>Hero Section:</h3>
    <pre><?php print_r($hero_section); ?></pre>
    
    <h3>Intro Section:</h3>
    <pre><?php print_r($intro_section); ?></pre>
    
    <h3>Accordion Section:</h3>
    <pre><?php print_r($accordion_section); ?></pre>
    
    <h3>Feature Section:</h3>
    <pre><?php print_r($feature_section); ?></pre>
    
    <h3>Projects Section:</h3>
    <pre><?php print_r($projects_section); ?></pre>
    
    <h3>News Section:</h3>
    <pre><?php print_r($news_section); ?></pre>
    
    <h3>All Fields:</h3>
    <pre><?php print_r(get_fields()); ?></pre>
</div>

