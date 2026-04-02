// AJAX Navigation System
document.addEventListener('DOMContentLoaded', function() {
    // Intercept all navigation link clicks
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[data-ajax="true"]');
        
        if (link && !link.target) {
            e.preventDefault();
            loadContentViaAjax(link.href);
        }
    });

    // Load content via AJAX
    function loadContentViaAjax(url) {
        const contentArea = document.getElementById('ajax-content');
        if (!contentArea) return;

        // Show loading state
        contentArea.style.opacity = '0.5';
        contentArea.style.pointerEvents = 'none';

        // Add query param to indicate AJAX request
        const ajaxUrl = new URL(url);
        ajaxUrl.searchParams.set('ajax', '1');

        fetch(ajaxUrl.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.text();
        })
        .then(html => {
            // Update content
            contentArea.innerHTML = html;
            contentArea.style.opacity = '1';
            contentArea.style.pointerEvents = 'auto';

            // Update URL
            window.history.pushState({ url: url }, '', url);

            // Update active menu item
            updateActiveMenu(url);

            // Scroll to top
            window.scrollTo(0, 0);

            // Trigger any necessary re-initialization of scripts
            if (window.reinitializePageScripts) {
                window.reinitializePageScripts();
            }
        })
        .catch(error => {
            console.error('Error loading content:', error);
            contentArea.style.opacity = '1';
            contentArea.style.pointerEvents = 'auto';
            contentArea.innerHTML = '<div class="p-6 bg-red-50 border border-red-200 rounded-lg"><p class="text-red-600">Error loading content. Please try again.</p></div>';
        });
    }

    // Update active menu highlighting
    function updateActiveMenu(url) {
        // Remove active class from all menu items
        document.querySelectorAll('.menu-item').forEach(item => {
            item.classList.remove('active');
            item.removeAttribute('aria-current');
        });

        // Find and highlight the matching link
        document.querySelectorAll('a[data-ajax="true"]').forEach(link => {
            if (link.href === url) {
                link.classList.add('active');
                link.setAttribute('aria-current', 'page');

                // Auto-expand parent submenu if necessary
                const submenu = link.closest('[id$="Submenu"]');
                if (submenu && submenu.classList.contains('hidden')) {
                    submenu.classList.remove('hidden');
                    const icon = submenu.previousElementSibling?.querySelector('.fa-chevron-down');
                    if (icon) icon.style.transform = 'rotate(180deg)';
                }
            }
        });
    }

    // Handle browser back/forward
    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.url) {
            loadContentViaAjax(e.state.url, true);
        }
    });
});
