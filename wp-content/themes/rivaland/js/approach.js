// Approach section vertical scroll progress bar
document.addEventListener('DOMContentLoaded', function() {
    const approachContainer = document.querySelector('.approach-container');
    const approachSection = document.querySelector('.approach-section');
    const progressBar = approachSection ? approachSection.querySelector('.approach-progress') : null;
    
    if (approachContainer && progressBar && approachSection) {
        // Ensure container can scroll - force height calculation
        const pages = approachContainer.querySelectorAll('.approach-page');
        if (pages.length > 0) {
            const containerHeight = approachContainer.clientHeight;
            pages.forEach(page => {
                // Ensure each page is exactly the container height
                page.style.minHeight = containerHeight + 'px';
                page.style.height = containerHeight + 'px';
            });
        }
        
        let hasScrolledAllPages = false;
        
        function updateProgress() {
            const scrollTop = approachContainer.scrollTop;
            const containerHeight = approachContainer.clientHeight;
            const scrollHeight = approachContainer.scrollHeight;
            const pageHeight = containerHeight; // Each page is 100vh
            const totalPages = pages.length;
            
            // Check if we've scrolled through all pages (within 5px of the end)
            const maxScroll = scrollHeight - containerHeight;
            hasScrolledAllPages = scrollTop >= maxScroll - 5;
            
            // Calculate which page we're on (0 = page 1, 1 = page 2, etc.)
            const currentPage = Math.floor(scrollTop / pageHeight);
            const pageProgress = (scrollTop % pageHeight) / pageHeight;
            
            // Progress calculation: 
            // Page 1: starts at 50% (when scrollTop = 0), fills to 100% (when scrollTop = pageHeight)
            // Page 2: stays at 100% (already scrolled through page 1)
            let progress;
            if (currentPage === 0) {
                // On page 1: 50% + (pageProgress * 50%) = 50% to 100%
                progress = 50 + (pageProgress * 50);
            } else {
                // On page 2 or beyond: already at 100%
                progress = 100;
            }
            
            // Clamp progress between 50 and 100
            const clampedProgress = Math.max(50, Math.min(100, progress));
            
            // Get the text box width from the first page's approach-right element for width calculation
            const firstPage = pages[0];
            const approachRight = firstPage ? firstPage.querySelector('.approach-right') : null;
            const textElement = approachRight ? approachRight.querySelector('.approach-text') : null;
            
            // Use text element width for progress bar width
            let textBoxWidth = 0;
            if (textElement) {
                const textRect = textElement.getBoundingClientRect();
                textBoxWidth = textRect.width;
            } else if (approachRight) {
                const rightRect = approachRight.getBoundingClientRect();
                textBoxWidth = rightRect.width;
            } else {
                textBoxWidth = approachContainer.clientWidth;
            }
            
            // Position progress bar at bottom left of right container (not aligned with text)
            // Get the container's left position relative to the approach section
            const sectionRect = approachSection.getBoundingClientRect();
            const containerRect = approachContainer.getBoundingClientRect();
            const containerLeft = containerRect.left - sectionRect.left;
            
            // Calculate width based on progress using text box width
            // Ensure minimum width of 20px so it's always draggable
            const progressWidth = Math.max(20, (clampedProgress / 100) * textBoxWidth);
            progressBar.style.width = progressWidth + 'px';
            progressBar.style.left = containerLeft + 'px'; // At the left edge of the right container
        }
        
        // Check if approach section is fully in viewport
        function isSectionInViewport() {
            const rect = approachSection.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            const windowWidth = window.innerWidth;
            // Section is fully in viewport if top is at or above viewport top and bottom is at or below viewport bottom
            return rect.top >= 0 && rect.bottom <= windowHeight && 
                   rect.left >= 0 && rect.right <= windowWidth;
        }
        
        // Check if approach container can scroll in a direction
        function canScrollDown() {
            const scrollTop = approachContainer.scrollTop;
            const scrollHeight = approachContainer.scrollHeight;
            const containerHeight = approachContainer.clientHeight;
            return scrollTop < scrollHeight - containerHeight - 5;
        }
        
        function canScrollUp() {
            return approachContainer.scrollTop > 5;
        }
        
        // Intercept page scroll when approach section is active
        function handlePageScroll(e) {
            // Skip if modifier keys are pressed
            if (e.shiftKey || e.ctrlKey || e.metaKey || e.altKey) {
                return;
            }
            
            // Only handle vertical scrolling
            if (Math.abs(e.deltaY) <= Math.abs(e.deltaX)) {
                return;
            }
            
            // Check if section is fully in viewport
            if (!isSectionInViewport()) {
                return;
            }
            
            // Scrolling down
            if (e.deltaY > 0) {
                // If container can scroll down, intercept and scroll container
                if (canScrollDown()) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    approachContainer.scrollTop += e.deltaY;
                    updateProgress();
                    return false;
                }
                // Container at bottom - allow normal page scroll down
                return;
            }
            
            // Scrolling up
            if (e.deltaY < 0) {
                // If container can scroll up, intercept and scroll container
                if (canScrollUp()) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    approachContainer.scrollTop += e.deltaY;
                    updateProgress();
                    return false;
                }
                // Container at top - allow normal page scroll up (don't prevent)
                return;
            }
        }
        
        // Make progress bar draggable
        let isDragging = false;
        let dragStartX = 0;
        let dragStartScroll = 0;
        let textBoxWidth = 0;
        
        function updateTextBoxWidth() {
            const firstPage = pages[0];
            const approachRight = firstPage ? firstPage.querySelector('.approach-right') : null;
            const textElement = approachRight ? approachRight.querySelector('.approach-text') : null;
            
            if (textElement) {
                const textRect = textElement.getBoundingClientRect();
                textBoxWidth = textRect.width;
            } else if (approachRight) {
                const rightRect = approachRight.getBoundingClientRect();
                textBoxWidth = rightRect.width;
            } else {
                textBoxWidth = approachContainer.clientWidth;
            }
        }
        
        progressBar.addEventListener('mousedown', function(e) {
            isDragging = true;
            dragStartX = e.clientX;
            dragStartScroll = approachContainer.scrollTop;
            updateTextBoxWidth();
            e.preventDefault();
            e.stopPropagation();
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const sectionRect = approachSection.getBoundingClientRect();
            const containerRect = approachContainer.getBoundingClientRect();
            const containerLeft = containerRect.left - sectionRect.left;
            
            // Calculate relative X position from the start of the progress bar
            const relativeX = e.clientX - sectionRect.left - containerLeft;
            
            // Calculate progress percentage based on drag position
            // Clamp to 0-100% range - only allow dragging within progress bar bounds
            const progressPercent = Math.max(0, Math.min(100, (relativeX / textBoxWidth) * 100));
            
            // Convert progress to scroll position
            // Progress 50% = scroll 0, Progress 100% = scroll maxScroll
            const scrollHeight = approachContainer.scrollHeight - approachContainer.clientHeight;
            const normalizedProgress = Math.max(0, Math.min(1, (progressPercent - 50) / 50));
            const targetScroll = normalizedProgress * scrollHeight;
            
            approachContainer.scrollTop = targetScroll;
            updateProgress();
        });
        
        document.addEventListener('mouseup', function() {
            isDragging = false;
        });
        
        // Prevent text selection while dragging
        progressBar.addEventListener('selectstart', function(e) {
            if (isDragging) {
                e.preventDefault();
            }
        });
        
        // Add global wheel listener to intercept page scroll (only when not scrolling container directly)
        window.addEventListener('wheel', handlePageScroll, { passive: false, capture: true });
        
        // Update on scroll
        approachContainer.addEventListener('scroll', updateProgress);
        
        // Initial update
        setTimeout(updateProgress, 100);
        
        // Debounce helper for resize
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
        
        // Update on resize (debounced for performance)
        const debouncedResize = debounce(function() {
            // Recalculate page heights on resize
            const containerHeight = approachContainer.clientHeight;
            pages.forEach(page => {
                page.style.minHeight = containerHeight + 'px';
                page.style.height = containerHeight + 'px';
            });
            // Recalculate progress bar position after resize
            setTimeout(updateProgress, 100);
        }, 150);
        
        window.addEventListener('resize', debouncedResize);
        
        // Mobile navigation arrows
        const prevButton = approachSection.querySelector('.approach-prev');
        const nextButton = approachSection.querySelector('.approach-next');
        
        function updateArrowStates() {
            if (!prevButton || !nextButton) return;
            
            const currentScroll = approachContainer.scrollTop;
            const pageHeight = approachContainer.clientHeight;
            const maxScroll = approachContainer.scrollHeight - approachContainer.clientHeight;
            const scrollThreshold = 5; // Small threshold to account for rounding
            
            // Disable prev button if at the start
            if (currentScroll <= scrollThreshold) {
                prevButton.classList.add('disabled');
            } else {
                prevButton.classList.remove('disabled');
            }
            
            // Disable next button if at the end
            if (currentScroll >= maxScroll - scrollThreshold) {
                nextButton.classList.add('disabled');
            } else {
                nextButton.classList.remove('disabled');
            }
        }
        
        if (prevButton && nextButton) {
            prevButton.addEventListener('click', function() {
                if (this.classList.contains('disabled')) return;
                
                const currentScroll = approachContainer.scrollTop;
                const pageHeight = approachContainer.clientHeight;
                const targetScroll = Math.max(0, currentScroll - pageHeight);
                approachContainer.scrollTo({
                    top: targetScroll,
                    behavior: 'smooth'
                });
            });
            
            nextButton.addEventListener('click', function() {
                if (this.classList.contains('disabled')) return;
                
                const currentScroll = approachContainer.scrollTop;
                const pageHeight = approachContainer.clientHeight;
                const maxScroll = approachContainer.scrollHeight - approachContainer.clientHeight;
                const targetScroll = Math.min(maxScroll, currentScroll + pageHeight);
                approachContainer.scrollTo({
                    top: targetScroll,
                    behavior: 'smooth'
                });
            });
            
            // Update arrow states on scroll
            approachContainer.addEventListener('scroll', updateArrowStates);
            
            // Initial state update
            updateArrowStates();
        }
    }
});

