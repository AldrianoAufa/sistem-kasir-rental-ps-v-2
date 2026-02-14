/**
 * Sidebar Toggle Script
 * Handles hamburger menu for mobile and desktop sidebar toggling
 */

(function() {
    'use strict';

    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        // Get toggle buttons
        var sidebarToggleTop = document.getElementById('sidebarToggleTop');
        var sidebarToggle = document.getElementById('sidebarToggle');
        var sidebar = document.querySelector('.sidebar');
        var body = document.body;

        // Debug: Log element detection
        console.log('Sidebar elements check:');
        console.log('- sidebarToggleTop:', sidebarToggleTop ? 'Found' : 'NOT FOUND');
        console.log('- sidebarToggle:', sidebarToggle ? 'Found' : 'NOT FOUND');
        console.log('- sidebar:', sidebar ? 'Found' : 'NOT FOUND');
        console.log('- body:', body ? 'Found' : 'NOT FOUND');

        // Toggle sidebar function
        function toggleSidebar(e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            body.classList.toggle('sidebar-toggled');
            
            if (sidebar) {
                sidebar.classList.toggle('toggled');
                
                // Debug log
                console.log('Sidebar toggled:', sidebar.classList.contains('toggled') ? 'HIDDEN' : 'VISIBLE');
                
                // Close any open collapse menus when sidebar is toggled
                if (sidebar.classList.contains('toggled')) {
                    var collapseElements = sidebar.querySelectorAll('.collapse.show');
                    collapseElements.forEach(function(el) {
                        el.classList.remove('show');
                    });
                }
            }
        }

        // Attach event listener to mobile hamburger button
        if (sidebarToggleTop) {
            sidebarToggleTop.addEventListener('click', toggleSidebar);
        }

        // Attach event listener to desktop sidebar toggle button
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', toggleSidebar);
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768) {
                var isClickInsideSidebar = sidebar && sidebar.contains(e.target);
                var isClickOnToggle = (sidebarToggleTop && sidebarToggleTop.contains(e.target)) || 
                                       (sidebarToggle && sidebarToggle.contains(e.target));
                
                if (!isClickInsideSidebar && !isClickOnToggle && sidebar && !sidebar.classList.contains('toggled')) {
                    body.classList.add('sidebar-toggled');
                    sidebar.classList.add('toggled');
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth < 768) {
                // Close any open collapse menus on mobile
                if (sidebar) {
                    var collapseElements = sidebar.querySelectorAll('.collapse.show');
                    collapseElements.forEach(function(el) {
                        el.classList.remove('show');
                    });
                }
            }
            
            // Auto-hide sidebar on very small screens
            if (window.innerWidth < 480 && sidebar && !sidebar.classList.contains('toggled')) {
                body.classList.add('sidebar-toggled');
                sidebar.classList.add('toggled');
            }
        });

        // Auto-hide sidebar on mobile on initial load
        if (window.innerWidth < 768 && sidebar && !sidebar.classList.contains('toggled')) {
            body.classList.add('sidebar-toggled');
            sidebar.classList.add('toggled');
        }

        console.log('Sidebar toggle script loaded successfully');
    });
})();
