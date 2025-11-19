// Accordion functionality
document.addEventListener('DOMContentLoaded', function() {
    const accordionToggles = document.querySelectorAll('.accordion__toggle');
    
    // Function to update icon text
    function updateIcon(item, isActive) {
        const toggle = item.querySelector('.accordion__toggle');
        if (toggle) {
            toggle.textContent = isActive ? '×' : '+';
        }
    }
    
    // Initialize icons for all items
    document.querySelectorAll('.accordion__item').forEach(item => {
        const isActive = item.classList.contains('active');
        updateIcon(item, isActive);
    });
    
    // Function to update accordion card layout (for services variant)
    function updateAccordionCardLayout() {
        const accordionSection = document.querySelector('.accordion--services');
        if (accordionSection) {
            const hasActive = document.querySelector('.accordion__item.active');
            if (hasActive) {
                accordionSection.classList.add('has-active-accordion');
            } else {
                accordionSection.classList.remove('has-active-accordion');
            }
        }
    }
    
    accordionToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const accordionItem = this.closest('.accordion__item');
            const isActive = accordionItem.classList.contains('active');
            const accordionSection = this.closest('.accordion--services');
            
            // Keep the layout class active if we're switching between accordions
            if (accordionSection) {
                const hasActiveBefore = accordionSection.classList.contains('has-active-accordion');
                const willHaveActive = !isActive;
                
                // If switching from one active accordion to another, maintain layout class
                if (hasActiveBefore && willHaveActive) {
                    // Don't remove the class - keep it active during transition
                }
            }
            
            // Close all accordions and reset icons
            document.querySelectorAll('.accordion__item').forEach(item => {
                item.classList.remove('active');
                updateIcon(item, false);
            });
            
            // Toggle the clicked item
            if (!isActive) {
                accordionItem.classList.add('active');
                updateIcon(accordionItem, true);
            }
            
            // Update accordion card layout - this will maintain or update the class appropriately
            updateAccordionCardLayout();
        });
    });
    
    // Also allow clicking on the header to toggle
    const accordionHeaders = document.querySelectorAll('.accordion__header');
    accordionHeaders.forEach(header => {
        const accordionItem = header.closest('.accordion__item');
        const toggle = accordionItem.querySelector('.accordion__toggle');
        if (toggle) {
            header.addEventListener('click', function(e) {
                if (e.target !== toggle) {
                    toggle.click();
                }
            });
        }
    });
    
    // Read more functionality for mobile accordion--page
    const readMoreLinks = document.querySelectorAll('.service-read-more');
    readMoreLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const textFull = this.previousElementSibling; // .service-text-full
            const isExpanded = textFull.classList.contains('expanded');
            
            if (isExpanded) {
                textFull.classList.remove('expanded');
                this.classList.remove('expanded');
                this.querySelector('.read-more-text').textContent = 'Read more';
            } else {
                textFull.classList.add('expanded');
                this.classList.add('expanded');
                this.querySelector('.read-more-text').textContent = 'Read less';
            }
        });
    });
});

