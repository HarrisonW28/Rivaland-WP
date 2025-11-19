// Footer component loader
// Dynamically loads and injects the footer to ensure consistency across all pages

// Footer HTML template - embedded for reliability (works without server)
const footerHTML = `<footer class="site-footer">
    <div class="footer-top">
        <div class="footer-contact">
            <p>Telephone: 01564 123456</p>
            <p>Email: info@rivaland.co.uk</p>
            <p>Connect on Linkedin</p>
        </div>
        <div class="footer-address">
            <p>Beech House, Poolhead Lane,</p>
            <p>Tanworth In Arden, B94 5ED</p>
        </div>
        <div class="footer-links">
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
        </div>
    </div>
    <div class="footer-divider"></div>
    <div class="footer-bottom">
        <div class="footer-copyright">
            <p>© Riva Land 2025. All rights reserved. Registered in England and Wales No: 09080608.</p>
        </div>
        <div></div>
        <div class="footer-credit">
            <p>Site by Brown + Cooper</p>
        </div>
    </div>
    <div class="footer-logo">
        <img src="assets/svg/logo.svg" alt="RIVALAND">
    </div>
</footer>`;

document.addEventListener('DOMContentLoaded', function() {
    const footerContainer = document.querySelector('[data-footer]');
    
    if (!footerContainer) {
        // If no data-footer attribute, try to find existing footer and replace it
        const existingFooter = document.querySelector('.site-footer');
        if (existingFooter) {
            existingFooter.outerHTML = footerHTML;
        }
        return;
    }
    
    // Replace the container with the footer HTML
    footerContainer.outerHTML = footerHTML;
});

