document.addEventListener('DOMContentLoaded', function () {
    const productsSubmenu = document.getElementById('productsSubmenu');
    const arrowIcon = document.getElementById('products-arrow');
    const toggle = document.getElementById('products-toggle');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const dashboardApp = document.querySelector('.dashboard-app');

    // Check if elements exist to avoid errors on other pages
    if (productsSubmenu && arrowIcon && toggle) {
        const isInitiallyActive = toggle.classList.contains('active');

        toggle.addEventListener('click', function (e) {
            // If sidebar is collapsed, clicking the icon takes you directly to inventory
            if (document.documentElement.classList.contains('sidebar-collapsed')) {
                window.location.href = 'inventory.php';
                return;
            }
            // Always allow toggling the submenu by click
            if (window.bootstrap && bootstrap.Collapse) {
                const bsCollapse = bootstrap.Collapse.getInstance(productsSubmenu) || new bootstrap.Collapse(productsSubmenu, { toggle: false });
                bsCollapse.toggle();
            }
        });

        // Hover functionality
        let hideTimeout;

        const handleMouseEnter = () => {
            clearTimeout(hideTimeout);
            if (!document.documentElement.classList.contains('sidebar-collapsed')) {
                // Expanded mode: Show collapse
                if (window.bootstrap && bootstrap.Collapse) {
                    const bsCollapse = bootstrap.Collapse.getInstance(productsSubmenu) || new bootstrap.Collapse(productsSubmenu, { toggle: false });
                    bsCollapse.show();
                }
            } else {
                // Collapsed mode: Highlight toggle AND position/show submenu
                toggle.classList.add('active');

                const rect = toggle.getBoundingClientRect();

                productsSubmenu.style.cssText = `
                    position: fixed !important;
                    left: ${rect.right}px !important;
                    top: ${rect.top}px !important;
                    opacity: 1 !important;
                    visibility: visible !important;
                    pointer-events: auto !important;
                    transform: translateX(0) !important;
                    z-index: 9999 !important;
                    min-width: 220px !important;
                    background: #3D2B1F !important;
                    border: 1px solid rgba(255, 255, 255, 0.1) !important;
                    border-radius: 0 0.85rem 0.85rem 0 !important;
                    box-shadow: 15px 10px 30px rgba(0, 0, 0, 0.2) !important;
                    transition: opacity 0.2s ease, transform 0.2s ease !important;
                `;
            }
        };

        const handleMouseLeave = () => {
            if (!document.documentElement.classList.contains('sidebar-collapsed')) {
                // Expanded mode: do nothing on mouseleave — let click handle toggle
            } else {
                // Collapsed mode: Remove highlight and hide submenu
                if (!isInitiallyActive) {
                    toggle.classList.remove('active');
                }
                hideTimeout = setTimeout(() => {
                    productsSubmenu.style.opacity = '0';
                    productsSubmenu.style.transform = 'translateX(-10px)';
                    productsSubmenu.style.visibility = 'hidden';
                    productsSubmenu.style.pointerEvents = 'none';

                    // Clear all inline styles after animation finishes
                    setTimeout(() => {
                        if (productsSubmenu.style.opacity === '0') {
                            productsSubmenu.style.cssText = '';
                        }
                    }, 200);
                }, 100);
            }
        };

        toggle.addEventListener('mouseenter', handleMouseEnter);
        toggle.addEventListener('mouseleave', handleMouseLeave);
        productsSubmenu.addEventListener('mouseenter', handleMouseEnter);
        productsSubmenu.addEventListener('mouseleave', handleMouseLeave);

        // When the menu starts to open
        productsSubmenu.addEventListener('show.bs.collapse', function () {
            arrowIcon.classList.replace('bi-chevron-right', 'bi-chevron-down');
            toggle.classList.add('active');
        });

        // When the menu starts to close
        productsSubmenu.addEventListener('hide.bs.collapse', function () {
            arrowIcon.classList.replace('bi-chevron-down', 'bi-chevron-right');
            toggle.classList.remove('active');
        });
    }

    if (sidebarToggle) {
        // Retrieve state from localStorage or check if class already exists (from head script)
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true' || document.documentElement.classList.contains('sidebar-collapsed');

        // Apply state on load to sync UI components
        if (isCollapsed) {
            document.documentElement.classList.add('sidebar-collapsed');
            sidebarToggle.classList.remove('bi-layout-sidebar-inset');
            sidebarToggle.classList.add('bi-layout-sidebar-reverse');
        } else {
            document.documentElement.classList.remove('sidebar-collapsed');
            sidebarToggle.classList.add('bi-layout-sidebar-inset');
            sidebarToggle.classList.remove('bi-layout-sidebar-reverse');
        }

        sidebarToggle.addEventListener('click', function () {
            const nowCollapsed = document.documentElement.classList.toggle('sidebar-collapsed');

            if (nowCollapsed) {
                sidebarToggle.classList.remove('bi-layout-sidebar-inset');
                sidebarToggle.classList.add('bi-layout-sidebar-reverse');
                localStorage.setItem('sidebarCollapsed', 'true');
            } else {
                sidebarToggle.classList.add('bi-layout-sidebar-inset');
                sidebarToggle.classList.remove('bi-layout-sidebar-reverse');
                localStorage.setItem('sidebarCollapsed', 'false');
            }
        });
    }
});
