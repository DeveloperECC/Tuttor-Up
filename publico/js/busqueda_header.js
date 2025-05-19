// publico/js/busqueda_header.js
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');

    function realizarBusqueda() {
        if (!searchInput) return; // Salir si el input no existe en la página
        const termino = searchInput.value.trim();
        if (termino) {
            // Asegurarse de que BASE_URL está definido globalmente
            if (typeof BASE_URL === 'undefined') {
                console.error('BASE_URL no está definido. La búsqueda no funcionará.');
                return;
            }
            window.location.href = `${BASE_URL}/materias/buscar?q=${encodeURIComponent(termino)}`;
        } else {
            // Opcional: mostrar un pequeño feedback si no hay término
            // searchInput.placeholder = "Escribe algo para buscar...";
        }
    }

    if (searchButton) {
        searchButton.addEventListener('click', realizarBusqueda);
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevenir submit de formulario si el input estuviera en uno
                realizarBusqueda();
            }
        });
    }

    // --- Funcionalidad para otros botones del header ---
    const notificationsButton = document.getElementById('notificationsButton'); // Asumo que este ID podría existir
    const userProfileButton = document.getElementById('userProfileButton');
    const arButton = document.getElementById('arButton'); // Asumo que este ID podría existir

    // Ejemplo para el botón de notificaciones (si existe)
    notificationsButton?.addEventListener('click', (e) => {
        e.preventDefault();
        // Ejemplo: Redirigir a la página de notificaciones
        // window.location.href = `${BASE_URL}/notificaciones`; 
        alert('Funcionalidad de Notificaciones (Pendiente)');
    });

});