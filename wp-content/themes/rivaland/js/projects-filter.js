// Projects Filter Functionality
document.addEventListener('DOMContentLoaded', function() {
    const filterInput = document.getElementById('project-filter-input');
    const clearButton = document.getElementById('project-filter-clear');
    const filterButtons = document.querySelectorAll('.projects-index__filter');
    const projectCards = document.querySelectorAll('.project-card--index');
    const dropdownToggle = document.getElementById('project-filter-dropdown-toggle');
    const dropdown = document.getElementById('project-filter-dropdown');
    const dropdownItems = document.querySelectorAll('.projects-index__filter-dropdown-item');
    const clearMobileButton = document.getElementById('project-filter-clear-mobile');

    let currentFilters = []; // Track multiple filter types (array for multi-selection)

    // Don't return early - allow filters to work even if input/clear button don't exist
    // These are optional elements for desktop search functionality

    // Show/hide clear button based on input value
    function toggleClearButton() {
        if (clearButton && filterInput) {
            if (filterInput.value.length > 0) {
                clearButton.classList.add('visible');
            } else {
                clearButton.classList.remove('visible');
            }
        }
    }

    // Filter projects based on type and search term
    function filterProjects() {
        const searchTerm = filterInput ? filterInput.value.toLowerCase().trim() : '';
        
        console.log('filterProjects called - currentFilters:', currentFilters, 'searchTerm:', searchTerm);
        console.log('Total project cards:', projectCards.length);
        
        projectCards.forEach((card, index) => {
            const meta = card.querySelector('.project-card__meta');
            const location = card.querySelector('.project-card__location');
            
            if (!meta || !location) {
                // If meta or location is missing, hide the card
                card.style.display = 'none';
                console.log(`Card ${index}: Missing meta or location, hiding`);
                return;
            }
            
            const metaText = meta.textContent.toLowerCase();
            const locationText = location.textContent.toLowerCase();
            const cardText = metaText + ' ' + locationText;
            
            // Check type filter (multi-selection: match if any selected filter matches)
            let matchesType = true;
            if (currentFilters.length > 0) {
                // Check if any of the selected filters match the meta text
                matchesType = currentFilters.some(filter => {
                    const filterLower = filter.toLowerCase();
                    // Check if meta text contains the filter type (e.g., "residential", "commercial", "mixed use")
                    const matches = metaText.includes(filterLower);
                    console.log(`Card ${index}: Checking filter "${filterLower}" against meta "${metaText}" - matches: ${matches}`);
                    return matches;
                });
            }
            
            // Check search term
            let matchesSearch = true;
            if (searchTerm) {
                matchesSearch = cardText.includes(searchTerm);
            }
            
            // Show card if it matches both filters
            const shouldShow = matchesType && matchesSearch;
            console.log(`Card ${index}: matchesType=${matchesType}, matchesSearch=${matchesSearch}, shouldShow=${shouldShow}`);
            
            if (shouldShow) {
                // Remove the display style to restore default (grid on desktop, flex on mobile)
                card.style.removeProperty('display');
            } else {
                // Hide the card
                card.style.setProperty('display', 'none', 'important');
            }
        });
    }

    // Function to toggle filter (multi-selection support)
    function toggleFilter(filterType, buttonElement) {
        const index = currentFilters.indexOf(filterType);
        
        if (index > -1) {
            // Filter is already selected, remove it
            currentFilters.splice(index, 1);
            if (buttonElement) buttonElement.classList.remove('active');
            // Also remove active from corresponding desktop button
            filterButtons.forEach(btn => {
                if (btn.getAttribute('data-filter') === filterType) {
                    btn.classList.remove('active');
                }
            });
        } else {
            // Filter is not selected, add it
            currentFilters.push(filterType);
            if (buttonElement) buttonElement.classList.add('active');
            // Also set active on corresponding button (desktop/mobile sync)
            filterButtons.forEach(btn => {
                if (btn.getAttribute('data-filter') === filterType) {
                    btn.classList.add('active');
                }
            });
        }
        
        // Update dropdown text based on active filters
        if (dropdownToggle) {
            if (currentFilters.length === 0) {
                dropdownToggle.querySelector('.filter-dropdown-text').textContent = 'Filter';
            } else if (currentFilters.length === 1) {
                const activeItem = Array.from(dropdownItems).find(item => item.getAttribute('data-filter') === currentFilters[0]);
                if (activeItem) {
                    dropdownToggle.querySelector('.filter-dropdown-text').textContent = activeItem.textContent;
                }
            } else {
                dropdownToggle.querySelector('.filter-dropdown-text').textContent = `${currentFilters.length} selected`;
            }
        }
        
        // Show/hide clear button
        updateClearMobileButton();
        
        // Always call filterProjects to update display
        filterProjects();
        
        // Debug: log current filters
        console.log('Current filters:', currentFilters);
    }
    
    // Function to clear all filters
    function clearAllFilters() {
        currentFilters = [];
        filterButtons.forEach(btn => btn.classList.remove('active'));
        dropdownItems.forEach(item => item.classList.remove('active'));
        
        if (dropdownToggle) {
            dropdownToggle.querySelector('.filter-dropdown-text').textContent = 'Filter';
            // Close dropdown when clearing filters
            dropdownToggle.classList.remove('active');
        }
        
        if (dropdown) {
            dropdown.classList.remove('open');
        }
        
        updateClearMobileButton();
        filterProjects();
    }
    
    // Update clear mobile button visibility
    function updateClearMobileButton() {
        if (clearMobileButton) {
            if (currentFilters.length > 0) {
                clearMobileButton.classList.add('visible');
            } else {
                clearMobileButton.classList.remove('visible');
            }
        }
    }

    // Handle filter button clicks (desktop) - single selection for desktop
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            const filterType = this.getAttribute('data-filter');
            // Desktop: single selection (toggle)
            const index = currentFilters.indexOf(filterType);
            if (index > -1) {
                // Already selected, clear all
                clearAllFilters();
            } else {
                // Not selected, set as only filter
                // Clear all filters first
                currentFilters = [];
                filterButtons.forEach(btn => btn.classList.remove('active'));
                dropdownItems.forEach(item => item.classList.remove('active'));
                
                // Then add the new filter
                currentFilters.push(filterType);
                this.classList.add('active');
                // Also set active on corresponding dropdown item
                dropdownItems.forEach(item => {
                    if (item.getAttribute('data-filter') === filterType) {
                        item.classList.add('active');
                    }
                });
                
                // Update dropdown text
                if (dropdownToggle) {
                    const activeItem = Array.from(dropdownItems).find(item => item.getAttribute('data-filter') === filterType);
                    if (activeItem) {
                        dropdownToggle.querySelector('.filter-dropdown-text').textContent = activeItem.textContent;
                    }
                }
                
                // Update clear button and filter
                updateClearMobileButton();
                filterProjects();
            }
        });
    });
    
    // Handle dropdown toggle
    if (dropdownToggle && dropdown) {
        dropdownToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownToggle.classList.toggle('active');
            dropdown.classList.toggle('open');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!dropdownToggle.contains(e.target) && !dropdown.contains(e.target)) {
                dropdownToggle.classList.remove('active');
                dropdown.classList.remove('open');
            }
        });
    }
    
    // Handle dropdown item clicks (mobile) - multi-selection
    dropdownItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            const filterType = this.getAttribute('data-filter');
            toggleFilter(filterType, this); // Multi-selection toggle
            // Keep dropdown open after selection - don't close it
            // User must click outside or toggle button to close
        });
    });
    
    // Handle clear mobile button
    if (clearMobileButton) {
        clearMobileButton.addEventListener('click', function(e) {
            e.stopPropagation(); // Prevent event bubbling
            // Only clear if filters are active
            if (currentFilters.length > 0) {
                clearAllFilters();
            }
        });
    }

    // Clear filter input (only if elements exist)
    if (clearButton && filterInput) {
        clearButton.addEventListener('click', function() {
            filterInput.value = '';
            filterInput.focus();
            toggleClearButton();
            filterProjects();
            maintainInactiveState(); // Ensure cards revert to inactive state
        });
    }

    // Filter on input (only if element exists)
    if (filterInput) {
        filterInput.addEventListener('input', function() {
            toggleClearButton();
            filterProjects();
            maintainInactiveState(); // Ensure cards revert to inactive state
        });
    }

    // Function to force inactive state (white background, default text colors)
    function forceInactiveState(card) {
        // Force white background
        card.style.setProperty('background-color', '#ffffff', 'important');
        card.style.setProperty('background', '#ffffff', 'important');
        
        // Force default text colors (NOT white)
        const meta = card.querySelector('.project-card__meta');
        const status = card.querySelector('.project-status');
        const location = card.querySelector('.project-card__location');
        const arrow = card.querySelector('.project-card__arrow');
        
        if (meta) {
            meta.style.setProperty('color', '#7a8796', 'important'); // $color-text-secondary
        }
        if (status) {
            status.style.setProperty('color', '#7DCAD8', 'important'); // $color-accent-teal
        }
        if (location) {
            location.style.setProperty('color', '#202A44', 'important'); // $color-primary-light
        }
        if (arrow) {
            arrow.style.setProperty('color', '#7a8796', 'important');
        }
    }
    
    // Function to apply active/hover state (green background, white text)
    function applyActiveState(card) {
        // Green background
        card.style.setProperty('background-color', '#02635E', 'important');
        card.style.setProperty('background', '#02635E', 'important');
        
        // White text colors
        const meta = card.querySelector('.project-card__meta');
        const status = card.querySelector('.project-status');
        const location = card.querySelector('.project-card__location');
        const arrow = card.querySelector('.project-card__arrow');
        
        if (meta) {
            meta.style.setProperty('color', 'rgba(255, 255, 255, 0.8)', 'important');
        }
        if (status) {
            status.style.setProperty('color', 'rgba(255, 255, 255, 0.9)', 'important');
        }
        if (location) {
            location.style.setProperty('color', '#ffffff', 'important');
        }
        if (arrow) {
            arrow.style.setProperty('color', '#ffffff', 'important');
        }
    }
    
    // Initialize card states and event handlers
    function initializeCards() {
        projectCards.forEach(card => {
            // Remove any active class
            card.classList.remove('active');
            
            // Force inactive state
            forceInactiveState(card);
            
            // Blur if focused
            if (card === document.activeElement || card.contains(document.activeElement)) {
                document.activeElement.blur();
            }
            
            // Add click handler to toggle active state
            card.addEventListener('click', function(e) {
                const isActive = card.classList.contains('active');
                
                // Remove active from all cards
                projectCards.forEach(c => {
                    c.classList.remove('active');
                    forceInactiveState(c);
                });
                
                // If this card wasn't active, make it active
                if (!isActive) {
                    card.classList.add('active');
                    applyActiveState(card);
                }
            });
            
            // Add hover handlers
            card.addEventListener('mouseenter', function() {
                applyActiveState(card);
            });
            
            card.addEventListener('mouseleave', function() {
                // Only revert to inactive if not clicked (not active)
                if (!card.classList.contains('active')) {
                    forceInactiveState(card);
                }
            });
        });
    }
    
    // Ensure inactive state is maintained (called after operations that might affect styles)
    function maintainInactiveState() {
        projectCards.forEach(card => {
            if (!card.classList.contains('active')) {
                forceInactiveState(card);
            }
        });
    }
    
    // Initialize cards
    initializeCards();
    
    // Initial state
    toggleClearButton();
    filterProjects();
    maintainInactiveState();
    updateClearMobileButton(); // Initialize clear mobile button visibility
    
    // Ensure inactive state when page becomes visible
    document.addEventListener('visibilitychange', function() {
        if (document.visibilityState === 'visible') {
            if (document.activeElement && document.activeElement.classList.contains('project-card--index')) {
                document.activeElement.blur();
            }
            maintainInactiveState();
        }
    });
    
    // Ensure inactive state after DOM operations
    setTimeout(maintainInactiveState, 100);
    requestAnimationFrame(maintainInactiveState);
});


