const sidebar = document.getElementById('sidebar');
const toggleBtn = document.getElementById('toggleBtn');
const sidebarTitle = document.getElementById('sidebar-title');
const navTexts = document.querySelectorAll('.nav-text');
const tooltips = document.querySelectorAll('.tooltip');
let open = false;

// Sidebar cerrado al inicio
sidebar.classList.remove('w-64');
sidebar.classList.add('w-20');
sidebarTitle.classList.add('hidden');
navTexts.forEach(t => t.classList.add('hidden'));

toggleBtn.addEventListener('click', () => {
    open = !open;   
    if (open) {
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-64');
        // Ocultar tooltips cuando está abierto
        tooltips.forEach(t => t.classList.add('hidden'));
        setTimeout(() => {
            sidebarTitle.classList.remove('hidden');
            navTexts.forEach(t => t.classList.remove('hidden'));
        }, 150);
    } else {
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');
        sidebarTitle.classList.add('hidden');
        navTexts.forEach(t => t.classList.add('hidden'));
        // Mostrar tooltips nuevamente cuando se cierra
        setTimeout(() => {
            tooltips.forEach(t => t.classList.remove('hidden'));
        }, 150);
    }
});


