// publico/js/busqueda_header.js
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');

    function realizarBusqueda() {
        const termino = searchInput.value.trim();
        if (termino) {
            // Asumimos que BASE_URL está definido globalmente (desde layout_principal.php)
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
                e.preventDefault(); // Prevenir submit de formulario si lo hubiera
                realizarBusqueda();
            }
        });
    }

    // Funcionalidad placeholder para otros botones del header
    const notificationsButton = document.getElementById('notificationsButton');
    const userProfileButton = document.getElementById('userProfileButton');
    const arButton = document.getElementById('arButton');

    notificationsButton?.addEventListener('click', () => alert('Funcionalidad de Notificaciones (Pendiente)'));
    userProfileButton?.addEventListener('click', () => alert('Funcionalidad de Perfil de Usuario (Pendiente)'));
    arButton?.addEventListener('click', (e) => {
        e.preventDefault();
        alert('Funcionalidad de Realidad Aumentada (Pendiente)');
    });
});