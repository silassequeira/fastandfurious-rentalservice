document.getElementById('foto').addEventListener('change', function (event) {
    const previewContainer = document.getElementById('preview-container');
    const errorMessage = document.getElementById('error-message');
    const file = event.target.files[0];
    const allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
    const maxSize = 2 * 1024 * 1024; // 2MB

    // Clear previous content
    previewContainer.innerHTML = '';
    errorMessage.textContent = '';

    if (file) {
        if (!allowedTypes.includes(file.type)) {
            errorMessage.textContent = 'Only PNG, JPG, and JPEG files are allowed.';
            return;
        }

        if (file.size > maxSize) {
            errorMessage.textContent = 'File size must be less than 2MB.';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    } else {
        previewContainer.innerHTML = '<p>No image selected</p>';
    }
});