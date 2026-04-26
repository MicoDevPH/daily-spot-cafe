const productImage = document.getElementById('productImage');
const imagePreview = document.getElementById('image-preview');

if (productImage) {
    productImage.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            }
            reader.readAsDataURL(file);
        } else {
            imagePreview.innerHTML = `<i class="bi bi-image text-muted fs-3"></i>`;
        }
    });
}
// Wait for page to load
document.addEventListener('DOMContentLoaded', function() {
    
    // Get the form
    const addProductForm = document.getElementById('addProductForm');
    const productImage = document.getElementById('productImage');
    const imagePreview = document.getElementById('image-preview');
    
    // ========================================
    // IMAGE PREVIEW (Show image before upload)
    // ========================================
    productImage.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Create a URL for the image
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Show the image
                imagePreview.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
            };
            
            reader.readAsDataURL(file);
        }
    });
    
    // ========================================
    // FORM SUBMIT (Add New Product)
    // ========================================
    addProductForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Stop normal form submission
        
        // Get Quill editor content (rich text)
        const longDescription = quill.root.innerHTML;
        
        // Create FormData (needed for file upload)
        const formData = new FormData();
        
        // Add all form fields
        formData.append('action', 'create');
        formData.append('product_name', document.querySelector('input[placeholder="Enter product name"]').value);
        formData.append('category_id', document.querySelector('select').value);
        formData.append('price', document.querySelector('input[placeholder="0.00"]').value);
        formData.append('short_description', document.querySelector('input[type="text"].form-control.bg-light.border-0').value);
        formData.append('long_description', longDescription);
        formData.append('featured_image', productImage.files[0]); // The actual file
        
        // Send to server
        fetch('api/products.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Success! Show message
                alert('✅ ' + data.message);
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('addProductModal'));
                modal.hide();
                
                // Reload products table
                loadProducts();
                
                // Reset form
                addProductForm.reset();
                imagePreview.innerHTML = '<i class="bi bi-image text-muted fs-3"></i>';
            } else {
                // Error! Show message
                alert('❌ ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('❌ Something went wrong!');
        });
    });
    
    // ========================================
    // LOAD PRODUCTS (Show in table)
    // ========================================
    function loadProducts() {
        fetch('api/products.php?action=read')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayProducts(data.data);
                }
            })
            .catch(error => console.error('Error loading products:', error));
    }
    
    // ========================================
    // DISPLAY PRODUCTS (Fill the table)
    // ========================================
    function displayProducts(products) {
        const tbody = document.querySelector('tbody');
        tbody.innerHTML = ''; // Clear existing rows
        
        products.forEach(product => {
            const row = `
                <tr>
                    <td>${product.product_id}</td>
                    <td>${product.product_name}</td>
                    <td>${product.category_name}</td>
                    <td>₱${parseFloat(product.price).toFixed(2)}</td>
                    <td>
                        <span class="badge ${product.is_published ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}">
                            ${product.is_published ? 'Published' : 'Draft'}
                        </span>
                    </td>
                    <td>${new Date(product.created_at).toLocaleDateString()}</td>
                    <td class="text-end">
                        <button class="btn btn-light btn-sm" onclick="editProduct(${product.product_id})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-light btn-sm text-danger" onclick="deleteProduct(${product.product_id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    }
    
    // Load products when page loads
    loadProducts();
});

// ========================================
// DELETE PRODUCT
// ========================================
function deleteProduct(id) {
    if (confirm('Are you sure you want to delete this product?')) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('product_id', id);
        
        fetch('api/products.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('✅ Product deleted!');
                location.reload(); // Refresh page
            } else {
                alert('❌ ' + data.message);
            }
        });
    }
}