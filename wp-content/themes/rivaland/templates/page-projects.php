<?php
/**
 * Template Name: Projects
 * 
 * Projects page template
 * 
 * @package Rivaland
 */

get_header();
?>

<main>
    <?php
    // Get ACF fields
    $projects_page_section = get_field('projects_page_section');
    $title = $projects_page_section['title'] ?? 'Projects';
    $description = $projects_page_section['description'] ?? 'A brief overview of some of the projects we are currently involved in promoting and some of our recent success stories.';
    $filter_placeholder = $projects_page_section['filter_placeholder'] ?? 'Filter results';
    $filter_options = $projects_page_section['filter_options'] ?? [];
    
    // Default filter options if empty
    if (empty($filter_options)) {
        $filter_options = [
            ['label' => 'Residential', 'value' => 'residential'],
            ['label' => 'Commercial', 'value' => 'commercial'],
            ['label' => 'Mixed Use', 'value' => 'mixed'],
        ];
    }
    
    // Get project categories/taxonomies
    $categories = get_terms([
        'taxonomy' => 'project_category',
        'hide_empty' => false,
    ]);
    
    // Projects Index Section
    $projects_query = new WP_Query([
        'post_type' => 'project',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    ?>
    
    <section class="projects-index">
        <div class="projects-index-left">
            <h2><?php echo esc_html($title); ?></h2>
            <p class="projects-index-description">
                <?php echo esc_html($description); ?>
            </p>
            <div class="projects-filters">
                <div class="projects-index__filter-input-wrapper">
                    <input type="text" class="projects-index__filter-input" placeholder="<?php echo esc_attr($filter_placeholder); ?>" id="project-filter-input">
                    <button class="projects-index__filter-clear" aria-label="Clear filter" id="project-filter-clear">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
                <div class="projects-index__filters-buttons">
                    <?php foreach ($filter_options as $option) : 
                        $label = $option['label'] ?? '';
                        $value = $option['value'] ?? '';
                    ?>
                        <button class="projects-index__filter" data-filter="<?php echo esc_attr($value); ?>">
                            <?php echo esc_html($label); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <div class="projects-index__filter-dropdown-wrapper">
                    <button class="projects-index__filter-dropdown-toggle" id="project-filter-dropdown-toggle" aria-label="Filter projects">
                        <span class="filter-dropdown-text">Filter</span>
                        <svg class="filter-dropdown-arrow" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6L8 10L12 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="projects-index__filter-clear-mobile" id="project-filter-clear-mobile" aria-label="Clear filters" title="Clear filters">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <div class="projects-index__filter-dropdown" id="project-filter-dropdown">
                        <?php foreach ($filter_options as $option) : 
                            $label = $option['label'] ?? '';
                            $value = $option['value'] ?? '';
                        ?>
                            <button class="projects-index__filter-dropdown-item" data-filter="<?php echo esc_attr($value); ?>">
                                <?php echo esc_html($label); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="projects-accent-line"></div>
        </div>
        <div class="projects-index-right">
            <div class="projects-index__list">
                <?php
                if ($projects_query->have_posts()) {
                    while ($projects_query->have_posts()) {
                        $projects_query->the_post();
                        get_template_part('template-parts/project-card');
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p>No projects found.</p>';
                }
                ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();

