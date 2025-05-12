// publico/js/menu_lateral.js
document.addEventListener('DOMContentLoaded', function() {
    const menuLateral = document.querySelector('.menu-lateral');
    const itemsConSubmenu = document.querySelectorAll('.menu-lateral__item.has-submenu');
    const botonMenuMovil = document.querySelector('.boton-menu-movil'); // Asumiendo que lo añades al layout_principal.php si no existe

    // Crear botón de menú móvil si no existe en el HTML
    const todosLosEnlacesDelMenu= document.querySelectorAll('.menu-lateral__lista .menu-lateral__enlace');
    todosLosEnlacesDelMenu.forEach(enlace => {
        enlace.addEventListener('click', function(event_click_enlace) {
            const itemPadreActual = this.closest('.menu-lateral__item');
            const esSubmenuTriggerActual = itemPadreActual && itemPadreActual.classList.contains('has-submenu');
            const submenuAbierto = itemPadreActual && itemPadreActual.classList.contains('submenu-abierto');
            if (esSubmenuTriggerActual && submenuAbierto) {
                event_click_enlace.preventDefault(); // Prevenir el comportamiento por defecto
                itemPadreActual.classList.remove('submenu-abierto'); // Cerrar el submenú
            }


            // Si el menú está abierto y se hace clic en un enlace, cerrarlo
            if (menuLateral.classList.contains('menu-abierto-js') && !esSubmenuTriggerActual) {
                menuLateral.classList.remove('menu-abierto-js');
                actualizarIconoBotonMovil(false);
                if (botonMenuMovil) botonMenuMovil.setAttribute('aria-label', 'Abrir menú');
                itemsConSubmenu.forEach(item => item.classList.remove('submenu-abierto'));
            }
        }
        );
    });

    let btnMenuMovilExistente = document.querySelector('.boton-menu-movil');
    if (!btnMenuMovilExistente) {
        const nuevoBoton = document.createElement('button');
        nuevoBoton.className = 'boton-menu-movil';
        nuevoBoton.setAttribute('aria-label', 'Abrir menú');
        nuevoBoton.innerHTML = '<i class="fas fa-bars"></i>';
        document.body.insertBefore(nuevoBoton, document.body.firstChild); // Insertar al inicio del body
        btnMenuMovilExistente = nuevoBoton;
    }


    // Abrir/cerrar submenús
    itemsConSubmenu.forEach(item => {
        const enlacePrincipal = item.querySelector('.menu-lateral__enlace');
        if (enlacePrincipal) {
            enlacePrincipal.addEventListener('click', function(event) {
                // Si el enlace tiene una URL real (no solo '#') y el menú no está expandido por hover,
                // permite la navegación. Si no, previene y gestiona el submenú.
                const esSoloAncla = this.getAttribute('href') === '#' || this.getAttribute('href') === '';
                const menuEstaHover = menuLateral.matches(':hover');
                const menuEstaAbiertoJs = menuLateral.classList.contains('menu-abierto-js');

                // Si tiene URL y el menú está expandido (hover o JS), no prevenir si no es ancla
                if (!esSoloAncla && (menuEstaHover || menuEstaAbiertoJs)) {
                    // No prevenir el comportamiento por defecto si es un enlace real y el menú está expandido
                } else {
                     event.preventDefault(); // Prevenir para anclas o cuando el menú está colapsado
                }
                
                // Si el menú está colapsado y se hace clic, se expande el menú completo primero (modo móvil)
                if (!menuEstaHover && !menuEstaAbiertoJs && window.innerWidth <= 768) {
                    menuLateral.classList.add('menu-abierto-js');
                    actualizarIconoBotonMovil(true);
                }
                
                // Luego, o si ya está expandido, se maneja el submenú
                const padre = this.closest('.menu-lateral__item.has-submenu');
                if (padre) {
                    padre.classList.toggle('submenu-abierto');
                }
            });
        }
    });

    // Botón de menú móvil
    if (btnMenuMovilExistente) {
        btnMenuMovilExistente.addEventListener('click', function(e) {
            e.stopPropagation();
            const menuAbierto = menuLateral.classList.toggle('menu-abierto-js');
            actualizarIconoBotonMovil(menuAbierto);
            if (menuAbierto) {
                this.setAttribute('aria-label', 'Cerrar menú');
            } else {
                this.setAttribute('aria-label', 'Abrir menú');
                 // Opcional: cerrar submenús al cerrar el menú principal
                itemsConSubmenu.forEach(item => item.classList.remove('submenu-abierto'));
            }
        });
    }
    
    function actualizarIconoBotonMovil(menuAbierto) {
        if (btnMenuMovilExistente) {
            btnMenuMovilExistente.innerHTML = menuAbierto ? '<i class="fas fa-times"></i>' : '<i class="fas fa-bars"></i>';
        }
    }

    // Cerrar menú móvil al hacer clic fuera
    document.addEventListener('click', function(event) {
        if (menuLateral.classList.contains('menu-abierto-js') && 
            !menuLateral.contains(event.target) && 
            (!btnMenuMovilExistente || !btnMenuMovilExistente.contains(event.target))) {
            menuLateral.classList.remove('menu-abierto-js');
            actualizarIconoBotonMovil(false);
            if (btnMenuMovilExistente) btnMenuMovilExistente.setAttribute('aria-label', 'Abrir menú');
            itemsConSubmenu.forEach(item => item.classList.remove('submenu-abierto'));
        }
    });

    // Mantener submenú activo si la página actual está en él
    // (Esto ya se maneja en PHP añadiendo la clase 'submenu-abierto' al <li>)
});