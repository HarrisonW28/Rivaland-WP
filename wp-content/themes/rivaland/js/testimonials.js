// Testimonial navigation functionality
document.addEventListener('DOMContentLoaded', function() {
    const testimonialSection = document.querySelector('.testimonial-section');
    if (!testimonialSection) return;
    
    const testimonialItems = testimonialSection.querySelectorAll('.testimonial-item');
    const prevButton = testimonialSection.querySelector('.testimonial-nav .prev');
    const nextButton = testimonialSection.querySelector('.testimonial-nav .next');
    
    if (testimonialItems.length === 0) return;
    
    let currentIndex = 0;
    const testimonialRight = testimonialSection.querySelector('.testimonial-right');
    
    // Function to scale text to fit max-height on mobile and desktop
    function scaleTextToFit(item) {
        if (!item) return;
        
        const blockquote = item.querySelector('blockquote');
        if (!blockquote) return;
        
        const isDesktop = window.innerWidth >= 1024; // Desktop breakpoint
        
        // Ensure item is visible for measurement
        const wasHidden = item.style.display === 'none' || !item.classList.contains('active');
        if (wasHidden) {
            item.style.display = 'flex';
            item.style.position = 'relative';
        }
        
        let maxHeight, padding, authorHeight;
        
        if (isDesktop) {
            maxHeight = 514; // Desktop height
            padding = 160; // Top and bottom padding (80px * 2)
            authorHeight = 30; // Approximate author height
        } else {
            maxHeight = 400; // Mobile max height
            padding = 80; // Top and bottom padding (40px * 2)
            authorHeight = 30; // Approximate author height
        }
        
        const availableHeight = maxHeight - padding - authorHeight;
        
        // Reset font size to check natural height
        blockquote.style.fontSize = '';
        blockquote.style.transform = '';
        
        const naturalHeight = blockquote.scrollHeight;
        
        if (naturalHeight > availableHeight) {
            const scale = availableHeight / naturalHeight;
            blockquote.style.transform = `scale(${scale})`;
            blockquote.style.transformOrigin = 'center top';
        } else {
            blockquote.style.transform = '';
        }
        
        // Restore hidden state if it was hidden
        if (wasHidden && !item.classList.contains('active')) {
            item.style.display = 'none';
            item.style.position = 'absolute';
        }
    }
    
    function showTestimonial(index) {
        const activeItem = testimonialItems[index];
        if (!activeItem || !testimonialRight) return;
        
        const isDesktop = window.innerWidth >= 1024; // Desktop breakpoint
        
        // Get current active item
        const currentActive = testimonialRight.querySelector('.testimonial-item.active');
        
        // Height is fixed on both desktop and mobile, no need to calculate
        
        // Step 1: Fade out current active item
        if (currentActive && currentActive !== activeItem) {
            currentActive.classList.remove('active');
            
            // Step 2: After fade out completes, show and fade in new item
            setTimeout(() => {
                // Hide old item completely
                currentActive.style.display = 'none';
                currentActive.style.opacity = '0';
                
                // Show new item
                activeItem.style.display = 'flex';
                activeItem.style.opacity = '0';
                
                // Fade in new item
                requestAnimationFrame(() => {
                    activeItem.classList.add('active');
                    activeItem.style.opacity = '1';
                    // Scale text to fit (mobile and desktop)
                    scaleTextToFit(activeItem);
                });
            }, 400); // Wait for fade out to complete
        } else {
            // No current active item, show immediately
            activeItem.style.display = 'flex';
            activeItem.style.opacity = '0';
            
            requestAnimationFrame(() => {
                activeItem.classList.add('active');
                activeItem.style.opacity = '1';
                // Scale text to fit (mobile and desktop)
                scaleTextToFit(activeItem);
            });
        }
        
        // Update button states
        if (prevButton) {
            prevButton.classList.toggle('disabled', index === 0);
        }
        if (nextButton) {
            nextButton.classList.toggle('disabled', index === testimonialItems.length - 1);
        }
    }
    
    function goToPrev() {
        if (currentIndex > 0) {
            currentIndex--;
            showTestimonial(currentIndex);
        }
    }
    
    function goToNext() {
        if (currentIndex < testimonialItems.length - 1) {
            currentIndex++;
            showTestimonial(currentIndex);
        }
    }
    
    if (prevButton) {
        prevButton.addEventListener('click', goToPrev);
    }
    
    if (nextButton) {
        nextButton.addEventListener('click', goToNext);
    }
    
    // Hide all items initially except the first one
    testimonialItems.forEach((item, i) => {
        if (i !== currentIndex) {
            item.style.display = 'none';
            item.style.position = 'absolute';
            item.classList.remove('active');
        }
    });
    
    // Initialize
    showTestimonial(currentIndex);
    
    // Scale text to fit on initial load (mobile and desktop)
    setTimeout(() => {
        if (testimonialRight) {
            const activeItem = testimonialRight.querySelector('.testimonial-item.active');
            if (activeItem) {
                scaleTextToFit(activeItem);
            }
        }
    }, 100);
    
    // Scale text on window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(function() {
            const activeItem = testimonialRight.querySelector('.testimonial-item.active');
            if (activeItem) {
                scaleTextToFit(activeItem);
            }
        }, 250);
    });
});

