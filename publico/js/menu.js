
document.addEventListener('DOMContentLoaded', function() {
    // Selecciona el elemento del menú lateral (la barra lateral)
    const menuLateral = document.querySelector('.menu-lateral');
    // Selecciona todos los elementos de lista (li) que tienen un submenú
    const itemsConSubmenu = document.querySelectorAll('.menu-lateral .has-submenu');
    // Selecciona todos los enlaces principales (a) dentro de los elementos con submenú
    const enlacesPrincipalesSubmenu = document.querySelectorAll('.menu-lateral .has-submenu > .enlace-menu');

    // --- Lógica para desplegar/replegar submenús al hacer clic en el enlace principal ---
    // Itera sobre cada enlace principal de submenú
    enlacesPrincipalesSubmenu.forEach(enlace => {
        // Añade un "escuchador" de evento clic a cada enlace
        enlace.addEventListener('click', function(e) {
            // Obtiene el elemento padre (li) que contiene este enlace y el submenú
            const itemPadre = this.parentElement;
            // Obtiene el elemento de la flecha dentro de este enlace
            const flecha = this.querySelector('.flecha');
            // Obtiene el elemento del submenú (ul) asociado a este elemento padre
            const submenu = itemPadre.querySelector('.submenu'); // Aunque no se usa directamente aquí para max-height, es útil tenerlo

            // Determina si el menú lateral está actualmente colapsado (en su estado estrecho)
            const isMenuCollapsed = menuLateral.classList.contains('colapsado');

            // Si el menú NO está colapsado (está expandido)
            // O si SÍ está colapsado, pero el enlace es solo un marcador de posición '#'
            // Previene el comportamiento por defecto del enlace (navegar a otra página)
            // para poder manejar nosotros la lógica de desplegar/replegar.
            // Si el menú SÍ está colapsado Y el enlace tiene una URL real (no es '#'),
            // entonces permitimos la navegación por defecto en el segundo clic (ver 'else').
            if (!isMenuCollapsed || this.getAttribute('href') === '#') {
                 e.preventDefault();
            } else {
                 // Si el menú está colapsado y el enlace tiene una URL real,
                 // no hacemos nada aquí, permitimos que el navegador siga el enlace.
                 // Esto ocurriría si el usuario hace clic una segunda vez en el icono
                 // después de que el submenú se desplegó por hover o por un primer clic.
                 return; // Salimos de la función para no ejecutar el resto del código.
            }


            // Alterna la clase 'active' en el elemento padre (li).
            // Esta clase 'active' es la que usa el CSS para:
            // 1. En el menú expandido: controlar la altura máxima del submenú para desplegarlo/replegarlo suavemente.
            // 2. Rotar la flecha.
            itemPadre.classList.toggle('active');

            // Rota la flecha hacia arriba o hacia abajo.
            // La transición suave la maneja el CSS.
            if (itemPadre.classList.contains('active')) {
                 flecha.style.transform = 'rotate(180deg)'; // Rota 180 grados (apunta hacia arriba)
            } else {
                 flecha.style.transform = 'rotate(0deg)'; // Vuelve a 0 grados (apunta hacia abajo)
            }

            // Nota importante: El despliegue/repliegue visual del submenú (la animación)
            // se maneja completamente a través de las transiciones CSS en la propiedad `max-height`.
            // El CSS reacciona a la presencia de la clase 'active' (cuando el menú no está colapsado)
            // O al estado ':hover' (cuando el menú SÍ está colapsado).
        });
    });

    // --- Lógica para el Menú Móvil (Botón Hamburguesa) ---
    // Selecciona el botón que abre/cierra el menú en pantallas pequeñas.
    // Se asume que este botón ya existe en el HTML con la clase 'boton-menu-movil'.
    const botonMenuMovil = document.querySelector('.boton-menu-movil');
    // Si el botón no existe en el HTML, este bloque lo crea dinámicamente (como en versiones anteriores).
    if (!botonMenuMovil) {
         const newBotonMenuMovil = document.createElement('button');
         newBotonMenuMovil.className = 'boton-menu-movil';
         newBotonMenuMovil.innerHTML = '<i class="fas fa-bars"></i>'; // Icono de hamburguesa
         document.body.appendChild(newBotonMenuMovil);
         botonMenuMovil = newBotonMenuMovil; // Asigna la variable al botón recién creado
    }

    // Añade un escuchador de clic al botón de menú móvil
    botonMenuMovil.addEventListener('click', function(e) {
        // Detiene la propagación del evento para que no active otros escuchadores (como el de cerrar menú al hacer clic fuera)
        e.stopPropagation();
        // Alterna la clase 'abierto' en el menú lateral.
        // Esta clase es la que usa el CSS para mostrar/ocultar el menú en móvil.
        menuLateral.classList.toggle('abierto');
        // Cambia el icono del botón entre hamburguesa y 'X'
         if (menuLateral.classList.contains('abierto')) {
             botonMenuMovil.innerHTML = '<i class="fas fa-times"></i>'; // Icono 'X'
             // Opcional: Colapsa todos los submenús si el menú móvil se abre
             // para evitar que se muestren submenús desplegados del estado de escritorio.
             itemsConSubmenu.forEach(item => {
                 item.classList.remove('active'); // Quita la clase active
                 const flecha = item.querySelector('.flecha');
                 if (flecha) flecha.style.transform = 'rotate(0deg)'; // Restaura la flecha
             });
         } else {
             botonMenuMovil.innerHTML = '<i class="fas fa-bars"></i>'; // Icono de hamburguesa
             // Opcional: Colapsa todos los submenús si el menú móvil se cierra
             itemsConSubmenu.forEach(item => {
                 item.classList.remove('active');
                 const flecha = item.querySelector('.flecha');
                 if (flecha) flecha.style.transform = 'rotate(0deg)';
             });
         }
    });

    // --- Lógica para Cerrar el Menú Móvil al hacer clic fuera ---
    // Añade un escuchador de clic a todo el documento
    document.addEventListener('click', function(e) {
        // Si el menú lateral tiene la clase 'abierto' (está visible en móvil)
        // Y el clic NO fue dentro del menú lateral
        // Y el clic NO fue en el botón de menú móvil
        if (menuLateral.classList.contains('abierto') && !menuLateral.contains(e.target) && e.target !== botonMenuMovil) {
            // Cierra el menú móvil quitando la clase 'abierto'
            menuLateral.classList.remove('abierto');
            // Restaura el icono de hamburguesa en el botón
            botonMenuMovil.innerHTML = '<i class="fas fa-bars"></i>';
             // Colapsa todos los submenús al cerrar el menú móvil
             itemsConSubmenu.forEach(item => {
                 item.classList.remove('active');
                 const flecha = item.querySelector('.flecha');
                 if (flecha) flecha.style.transform = 'rotate(0deg)';
             });
        }
    });

    // --- Manejo del estado inicial de las flechas al cargar la página ---
    // Recorre todos los elementos que tienen submenú
     itemsConSubmenu.forEach(item => {
         // Si el elemento tiene la clase 'active' (ej. si la página actual corresponde a esa sección)
         // Y el menú lateral NO está colapsado (para evitar rotar flechas en iconos colapsados)
         if (item.classList.contains('active') && !menuLateral.classList.contains('colapsado')) {
             // Encuentra la flecha dentro de este elemento
             const flecha = item.querySelector('.flecha');
             // Si la flecha existe, la rota para que apunte hacia arriba, indicando que el submenú está "abierto" visualmente (si el CSS lo permite).
             if (flecha) {
                 flecha.style.transform = 'rotate(180deg)';
             }
         }
     });
});