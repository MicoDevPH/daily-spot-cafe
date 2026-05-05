<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Categories Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <link href="https://quilljs.com" rel="stylesheet">
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
                    <a href="dashboard.php" class="list-group-item list-group-item-action">
                        <i class="nav-icon bi bi-house-door-fill"></i>
                        Dashboard
                    </a>
                    <a href="orders.php" class="list-group-item list-group-item-action">
                        <i class="nav-icon bi bi-box-seam-fill"></i>
                        Orders
                    </a>
                    <div id="products-toggle" class="list-group-item list-group-item-action active" 
                        data-bs-toggle="collapse" 
                        data-bs-target="#productsSubmenu" 
                        aria-expanded="true" 
                        style="cursor: pointer;">
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <span>
                                <i class="nav-icon bi bi-bag-fill me-2"></i>
                                Products
                            </span>
                            <i id="products-arrow" class="bi bi-chevron-down small"></i>
                        </div>
                    </div>

                    <!-- Submenu Links -->
                    <div class="collapse show" id="productsSubmenu">
                        <div class="list-group list-group-flush ps-4">
                            <a href="inventory.php" class="list-group-item list-group-item-action border-0 py-2 small">
                                <i class="bi bi-archive"></i>
                                Inventory
                            </a>
                            <a href="categories.php" class="list-group-item list-group-item-action border-0 py-2 small active">
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
                    <h1 class="h5 mb-1 fw-bold">Categories
                        <span class="dashboard-tooltip ms-2">
                            <i class="bi bi-question-circle-fill text-secondary" aria-hidden="true"></i>
                            <span class="dashboard-tooltip-text" role="tooltip">
                                Group your products into collections like 'Coffee' or 'Non-Coffee' to organize your menu and simplify customer navigation.</span>
                        </span>
                    </h1>
                </div>
            </div>

            <div class="dashboard-card p-4 mt-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-3">
                    <div class="input-group" style="max-width: 400px;">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" 
                            id="inventorySearch" 
                            class="form-control border-start-0 ps-0" 
                            placeholder="Search...">
                    </div>
                    
                    <button class="btn btn-primary btn-sm px-4" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="bi bi-plus-lg me-1"></i> Add Category
                    </button>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Category ID</th>
                                <th>Category Name</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Row 1 -->
                            <tr>
                                <td>1</td>
                                <td>Coffee</td>
                                <td>A collection of all coffee-based beverages.</td>
                                <td><span class="badge bg-success-subtle text-success">Active</span></td>
                                <td>2024-06-01</td>
                                <td class="text-end">
                                    <button class="btn btn-light btn-sm"><i class="bi bi-pencil"></i></button>
                                    <button class="btn btn-light btn-sm text-danger"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            </main>
        </div>
    </div>
    
    <!-- ADD CATEGORY MODAL -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="addCategoryForm">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category Name*</label>
                        <input type="text" class="form-control bg-light border-0" placeholder="Enter category name" required>
                    </div>
                    

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category Description*</label>
                         <span class="dashboard-tooltip ms-2">
                            <i class="bi bi-question-circle-fill text-secondary" aria-hidden="true"></i>
                            <span class="dashboard-tooltip-text" role="tooltip">
                                A brief description of the category.</span>
                        </span>
                        <input type="text" class="form-control bg-light border-0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Category Image*</label>
                        <span class="dashboard-tooltip ms-2">
                            <i class="bi bi-question-circle-fill text-secondary" aria-hidden="true"></i>
                            <span class="dashboard-tooltip-text" role="tooltip">
                                Upload an image for the category.</span>
                        </span>
                        <div class="d-flex align-items-center gap-3">
                            <!-- Preview Box -->
                            <div id="image-preview" class="rounded bg-light d-flex align-items-center justify-content-center border" style="width: 80px; height: 80px; overflow: hidden;">
                                <i class="bi bi-image text-muted fs-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <input type="file" class="form-control form-control-sm" id="productImage" accept="image/*" required>
                                <small class="text-muted">Recommended: Square image (500x500px)</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0 text-end pt-3">
                        <button type="button" class="btn btn-light px-4 me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    <script src="../src/assets/javascript/admin/left-side-bar.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-8DBwxvghb+f8w824cDtgFXtW+eLk+ifaFVIJ9ai0SyxgbpPzJblwXERQ8GHKq2ya" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://quilljs.com"></script>
</body>
</html>