<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../src/assets/css/admin/admin-dashboard.css" rel="stylesheet">
    <link href="../src/assets/css/admin/left-side-bar.css" rel="stylesheet">
    <link href="../src/assets/css/admin/top-navbar.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-app">
        <aside class="sidebar bg-white shadow-sm">
            <div class="sidebar-brand mb-4">
                <div class="h4 mb-1">Daily Spot Cafe</div>
                <small class="text-muted">Admin panel</small>
            </div>
            <div class="menu-card p-0">
                <div class="list-group sidebar-nav">
                    <a href="dashboard.php" class="list-group-item list-group-item-action active">
                        <i class="nav-icon bi bi-house-door-fill"></i>
                        Dashboard
                    </a>
                    <a href="orders.php" class="list-group-item list-group-item-action">
                        <i class="nav-icon bi bi-box-seam"></i>
                        Orders
                    </a>
                    <div id="products-toggle" class="list-group-item list-group-item-action" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#productsSubmenu" 
                        aria-expanded="false" 
                        style="cursor: pointer;">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span>
                                <i class="nav-icon bi bi-bag-fill me-2"></i>
                                Products
                            </span>
                            <i id="products-arrow" class="bi bi-chevron-right small"></i>
                        </div>
                    </div>

                    <!-- Submenu Links -->
                    <div class="collapse" id="productsSubmenu">
                        <div class="list-group list-group-flush ps-4">
                           <a href="inventory.php" class="list-group-item list-group-item-action border-0 py-2 small">
                                <i class="bi bi-archive"></i>
                                Inventory
                            </a>
                            <a href="categories.php" class="list-group-item list-group-item-action border-0 py-2 small">
                                <i class="bi bi-tags"></i>
                                Categories
                            </a>
                        </div>
                    </div>
                    <a href="contacts.php" class="list-group-item list-group-item-action">
                        <i class="nav-icon bi bi-people-fill"></i>
                        Contacts
                    </a>
                </div>
            </div>
        </aside>

        <div class="dashboard-wrapper">
            <nav class="top-navbar">
                <div class="d-flex align-items-center justify-content-between">
                    <i id="sidebarToggle" class="bi bi-layout-sidebar-inset fs-5" style="cursor: pointer;"></i>
                    <div class="d-flex align-items-center gap-2 top-navbar-actions">
                        <button type="button" class="btn btn-light btn-sm top-navbar-icon">
                            <i class="bi bi-bell"></i>
                        </button>
                        <div class="profile-menu d-flex align-items-center gap-2">
                            <div class="profile-avatar">MK</div>
                            <div class="d-none d-sm-flex flex-column profile-text">
                                <span class="fw-bold">Mico Nakase</span>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="dashboard-main">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
                <div>
                    <h1 class="h5 mb-1 fw-bold">Dashboard
                        <span class="dashboard-tooltip ms-2">
                            <i class="bi bi-question-circle-fill text-secondary" aria-hidden="true"></i>
                            <span class="dashboard-tooltip-text" role="tooltip">
                                View a quick snapshot of your business performance—track sales, top-selling products, website visitor trends, and key reports all in one place.
                            </span>
                        </span>
                    </h1>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary btn-sm">Last 7 Days</button>
                    <button type="button" class="btn btn-primary btn-sm">Add Reports</button>
                </div>
            </div>

            <div class="row gy-4">
                <div class="col-md-4">
                    <div class="dashboard-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="metric-label mb-2">Sales</div>
                                <div class="metric-value">No Data Available</div>
                            </div>
                            <button type="button" class="btn btn-outline-dark btn-sm">View</button>
                        </div>
                        <p class="text-muted mb-0">Last 7 days</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="metric-label mb-2">Orders</div>
                                <div class="metric-value">No Data Available</div>
                            </div>
                            <button type="button" class="btn btn-outline-dark btn-sm">View</button>
                        </div>
                        <p class="text-muted mb-0">Last 7 days</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="dashboard-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="metric-label mb-2">Unfulfilled Orders</div>
                                <div class="metric-value">No Data Available</div>
                            </div>
                            <button type="button" class="btn btn-outline-dark btn-sm">View</button>
                        </div>
                        <p class="text-muted mb-0">Last 7 days</p>
                    </div>
                </div>
            </div>

            <div class="row gy-4 mt-0">
                <div class="col-md-6">
                    <div class="dashboard-card p-3 h-100">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="card-header-sm">Daily Gross Sales</div>
                        </div>
                        <div class="border rounded-3" style="height: 240px; background: linear-gradient(180deg, #f8f9fa, #fff);"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card p-3 h-100">
                        <div class="card-header-sm mb-3">Total Sales</div>
                        <div class="metric-value">No Data Available</div>
                    </div>
                    <div class="dashboard-card p-3 mt-4">
                        <div class="card-header-sm mb-3">Top Selling Products</div>
                        <div class="metric-value">No Data Available</div>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <script src="../src/assets/javascript/admin/left-side-bar.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-8DBwxvghb+f8w824cDtgFXtW+eLk+ifaFVIJ9ai0SyxgbpPzJblwXERQ8GHKq2ya" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>