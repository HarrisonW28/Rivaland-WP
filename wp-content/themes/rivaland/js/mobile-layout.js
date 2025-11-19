// Mobile layout adjustments - button repositioning
document.addEventListener('DOMContentLoaded', function() {
    // Import utilities (using inline for compatibility)
    // Only move button on actual mobile devices (≤ 768px), including iPad Mini
    const BREAKPOINT_MOBILE = 768; // Use 768 to include iPad Mini (768px)
    
    function isMobile() {
        return window.innerWidth <= BREAKPOINT_MOBILE;
    }
    
    // Debounce helper
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    // Generic function to move button on mobile
    function handleButtonReposition(config) {
        const { button, container, list, mobileClass, desktopContainer, desktopClass } = config;
        
        if (!button || !list || !container) return;
        
        if (isMobile()) {
            if (!button.classList.contains(mobileClass)) {
                button.classList.add(mobileClass);
                if (desktopClass) {
                    button.classList.remove(desktopClass);
                }
                // Move button after list
                if (list.nextSibling) {
                    list.parentNode.insertBefore(button, list.nextSibling);
                } else {
                    list.parentNode.appendChild(button);
                }
            }
        } else {
            if (button.classList.contains(mobileClass)) {
                button.classList.remove(mobileClass);
                if (desktopClass) {
                    button.classList.add(desktopClass);
                }
                // Move button back to desktop container
                if (desktopContainer) {
                    desktopContainer.appendChild(button);
                }
            }
        }
    }
    
    // Move "What we offer" button after accordion on mobile
    function moveServicesButton() {
        handleButtonReposition({
            button: document.querySelector('.accordion--services__card .button'),
            container: document.querySelector('.accordion--services'),
            list: document.querySelector('.accordion'),
            mobileClass: 'link-button-mobile',
            desktopContainer: document.querySelector('.accordion--services__card'),
            desktopClass: 'link-button-offset'
        });
    }
    
    // Move "View all projects" button after project list on mobile
    function moveProjectsButton() {
        handleButtonReposition({
            button: document.querySelector('.projects-left .projects-button'),
            container: document.querySelector('.projects'),
            list: document.querySelector('.project-list'),
            mobileClass: 'projects-button-mobile',
            desktopContainer: document.querySelector('.projects-left')
        });
    }
    
    // Debounced resize handler
    const debouncedResize = debounce(() => {
        moveServicesButton();
        moveProjectsButton();
    }, 150);
    
    // Initial setup
    moveServicesButton();
    moveProjectsButton();
    
    // Handle resize
    window.addEventListener('resize', debouncedResize);
});

