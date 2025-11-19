<?php
/**
 * Projects Template Part
 * 
 * @package Rivaland
 */

$projects_section = get_field('projects_section');
$title = $projects_section['title'] ?? 'Projects';
$button_text = $projects_section['button_text'] ?? 'View all projects';
$button_url = $projects_section['button_url'] ?? get_permalink(get_page_by_path('projects')) ?: '#';
$projects = $projects_section['projects'] ?? [];

// Default projects if empty and no custom post type
if (empty($projects)) {
    // Try to get from custom post type first
    $projects_query = new WP_Query([
        'post_type' => 'project',
        'posts_per_page' => 3,
        'post_status' => 'publish',
    ]);
    
    if ($projects_query->have_posts()) {
        while ($projects_query->have_posts()) {
            $projects_query->the_post();
            $projects[] = [
                'title' => get_the_title(),
                'meta' => get_field('project_meta') ?? '',
                'location' => get_field('project_location') ?? '',
                'status' => get_field('project_status') ?? '',
                'url' => get_permalink(),
            ];
        }
        wp_reset_postdata();
    }
    
    // If still empty, use default projects
    if (empty($projects)) {
        $projects = [
            [
                'meta' => 'Residential',
                'location' => "Woodcote,\nSouth Oxfordshire",
                'status' => 'Application submitted',
                'url' => '#',
            ],
            [
                'meta' => 'Commercial',
                'location' => "Banbury,\nOxfordshire",
                'status' => 'Sold',
                'url' => '#',
            ],
            [
                'meta' => 'Residential',
                'location' => "Cirencester,\nGloucestershire",
                'status' => 'Application submitted',
                'url' => '#',
            ],
        ];
    }
}
?>

<section class="projects">
    <div class="projects-left">
        <h2><?php echo esc_html($title); ?></h2>
        <a class="projects-button" href="<?php echo esc_url($button_url); ?>">
            <?php echo esc_html($button_text); ?> 
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/arrow.svg'); ?>" alt="arrow" class="button-arrow">
        </a>
    </div>
    <div class="project-list">
        <?php foreach ($projects as $project) : 
            $meta = $project['meta'] ?? '';
            $location = $project['location'] ?? '';
            $status = $project['status'] ?? '';
            $url = $project['url'] ?? '#';
        ?>
            <article class="project-card">
                <div class="project-card__info">
                    <p class="project-card__meta">
                        <?php echo esc_html($meta); ?>
                        <?php if ($status) : ?>
                            / <span class="project-status"><?php echo esc_html($status); ?></span>
                        <?php endif; ?>
                    </p>
                    <p class="project-card__location"><?php echo wp_kses_post(nl2br($location)); ?></p>
                </div>
                <svg class="project-card__arrow" width="19" height="16" viewBox="0 0 19 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11 0.5L18.5 8M18.5 8L11 15.5M18.5 8H0.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </article>
        <?php endforeach; ?>
    </div>
    <a class="projects-button-mobile" href="<?php echo esc_url($button_url); ?>">
        <?php echo esc_html($button_text); ?> 
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/svg/arrow.svg'); ?>" alt="arrow" class="button-arrow">
    </a>
</section>

