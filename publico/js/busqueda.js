document.addEventListener('DOMContentLoaded', function() {
    const busquedaInput = document.querySelector('.busqueda-input');
    
    busquedaInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const termino = busquedaInput.value.trim();
            if (termino) {
                window.location.href = `${BASE_URL}/materias?accion=buscar&q=${encodeURIComponent(termino)}`;
            }
        }
    });
});