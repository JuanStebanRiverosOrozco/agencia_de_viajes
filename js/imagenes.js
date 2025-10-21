// js/imagenes.js

// Función para cambiar la imagen principal - EXPONERLA GLOBALMENTE
window.changeImage = function(newSrc, thumbnail) {
    const mainImage = document.getElementById('mainImage');
    
    if (!mainImage) return;
    
    // Fade out
    mainImage.style.opacity = '0';
    
    // Cambiar imagen después de la transición
    setTimeout(() => {
        mainImage.src = newSrc;
        mainImage.style.opacity = '1';
    }, 300);
    
    // Remover clase active de todas las miniaturas
    const thumbnails = document.querySelectorAll('.thumbnail');
    thumbnails.forEach(thumb => {
        thumb.classList.remove('active');
    });
    
    // Agregar clase active a la miniatura clickeada
    if (thumbnail) {
        thumbnail.classList.add('active');
    }
};

// Manejo de errores de imágenes
document.addEventListener('DOMContentLoaded', function() {
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail');
    
    // Error handler para imagen principal
    if (mainImage) {
        mainImage.addEventListener('error', function() {
            this.src = 'https://via.placeholder.com/800x600?text=Sin+imagen';
        });
    }
    
    // Error handler para miniaturas
    thumbnails.forEach(thumb => {
        thumb.addEventListener('error', function() {
            this.src = 'https://via.placeholder.com/200x150?text=Sin+imagen';
        });
    });
});