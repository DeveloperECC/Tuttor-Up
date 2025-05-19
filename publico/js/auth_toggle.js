// publico/js/auth_toggle.js
document.addEventListener('DOMContentLoaded', function() {
    const registroFormContainer = document.getElementById('registroFormContainer');
    const loginFormContainer = document.getElementById('loginFormContainer');
    const showLoginLink = document.getElementById('showLoginLink');
    const showRegisterLink = document.getElementById('showRegisterLink');

    function toggleForms(showLogin) {
        if (showLogin) {
            if (registroFormContainer) registroFormContainer.classList.remove('form-active');
            if (registroFormContainer) registroFormContainer.classList.add('form-inactive');
            if (loginFormContainer) loginFormContainer.classList.remove('form-inactive');
            if (loginFormContainer) loginFormContainer.classList.add('form-active');
        } else {
            if (loginFormContainer) loginFormContainer.classList.remove('form-active');
            if (loginFormContainer) loginFormContainer.classList.add('form-inactive');
            if (registroFormContainer) registroFormContainer.classList.remove('form-inactive');
            if (registroFormContainer) registroFormContainer.classList.add('form-active');
        }
        // Limpiar parámetros de URL 'success' si existen, para no mostrar el mensaje de éxito repetidamente
        if (window.location.search.includes('success=1')) {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    }

    showLoginLink?.addEventListener('click', function(e) {
        e.preventDefault();
        toggleForms(true);
    });

    showRegisterLink?.addEventListener('click', function(e) {
        e.preventDefault();
        toggleForms(false);
    });

    // Determinar qué formulario mostrar inicialmente basado en si hay errores o éxito
    // Esta lógica ya está manejada por las clases 'form-active'/'form-inactive' en el PHP de la vista.
    // Pero si vienes de un error de registro, podrías querer mostrar el registro, etc.
    // Esto se maneja en la vista PHP con $showLogin y $showRegister
});