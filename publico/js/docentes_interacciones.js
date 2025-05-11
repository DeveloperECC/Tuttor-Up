// publico/js/docentes_interacciones.js
document.addEventListener('DOMContentLoaded', () => {
    // Asegúrate que teachersDataFromPHP está disponible (inyectado desde la vista PHP)
    if (typeof teachersDataFromPHP === 'undefined') {
        console.error('teachersDataFromPHP no está definido. Asegúrate de pasarlo desde PHP.');
        return;
    }

    let allTeachers = teachersDataFromPHP;
    let currentPage = 0;
    const cardsPerPage = 6; // Mostrar 6 tarjetas por página (2 filas de 3) o ajustar
    const cardsContainer = document.getElementById('cards-container-docentes');
    
    const reservaContainer = document.getElementById('reserva-container-docentes');
    const mainContentDocentes = document.querySelector('.carrusel-docentes'); // El carrusel es el contenido principal aquí
    const volverADocentesBtn = document.getElementById('volver-a-docentes-btn');

    const modalHorarios = document.getElementById('horarioModalDocentes');
    const closeModalHorariosBtn = document.getElementById('closeModalHorariosDocentes');
    const horarioBtns = modalHorarios.querySelectorAll('.boton-horario');
    const confirmReservarBtn = document.getElementById('confirmReservarDocente');
    const confirmEliminarBtn = document.getElementById('confirmEliminarDocente');

    const subjectTitleDocentes = document.getElementById('subject-title-docentes');
    const filterMateriaCheckboxes = document.querySelectorAll('.filtro-materia');
    const filterRatingCheckboxes = document.querySelectorAll('.filtro-rating');

    let diaSeleccionadoParaReserva = null;
    let docenteSeleccionadoParaReserva = null;
    const bookings = {}; // { "docenteId-dia": "horario", ... }

    function renderStarRating(rating, targetElement) {
        let starsHTML = '';
        for (let i = 1; i <= 5; i++) {
            starsHTML += `<i class="${i <= rating ? 'fas' : 'far'} fa-star tarjeta__icono-valoracion-star"></i>`;
        }
        if (targetElement) {
            targetElement.innerHTML = starsHTML;
        }
        return starsHTML;
    }
    
    function renderTeacherCard(teacher) {
        const card = document.createElement('div');
        card.className = 'tarjeta-docente';
        card.dataset.teacherId = teacher.id;

        if (teacher.featured) {
            const tag = document.createElement('span');
            tag.className = 'tarjeta__etiqueta-docente';
            tag.textContent = 'Destacado';
            card.appendChild(tag);
        }

        // ASSETS_URL debe estar definido globalmente
        const imagePath = teacher.full_img_path || (ASSETS_URL + '/imagenes/' + teacher.img);


        card.innerHTML += `
            <img src="${imagePath}" alt="${teacher.name}" class="tarjeta__imagen-docente">
            <div class="tarjeta__contenido-docente">
                <h3 class="tarjeta__titulo-docente">${teacher.name}</h3>
                <div class="tarjeta__valoracion-docente">
                    ${renderStarRating(teacher.rating)}
                    <span class="tarjeta__texto-valoracion-docente">${teacher.opinions} opiniones</span>
                </div>
                <div class="tarjeta__precio-docente">$${teacher.price.toLocaleString()} COP</div>
                <div class="tarjeta__experiencia-docente">
                    <i class="fas fa-award"></i>${teacher.experience} años de experiencia
                </div>
                <button class="tarjeta__boton-agenda">Agendar</button>
            </div>
        `;
        return card;
    }

    function renderCards(list = allTeachers) {
        if (!cardsContainer) return;
        cardsContainer.innerHTML = '';
        const slice = list.slice(currentPage * cardsPerPage, (currentPage + 1) * cardsPerPage);
        
        if (slice.length === 0) {
            cardsContainer.innerHTML = '<p class="sin-resultados-docentes">No hay docentes que coincidan con tu búsqueda.</p>';
            return;
        }

        slice.forEach(t => {
            const cardElement = renderTeacherCard(t);
            cardsContainer.appendChild(cardElement);
        });

        // Eventos para botones Agenda
        document.querySelectorAll('.tarjeta__boton-agenda').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const card = e.target.closest('.tarjeta-docente');
                const teacherId = parseInt(card.dataset.teacherId);
                const teacherData = allTeachers.find(t => t.id === teacherId);
                docenteSeleccionadoParaReserva = teacherData;
                mostrarVistaReserva(teacherData);
            });
        });
    }
    
    function mostrarVistaReserva(teacher) {
        if (!reservaContainer || !mainContentDocentes) return;

        document.getElementById('reserva-nombre-docente').textContent = teacher.name;
        const imgPath = teacher.full_img_path || (ASSETS_URL + '/imagenes/' + teacher.img);
        document.getElementById('reserva-imagen-docente').src = imgPath;
        renderStarRating(teacher.rating, document.getElementById('reserva-calificacion-docente'));
        document.getElementById('reserva-exp-docente').textContent = `${teacher.experience} años de experiencia`;
        document.getElementById('reserva-desc-docente').textContent = teacher.description || "Descripción no disponible.";
        
        mainContentDocentes.style.display = 'none'; // Ocultar carrusel
        if (document.querySelector('.barra-superior-docentes')) {
             document.querySelector('.barra-superior-docentes').style.display = 'none'; // Ocultar header de docentes
        }
        reservaContainer.classList.remove('reserva-vista-oculta');
        reservaContainer.classList.add('reserva-vista-visible');
        
        generarCalendario();
        window.scrollTo(0,0); // Scroll al inicio
    }

    function ocultarVistaReserva() {
        if (!reservaContainer || !mainContentDocentes) return;
        reservaContainer.classList.remove('reserva-vista-visible');
        reservaContainer.classList.add('reserva-vista-oculta');
        mainContentDocentes.style.display = 'block'; // Mostrar carrusel de nuevo
         if (document.querySelector('.barra-superior-docentes')) {
             document.querySelector('.barra-superior-docentes').style.display = 'flex'; // Mostrar header de docentes
        }
        docenteSeleccionadoParaReserva = null;
    }

    if (volverADocentesBtn) {
        volverADocentesBtn.addEventListener('click', ocultarVistaReserva);
    }


    function generarCalendario() {
        const calendarioDiv = document.getElementById('calendario-docente');
        if (!calendarioDiv) return;
        calendarioDiv.innerHTML = '';
        const fecha = new Date();
        const mesActual = fecha.getMonth(); // 0-11
        const añoActual = fecha.getFullYear();
      
        // Días en el mes y primer día del mes
        const primerDiaDelMes = new Date(añoActual, mesActual, 1).getDay(); // 0 (Dom) - 6 (Sab)
        const diasEnMes = new Date(añoActual, mesActual + 1, 0).getDate();

        // Rellenar días vacíos al inicio
        for (let i = 0; i < primerDiaDelMes; i++) {
            const diaVacio = document.createElement('div');
            diaVacio.className = 'dia-calendario dia-vacio';
            calendarioDiv.appendChild(diaVacio);
        }

        // Generar días del mes
        for (let dia = 1; dia <= diasEnMes; dia++) {
            const diaElem = document.createElement('div');
            diaElem.className = 'dia-calendario';
            diaElem.dataset.dia = dia;
            diaElem.innerHTML = `<span>${dia}</span>`;
            
            // Marcar si está disponible (simulación: todos los días disponibles para elegir)
            // Aquí podrías tener lógica para marcar días reales no disponibles del tutor
            const bookingKey = `${docenteSeleccionadoParaReserva.id}-${dia}`;
            if (bookings[bookingKey]) {
                diaElem.classList.add('disponible');
            }

            diaElem.addEventListener('click', () => abrirModalHorarios(dia));
            calendarioDiv.appendChild(diaElem);
        }
    }

    function abrirModalHorarios(dia) {
        if (!modalHorarios) return;
        diaSeleccionadoParaReserva = dia;
        
        // Actualizar estado de botones de horario
        const bookingKey = `${docenteSeleccionadoParaReserva.id}-${dia}`;
        const horarioReservado = bookings[bookingKey];

        horarioBtns.forEach(btn => {
            btn.classList.remove('seleccionado');
            btn.disabled = false; // Habilitar todos por defecto
            if (horarioReservado && btn.textContent.trim() === horarioReservado) {
                btn.classList.add('seleccionado'); // Marcar el reservado
            }
            // Aquí podrías añadir lógica para deshabilitar horarios ya ocupados por otros
        });
        
        confirmEliminarBtn.style.display = horarioReservado ? 'inline-block' : 'none';

        modalHorarios.style.display = 'flex';
    }

    function cerrarModalHorarios() {
        if (!modalHorarios) return;
        modalHorarios.style.display = 'none';
        diaSeleccionadoParaReserva = null;
    }

    if (closeModalHorariosBtn) {
        closeModalHorariosBtn.addEventListener('click', cerrarModalHorarios);
    }
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', (event) => {
        if (event.target === modalHorarios) {
            cerrarModalHorarios();
        }
    });


    horarioBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('seleccionado')) { // Deseleccionar si ya está seleccionado
                 btn.classList.remove('seleccionado');
            } else {
                horarioBtns.forEach(b => b.classList.remove('seleccionado'));
                btn.classList.add('seleccionado');
            }
        });
    });

    if (confirmReservarBtn) {
        confirmReservarBtn.addEventListener('click', () => {
            const selectedHorarioBtn = modalHorarios.querySelector('.boton-horario.seleccionado');
            if (!docenteSeleccionadoParaReserva || !diaSeleccionadoParaReserva || !selectedHorarioBtn) {
                alert('Por favor, selecciona un docente, un día y un horario.');
                return;
            }
            const horario = selectedHorarioBtn.textContent.trim();
            const bookingKey = `${docenteSeleccionadoParaReserva.id}-${diaSeleccionadoParaReserva}`;
            
            // Simulación de reserva
            bookings[bookingKey] = horario;
            console.log('Reserva confirmada:', docenteSeleccionadoParaReserva.name, diaSeleccionadoParaReserva, horario);
            alert(`Reserva confirmada con ${docenteSeleccionadoParaReserva.name} el día ${diaSeleccionadoParaReserva} de ${horario}.`);
            
            // Actualizar calendario visualmente
            const diaElemCalendario = document.querySelector(`.dia-calendario[data-dia="${diaSeleccionadoParaReserva}"]`);
            if (diaElemCalendario) diaElemCalendario.classList.add('disponible');
            
            cerrarModalHorarios();
        });
    }
    
    if (confirmEliminarBtn) {
        confirmEliminarBtn.addEventListener('click', () => {
            if (!docenteSeleccionadoParaReserva || !diaSeleccionadoParaReserva) return;
            const bookingKey = `${docenteSeleccionadoParaReserva.id}-${diaSeleccionadoParaReserva}`;

            if (bookings[bookingKey]) {
                delete bookings[bookingKey];
                alert('Reserva eliminada.');
                const diaElemCalendario = document.querySelector(`.dia-calendario[data-dia="${diaSeleccionadoParaReserva}"]`);
                if (diaElemCalendario) diaElemCalendario.classList.remove('disponible');
            }
            cerrarModalHorarios();
        });
    }


    // Navegación del carrusel
    const prevButton = document.querySelector('.carrusel-docentes .carrusel__boton--anterior');
    const nextButton = document.querySelector('.carrusel-docentes .carrusel__boton--siguiente');

    if (prevButton) {
        prevButton.addEventListener('click', () => {
            const totalPages = Math.ceil(getFilteredTeachers().length / cardsPerPage);
            currentPage = (currentPage - 1 + totalPages) % totalPages;
            renderCards(getFilteredTeachers());
        });
    }
    if (nextButton) {
        nextButton.addEventListener('click', () => {
            const totalPages = Math.ceil(getFilteredTeachers().length / cardsPerPage);
            currentPage = (currentPage + 1) % totalPages;
            renderCards(getFilteredTeachers());
        });
    }
    
    // Filtros
    function updateSubjectTitle() {
        if (!subjectTitleDocentes) return;
        const materiasSeleccionadas = Array.from(filterMateriaCheckboxes)
                                        .filter(checkbox => checkbox.checked)
                                        .map(cb => cb.value);

        if (materiasSeleccionadas.length === 1) {
            subjectTitleDocentes.textContent = materiasSeleccionadas[0].toUpperCase();
        } else if (materiasSeleccionadas.length > 1) {
            subjectTitleDocentes.textContent = "VARIAS MATERIAS";
        } else {
            subjectTitleDocentes.textContent = "DOCENTES"; // Título por defecto
        }
    }

    function getFilteredTeachers() {
        let result = allTeachers.slice(); // Copia para no modificar el original

        // Filtrar por materia
        const materiasSeleccionadas = Array.from(filterMateriaCheckboxes)
                                        .filter(checkbox => checkbox.checked)
                                        .map(cb => cb.value);
        if (materiasSeleccionadas.length > 0) {
            result = result.filter(teacher => materiasSeleccionadas.includes(teacher.subject));
        }

        // Filtrar por calificación
        const ratingsSeleccionados = Array.from(filterRatingCheckboxes)
                                        .filter(checkbox => checkbox.checked)
                                        .map(cb => parseInt(cb.value, 10));
        if (ratingsSeleccionados.length > 0) {
            result = result.filter(teacher => ratingsSeleccionados.includes(teacher.rating));
        }
        
        // Filtrar por búsqueda de texto (opcional, si añades input de búsqueda)
        const searchInputDocentes = document.getElementById('docentesSearchInput');
        if (searchInputDocentes && searchInputDocentes.value.trim() !== '') {
            const termino = searchInputDocentes.value.trim().toLowerCase();
            result = result.filter(teacher => 
                teacher.name.toLowerCase().includes(termino) ||
                (teacher.description && teacher.description.toLowerCase().includes(termino))
            );
        }

        return result;
    }

    function applyFiltersAndRender() {
        currentPage = 0; // Resetear a la primera página con nuevos filtros
        updateSubjectTitle();
        const filteredList = getFilteredTeachers();
        renderCards(filteredList);
        // Actualizar visibilidad de botones de paginación si es necesario
        const totalPages = Math.ceil(filteredList.length / cardsPerPage);
        if (prevButton) prevButton.style.display = totalPages > 1 ? 'flex' : 'none';
        if (nextButton) nextButton.style.display = totalPages > 1 ? 'flex' : 'none';
    }

    filterMateriaCheckboxes.forEach(input => input.addEventListener('change', applyFiltersAndRender));
    filterRatingCheckboxes.forEach(input => input.addEventListener('change', applyFiltersAndRender));
    
    const docentesSearchButton = document.getElementById('docentesSearchButton');
    const docentesSearchInput = document.getElementById('docentesSearchInput');
    if(docentesSearchButton) docentesSearchButton.addEventListener('click', applyFiltersAndRender);
    if(docentesSearchInput) docentesSearchInput.addEventListener('keypress', (e) => {
        if(e.key === 'Enter') applyFiltersAndRender();
    });


    // Inicialización
    applyFiltersAndRender(); // Renderizar tarjetas y título inicial
});