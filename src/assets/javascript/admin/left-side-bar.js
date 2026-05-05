document.addEventListener('DOMContentLoaded', function() {
    const productsSubmenu = document.getElementById('productsSubmenu');
    const arrowIcon = document.getElementById('products-arrow');
    const toggle = document.getElementById('products-toggle');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const dashboardApp = document.querySelector('.dashboard-app');

    // Check if elements exist to avoid errors on other pages
    if (productsSubmenu && arrowIcon && toggle) {
        
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

    if (sidebarToggle && dashboardApp) {
        sidebarToggle.addEventListener('click', function () {
            dashboardApp.classList.toggle('sidebar-collapsed');
            sidebarToggle.classList.toggle('bi-layout-sidebar-inset');
            sidebarToggle.classList.toggle('bi-layout-sidebar-reverse');
        });
    }
});
