document.addEventListener('DOMContentLoaded', function () {
    const carItems = document.querySelectorAll('.car-item');
    const layoutGridAutoFit = document.querySelector('.layoutGridAutoFit');
    carItems.length > 1 ? layoutGridAutoFit.classList.add('multiple-car-items') : null;
});
