<?php
/**
 * Template Name: Contact
 * 
 * Contact page template
 * 
 * @package Rivaland
 */

get_header();
?>

<main>
    <?php
    // Intro Section
    get_template_part('template-parts/intro');
    
    // Contact Information Section
    $contact_info = get_field('contact_info');
    $eyebrow = $contact_info['eyebrow'] ?? 'Get in touch';
    $contact_form = $contact_info['contact_form'] ?? '';
    $telephone = $contact_info['telephone'] ?? '01564 123456';
    $email = $contact_info['email'] ?? 'info@rivaland.co.uk';
    
    // Update intro text with contact info if not set
    $intro_section = get_field('intro_section');
    if (empty($intro_section['intro_text'])) {
        $intro_section['intro_text'] = "If you have an enquiry and would like to get in touch, please complete this form. Alternatively you can speak to a member of the team by calling {$telephone} or email your enquiry to {$email}.";
    }
    ?>
    <section class="contact-info">
        <div class="contact-info__card">
            <p class="eyebrow"><?php echo esc_html($eyebrow); ?></p>
        </div>
        <div class="contact-info__form">
            <?php
            // Use Contact Form 7 or custom form
            if ($contact_form) {
                echo do_shortcode($contact_form);
            } else {
                // Fallback to basic form
                get_template_part('template-parts/contact-form');
            }
            ?>
        </div>
    </section>
    
    <?php
    // Feature Section (Contact Information)
    get_template_part('template-parts/feature');
    ?>
</main>

<?php
get_footer();

