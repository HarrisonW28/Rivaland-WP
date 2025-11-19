<footer class="site-footer">
    <div class="footer-top">
        <div class="footer-contact">
            <?php
            $telephone = get_field('telephone', 'option') ?? '01564 123456';
            $email = get_field('email', 'option') ?? 'info@rivaland.co.uk';
            $linkedin = get_field('linkedin', 'option') ?? '#';
            ?>
            <p>Telephone: <?php echo esc_html($telephone); ?></p>
            <p>Email: <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
            <p><a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener">Connect on Linkedin</a></p>
        </div>
        <div class="footer-address">
            <?php
            $address = get_field('address', 'option') ?? 'Beech House, Poolhead Lane,<br>Tanworth In Arden, B94 5ED';
            echo wp_kses_post($address);
            ?>
        </div>
        <div class="footer-links">
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container' => false,
                'menu_class' => 'footer-links',
                'fallback_cb' => false,
            ]);
            
            // Fallback if no menu
            if (!has_nav_menu('footer')) {
                echo '<a href="' . esc_url(get_privacy_policy_url()) . '">Privacy</a>';
                echo '<a href="#">Terms</a>';
            }
            ?>
        </div>
    </div>
    <div class="footer-divider"></div>
    <div class="footer-bottom">
        <div class="footer-copyright">
            <?php
            $copyright = get_field('copyright_text', 'option') ?? '© Riva Land 2025. All rights reserved. Registered in England and Wales No: 09080608.';
            echo '<p>' . esc_html($copyright) . '</p>';
            ?>
        </div>
        <div></div>
        <div class="footer-credit">
            <p>Site by Brown + Cooper</p>
        </div>
    </div>
    <div class="footer-logo">
        <?php
        // Use WordPress custom logo from Customizer
        if (has_custom_logo()) {
            $custom_logo_id = get_theme_mod('custom_logo');
            echo wp_get_attachment_image($custom_logo_id, 'full', false, [
                'alt' => get_bloginfo('name', 'display'),
            ]);
        } else {
            // Fallback to default logo
            $default_logo = get_template_directory_uri() . '/assets/svg/logo.svg';
            echo '<img src="' . esc_url($default_logo) . '" alt="' . esc_attr(get_bloginfo('name', 'display')) . '">';
        }
        ?>
    </div>
    <div data-footer></div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
