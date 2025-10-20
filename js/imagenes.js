function changeImage(src, element) {
    // Cambiar imagen principal
    const mainImg = document.getElementById('mainImage');
    mainImg.style.opacity = '0';

    setTimeout(() => {
        mainImg.src = src;
        mainImg.style.opacity = '1';
    }, 150);

    // Actualizar clase active en miniaturas
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}