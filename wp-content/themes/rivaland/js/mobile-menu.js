// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const navLinks = document.querySelector('.nav-links');
    const nav = document.querySelector('.nav');
    const body = document.body;
    
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            nav.classList.toggle('menu-open');
            menuToggle.classList.toggle('active');
            body.classList.toggle('menu-open');
        });
    }
    
    // Close menu when clicking on a link
    if (navLinks) {
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function() {
                nav.classList.remove('menu-open');
                menuToggle.classList.remove('active');
                body.classList.remove('menu-open');
            });
        });
    }
});

