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
