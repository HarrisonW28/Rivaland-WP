// Approach section with enhanced hover-to-scroll interaction
// Implements momentum-based detection, velocity tracking, and smart boundary handling
// Works with Lenis smooth scrolling

function initApproach() {
    const approachContainer = document.querySelector('.approach-container');
    const approachSection = document.querySelector('.approach-section');
    const progressBar = approachSection ? approachSection.querySelector('.approach-progress') : null;
    
    // Check if elements exist - if not, wait a bit and try again
    if (!approachContainer || !progressBar || !approachSection) {
        setTimeout(initApproach, 100);
        return;
    }
    
    // Prevent double initialization
    if (window.approachInitialized) return;
    window.approachInitialized = true;
    
    // Wait for Lenis to be available
    function waitForLenis(callback) {
        if (window.lenis) {
            callback(window.lenis);
        } else {
            const checkInterval = setInterval(() => {
                if (window.lenis) {
                    clearInterval(checkInterval);
                    callback(window.lenis);
                }
            }, 50);
            
            setTimeout(() => {
                clearInterval(checkInterval);
                callback(null);
            }, 1000);
        }
    }
    
    waitForLenis(function(lenis) {
        initializeApproach(lenis);
    });
    
    function initializeApproach(lenis) {
        // Store lenis reference globally
        window.approachLenis = lenis;
        
        // Enhanced state management
        const state = {
            isActive: false,              // Section is hovered/in viewport (>80%)
            isLocked: false,              // Page scroll is currently locked
            scrollVelocity: 0,            // Current scroll velocity (px/ms)
            lastScrollTime: 0,            // Timestamp of last scroll event
            lastScrollDelta: 0,           // Last scroll delta value
            boundaryBuffer: 50,           // Pixels from boundary before unlock consideration
            velocityThreshold: 2,         // px/ms threshold for fast scroll detection
            unlockAttempts: 0,            // Number of unlock attempts at boundary
            unlockAttemptsRequired: 2,     // Required attempts before unlock (slow scroll)
            isUnlocking: false,           // Flag to prevent rapid lock/unlock cycles
            lastScrollPosition: 0,         // Last scroll position for velocity calculation
            momentum: 0                   // Preserved momentum for transfer to Lenis
        };
        
        // Ensure container can scroll - force height calculation
        const pages = approachContainer.querySelectorAll('.approach-page');
        if (pages.length > 0) {
            const containerHeight = approachContainer.clientHeight;
            pages.forEach(page => {
                page.style.minHeight = containerHeight + 'px';
                page.style.height = containerHeight + 'px';
            });
        }
        
        // Progress bar update function
        function updateProgress() {
            const scrollTop = approachContainer.scrollTop;
            const containerHeight = approachContainer.clientHeight;
            const scrollHeight = approachContainer.scrollHeight;
            const pageHeight = containerHeight;
            
            // Calculate which page we're on
            const currentPage = Math.floor(scrollTop / pageHeight);
            const pageProgress = (scrollTop % pageHeight) / pageHeight;
            
            // Progress calculation: Page 1: 50% to 100%, Page 2+: 100%
            let progress;
            if (currentPage === 0) {
                progress = 50 + (pageProgress * 50);
            } else {
                progress = 100;
            }
            
            const clampedProgress = Math.max(50, Math.min(100, progress));
            
            // Get text box width for progress bar
            const firstPage = pages[0];
            const approachRight = firstPage ? firstPage.querySelector('.approach-right') : null;
            const textElement = approachRight ? approachRight.querySelector('.approach-text') : null;
            
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
            
            // Position progress bar
            const sectionRect = approachSection.getBoundingClientRect();
            const containerRect = approachContainer.getBoundingClientRect();
            const containerLeft = containerRect.left - sectionRect.left;
            
            const progressWidth = Math.max(20, (clampedProgress / 100) * textBoxWidth);
            progressBar.style.width = progressWidth + 'px';
            progressBar.style.left = containerLeft + 'px';
        }
        
        // Check if section is >80% in viewport (not just fully visible)
        function isSectionInViewport(threshold = 0.8) {
            const rect = approachSection.getBoundingClientRect();
            const windowHeight = window.innerHeight;
            const windowWidth = window.innerWidth;
            
            // Calculate intersection area
            const visibleTop = Math.max(0, rect.top);
            const visibleBottom = Math.min(windowHeight, rect.bottom);
            const visibleLeft = Math.max(0, rect.left);
            const visibleRight = Math.min(windowWidth, rect.right);
            
            const visibleHeight = Math.max(0, visibleBottom - visibleTop);
            const visibleWidth = Math.max(0, visibleRight - visibleLeft);
            const sectionArea = rect.height * rect.width;
            const visibleArea = visibleHeight * visibleWidth;
            
            return sectionArea > 0 && (visibleArea / sectionArea) >= threshold;
        }
        
        // Check if container can scroll
        function canScrollDown() {
            const scrollTop = approachContainer.scrollTop;
            const scrollHeight = approachContainer.scrollHeight;
            const containerHeight = approachContainer.clientHeight;
            return scrollTop < scrollHeight - containerHeight - state.boundaryBuffer;
        }
        
        function canScrollUp() {
            return approachContainer.scrollTop > state.boundaryBuffer;
        }
        
        // Calculate scroll velocity (px/ms)
        function calculateVelocity(delta, timestamp) {
            if (state.lastScrollTime === 0) {
                state.lastScrollTime = timestamp;
                state.lastScrollDelta = delta;
                return 0;
            }
            
            const timeDelta = timestamp - state.lastScrollTime;
            if (timeDelta === 0) return state.scrollVelocity;
            
            // Use exponential moving average for smoother velocity
            const instantVelocity = Math.abs(delta) / timeDelta;
            state.scrollVelocity = state.scrollVelocity * 0.7 + instantVelocity * 0.3;
            
            state.lastScrollTime = timestamp;
            state.lastScrollDelta = delta;
            
            return state.scrollVelocity;
        }
        
        // Check if at boundary with hysteresis buffer
        function isAtBoundary(position, direction) {
            const currentScroll = approachContainer.scrollTop;
            const maxScroll = approachContainer.scrollHeight - approachContainer.clientHeight;
            const buffer = state.boundaryBuffer;
            
            if (direction === 'up') {
                return currentScroll <= buffer;
            } else {
                return currentScroll >= (maxScroll - buffer);
            }
        }
        
        // Smart unlock decision based on velocity and attempts
        function shouldUnlock(direction, position, velocity) {
            const atBoundary = isAtBoundary(position, direction);
            const highVelocity = velocity > state.velocityThreshold;
            const multipleAttempts = state.unlockAttempts >= state.unlockAttemptsRequired;
            
            // Fast unlock conditions - user scrolling fast at boundary = wants to exit
            if (atBoundary && highVelocity) {
                return true;
            }
            
            // Slow unlock conditions - user made multiple attempts = intentional exit
            if (atBoundary && multipleAttempts) {
                return true;
            }
            
            return false;
        }
        
        // Use IntersectionObserver with 80% threshold for more responsive activation
        let lastIntersectionState = false;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                // Section must be >80% in viewport
                const isInView = entry.isIntersecting && entry.intersectionRatio >= 0.8;
                
                if (isInView !== lastIntersectionState) {
                    lastIntersectionState = isInView;
                    state.isActive = isInView;
                    
                    if (!isInView) {
                        // Section left viewport - unlock
                        if (state.isLocked) {
                            unlockPage();
                        }
                    }
                }
            });
        }, {
            threshold: [0, 0.8, 0.9, 1.0], // Multiple thresholds for better detection
            rootMargin: '0px'
        });
        
        observer.observe(approachSection);
        
        // Section becomes active when in viewport - no hover required
        // Once active and locked, stays locked until section leaves viewport or boundary reached
        // This allows continuous scrolling without keeping mouse over section
        
        // Enhanced wheel handler with velocity tracking and smart interception
        function handleWheelScroll(e) {
            // Skip if modifier keys are pressed
            if (e.shiftKey || e.ctrlKey || e.metaKey || e.altKey) {
                return;
            }
            
            // Only handle vertical scrolling
            if (Math.abs(e.deltaY) <= Math.abs(e.deltaX)) {
                return;
            }
            
            // Calculate velocity
            const timestamp = performance.now();
            const velocity = calculateVelocity(e.deltaY, timestamp);
            
            // Only intercept if section is >80% in view (no hover required)
            if (!state.isActive) {
                if (state.isLocked) {
                    unlockPage();
                }
                return;
            }
            
            const scrollingDown = e.deltaY > 0;
            const scrollingUp = e.deltaY < 0;
            const currentScroll = approachContainer.scrollTop;
            const maxScroll = approachContainer.scrollHeight - approachContainer.clientHeight;
            
            // Check boundaries with buffer
            const atTop = isAtBoundary(currentScroll, 'up');
            const atBottom = isAtBoundary(currentScroll, 'down');
            
            // Smart unlock decision
            const direction = scrollingDown ? 'down' : 'up';
            if (shouldUnlock(direction, currentScroll, velocity)) {
                // Unlock and allow page scroll
                if (state.isLocked) {
                    // Preserve momentum for Lenis
                    state.momentum = velocity * 10; // Convert to reasonable scroll amount
                    unlockPage();
                }
                // Reset unlock attempts
                state.unlockAttempts = 0;
                return;
            }
            
            // Track unlock attempts at boundary
            if ((atTop && scrollingUp) || (atBottom && scrollingDown)) {
                state.unlockAttempts++;
            } else {
                state.unlockAttempts = 0; // Reset if not at boundary
            }
            
            // Check if container can scroll in requested direction
            const containerCanScrollDown = currentScroll < (maxScroll - state.boundaryBuffer);
            const containerCanScrollUp = currentScroll > state.boundaryBuffer;
            const canScroll = scrollingDown ? containerCanScrollDown : (scrollingUp ? containerCanScrollUp : false);
            
            if (canScroll) {
                // Prevent default and handle container scroll
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                
                // Stop Lenis immediately
                if (lenis) {
                    lenis.stop();
                }
                
                if (!state.isLocked) {
                    lockPage();
                }
                
                // Scroll with momentum-based easing
                const pageHeight = approachContainer.clientHeight;
                const currentPage = Math.floor((currentScroll + pageHeight / 2) / pageHeight);
                
                if (scrollingDown) {
                    const nextPage = currentPage + 1;
                    const targetScroll = Math.min(nextPage * pageHeight, maxScroll);
                    
                    approachContainer.scrollTo({
                        top: targetScroll,
                        behavior: 'smooth'
                    });
                } else {
                    const prevPage = currentPage - 1;
                    const targetScroll = Math.max(0, prevPage * pageHeight);
                    
                    approachContainer.scrollTo({
                        top: targetScroll,
                        behavior: 'smooth'
                    });
                }
                
                // Update progress and arrows using RAF for smooth updates
                requestAnimationFrame(() => {
                    updateProgress();
                    updateArrowStates();
                });
                
                    return false;
            } else {
                // Can't scroll - unlock if locked
                if (state.isLocked) {
                    unlockPage();
                }
            }
        }
        
        // Lock/unlock functions with smooth transitions
        let lockTimeout = null;
        let unlockTimeout = null;
        
        function lockPage() {
            if (state.isLocked || state.isUnlocking) return;
            
            // Clear any pending unlock
            if (unlockTimeout) {
                clearTimeout(unlockTimeout);
                unlockTimeout = null;
                state.isUnlocking = false;
            }
            
            // Clear any pending lock
            if (lockTimeout) {
                clearTimeout(lockTimeout);
            }
            
            lockTimeout = setTimeout(() => {
                if (state.isLocked || state.isUnlocking) return;
                state.isLocked = true;
                
                if (lenis) {
                    lenis.stop();
                    if (lenis.options) {
                        lenis.options.smoothWheel = false;
                    }
                }
                document.body.style.overflow = 'hidden';
                lockTimeout = null;
            }, 30);
        }
        
        function unlockPage() {
            if (!state.isLocked) return;
            
            // Clear any pending lock
            if (lockTimeout) {
                clearTimeout(lockTimeout);
                lockTimeout = null;
            }
            
            // Clear any pending unlock
            if (unlockTimeout) {
                clearTimeout(unlockTimeout);
            }
            
            state.isUnlocking = true;
            
            unlockTimeout = setTimeout(() => {
                if (!state.isLocked) {
                    state.isUnlocking = false;
                    return;
                }
                
                state.isLocked = false;
                state.isUnlocking = false;
                
                if (lenis) {
                    if (lenis.options) {
                        lenis.options.smoothWheel = true;
                    }
                    lenis.start();
                    
                    // Transfer momentum to Lenis if available
                    if (state.momentum !== 0) {
                        // Scroll page by momentum amount
                        const currentScroll = window.scrollY || document.documentElement.scrollTop;
                        lenis.scrollTo(currentScroll + state.momentum, {
                            duration: 0.5,
                            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t))
                        });
                        state.momentum = 0; // Reset momentum
                    }
                }
                document.body.style.overflow = '';
                unlockTimeout = null;
            }, 200);
        }
        
        // Intercept wheel events at capture phase (before Lenis)
        window.addEventListener('wheel', handleWheelScroll, { passive: false, capture: true });
        
        // Enhanced touch handling with velocity tracking
        let touchStartY = 0;
        let touchStartScroll = 0;
        let touchStartTime = 0;
        let isTouching = false;
        
        approachSection.addEventListener('touchstart', function(e) {
            if (e.touches.length === 1 && state.isActive) {
                isTouching = true;
                touchStartY = e.touches[0].clientY;
                touchStartScroll = approachContainer.scrollTop;
                touchStartTime = performance.now();
            }
        }, { passive: true });
        
        approachSection.addEventListener('touchmove', function(e) {
            if (!isTouching || e.touches.length !== 1) {
                if (state.isLocked) {
                    unlockPage();
                }
                return;
            }
            
            // Only handle if section is active (in viewport)
            if (!state.isActive) {
                if (state.isLocked) {
                    unlockPage();
                }
                return;
            }
            
            const touchCurrentY = e.touches[0].clientY;
            const touchDelta = touchStartY - touchCurrentY;
            const touchTime = performance.now() - touchStartTime;
            const touchVelocity = Math.abs(touchDelta) / touchTime;
            
            const currentScroll = approachContainer.scrollTop;
            const maxScroll = approachContainer.scrollHeight - approachContainer.clientHeight;
            const scrollingDown = touchDelta > 0;
            const scrollingUp = touchDelta < 0;
            
            const atTop = isAtBoundary(currentScroll, 'up');
            const atBottom = isAtBoundary(currentScroll, 'down');
            const direction = scrollingDown ? 'down' : 'up';
            
            // Smart unlock for touch
            if (shouldUnlock(direction, currentScroll, touchVelocity)) {
                if (state.isLocked) {
                    state.momentum = touchVelocity * 10;
                    unlockPage();
                }
                state.unlockAttempts = 0;
                return;
            }
            
            if ((atTop && scrollingUp) || (atBottom && scrollingDown)) {
                state.unlockAttempts++;
            } else {
                state.unlockAttempts = 0;
            }
            
            const containerCanScrollDown = currentScroll < (maxScroll - state.boundaryBuffer);
            const containerCanScrollUp = currentScroll > state.boundaryBuffer;
            const canScroll = scrollingDown ? containerCanScrollDown : (scrollingUp ? containerCanScrollUp : false);
            
            if (canScroll) {
                    e.preventDefault();
                    e.stopPropagation();
                
                if (lenis) {
                    lenis.stop();
                }
                if (!state.isLocked) {
                    lockPage();
                }
                
                const newScrollTop = touchStartScroll + touchDelta;
                approachContainer.scrollTop = Math.min(
                    Math.max(0, newScrollTop),
                    maxScroll
                );
                
                requestAnimationFrame(() => {
                    updateProgress();
                    updateArrowStates();
                });
            } else {
                if (state.isLocked) {
                    unlockPage();
                }
            }
        }, { passive: false });
        
        approachSection.addEventListener('touchend', function() {
            isTouching = false;
            const hasAnyScroll = canScrollDown() || canScrollUp();
            if (state.isActive && hasAnyScroll && !state.isLocked) {
                lockPage();
            } else if (!hasAnyScroll && state.isLocked) {
                unlockPage();
            }
        }, { passive: true });
        
        // Progress bar draggable
        let isDragging = false;
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
            updateTextBoxWidth();
            e.preventDefault();
            e.stopPropagation();
        });
        
        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            
            const sectionRect = approachSection.getBoundingClientRect();
            const containerRect = approachContainer.getBoundingClientRect();
            const containerLeft = containerRect.left - sectionRect.left;
            const relativeX = e.clientX - sectionRect.left - containerLeft;
            const progressPercent = Math.max(0, Math.min(100, (relativeX / textBoxWidth) * 100));
            const scrollHeight = approachContainer.scrollHeight - approachContainer.clientHeight;
            const normalizedProgress = Math.max(0, Math.min(1, (progressPercent - 50) / 50));
            const targetScroll = normalizedProgress * scrollHeight;
            
            approachContainer.scrollTop = targetScroll;
            requestAnimationFrame(() => {
            updateProgress();
                updateArrowStates();
            });
        });
        
        document.addEventListener('mouseup', function() {
            isDragging = false;
        });
        
        progressBar.addEventListener('selectstart', function(e) {
            if (isDragging) {
                e.preventDefault();
            }
        });
        
        // Throttled scroll handler for container updates
        let scrollRafId = null;
        approachContainer.addEventListener('scroll', function() {
            if (scrollRafId) return;
            
            scrollRafId = requestAnimationFrame(() => {
                updateProgress();
                updateArrowStates();
        
                // Reset unlock attempts when scrolling normally
                const currentScroll = approachContainer.scrollTop;
                const maxScroll = approachContainer.scrollHeight - approachContainer.clientHeight;
                const atTop = isAtBoundary(currentScroll, 'up');
                const atBottom = isAtBoundary(currentScroll, 'down');
                
                if (!atTop && !atBottom) {
                    state.unlockAttempts = 0;
                }
                
                scrollRafId = null;
            });
        }, { passive: true });
        
        // Initial update
        setTimeout(() => {
            requestAnimationFrame(updateProgress);
        }, 100);
        
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
        
        // Throttled resize handler
        const debouncedResize = debounce(function() {
            const containerHeight = approachContainer.clientHeight;
            pages.forEach(page => {
                page.style.minHeight = containerHeight + 'px';
                page.style.height = containerHeight + 'px';
            });
            requestAnimationFrame(updateProgress);
        }, 150);
        
        window.addEventListener('resize', debouncedResize, { passive: true });
        
        // Mobile navigation arrows
        const prevButton = approachSection.querySelector('.approach-prev');
        const nextButton = approachSection.querySelector('.approach-next');
        
        function updateArrowStates() {
            if (!prevButton || !nextButton) return;
            
            const currentScroll = approachContainer.scrollTop;
            const maxScroll = approachContainer.scrollHeight - approachContainer.clientHeight;
            const scrollThreshold = 5;
            
            if (currentScroll <= scrollThreshold) {
                prevButton.classList.add('disabled');
            } else {
                prevButton.classList.remove('disabled');
            }
            
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
            
            approachContainer.addEventListener('scroll', updateArrowStates, { passive: true });
            updateArrowStates();
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initApproach);
} else {
    initApproach();
}
