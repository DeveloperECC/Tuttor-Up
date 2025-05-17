// publico/js/docentes_interacciones.js
document.addEventListener('DOMContentLoaded', () => {
    // Verificar si los datos de PHP están disponibles
    if (typeof teachersDataFromPHP === 'undefined') {
        console.error('teachersDataFromPHP no está definido. Asegúrate de pasarlo desde PHP en la vista docentes/index.php.');
        return;
    }

    const allTeachersOriginal = teachersDataFromPHP;
    let currentFilteredTeachers = allTeachersOriginal.slice();
    let currentPage = 0;
    const cardsPerPage = 6;

    // Elementos del DOM
    const cardsContainer = document.getElementById('cards-container-docentes');
    const reservaContainer = document.getElementById('reserva-container-docentes');
    const carruselDocentesSection = document.getElementById('carouselDocentes');
    const volverADocentesBtn = document.getElementById('volver-a-docentes-btn');
    const modalHorarios = document.getElementById('horarioModalDocentes');
    const closeModalHorariosBtn = document.getElementById('closeModalHorariosDocentes');
    const horarioBtns = modalHorarios ? modalHorarios.querySelectorAll('.boton-horario') : [];
    const confirmReservarBtn = document.getElementById('confirmReservarDocente');
    const confirmEliminarBtn = document.getElementById('confirmEliminarDocente');
    const subjectTitleDocentes = document.getElementById('subject-title-docentes');
    const filterMateriaCheckboxes = document.querySelectorAll('.filtro-materia');
    const filterRatingRadios = document.querySelectorAll('.filtro-rating');
    const docentesSearchInput = document.getElementById('docentesSearchInput');
    const prevButton = document.querySelector('.carrusel-docentes .carrusel__boton--anterior');
    const nextButton = document.querySelector('.carrusel-docentes .carrusel__boton--siguiente');

    let diaSeleccionadoParaReserva = null;
    let docenteSeleccionadoParaReserva = null;
    const bookings = {};

    // --- FUNCIONES HELPER ---
    function normalizarTexto(texto) {
        if (typeof texto !== 'string') return '';
        return texto
            .toLowerCase()
            .normalize("NFD")
            .replace(/[\u0300-\u036f]/g, "");
    }

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

    // --- RENDERIZADO DE TARJETAS ---
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
        const imagePath = teacher.full_img_path || (ASSETS_URL + '/imagenes/' + teacher.img);
        card.innerHTML = `
            <img src="${imagePath}" alt="Foto de ${teacher.name}" class="tarjeta__imagen-docente">
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
            </div>`;
        return card;
    }

    function renderCards() {
        if (!cardsContainer) {
            console.error("Elemento .carrusel__tarjetas (cards-container-docentes) no encontrado.");
            return;
        }
        cardsContainer.innerHTML = '';
        const slice = currentFilteredTeachers.slice(currentPage * cardsPerPage, (currentPage + 1) * cardsPerPage);
        
        if (slice.length === 0) {
            cardsContainer.innerHTML = '<p class="sin-resultados-docentes">No hay docentes que coincidan con tu búsqueda.</p>';
        } else {
            slice.forEach(t => {
                const cardElement = renderTeacherCard(t);
                cardsContainer.appendChild(cardElement);
            });
        }
        addAgendaButtonListeners();
        updatePaginationButtons();
    }

    function addAgendaButtonListeners() {
        document.querySelectorAll('.tarjeta__boton-agenda').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const card = e.target.closest('.tarjeta-docente');
                if (!card) return;
                const teacherId = parseInt(card.dataset.teacherId);
                const teacherData = allTeachersOriginal.find(t => t.id === teacherId);
                if (teacherData) {
                    docenteSeleccionadoParaReserva = teacherData;
                    mostrarVistaReserva(teacherData);
                } else {
                    console.error("No se encontraron datos para el docente con ID:", teacherId);
                }
            });
        });
    }
    
    function updatePaginationButtons() {
        if (!prevButton || !nextButton) return;
        const totalPages = Math.ceil(currentFilteredTeachers.length / cardsPerPage);

        prevButton.style.display = totalPages > 1 ? 'flex' : 'none';
        nextButton.style.display = totalPages > 1 ? 'flex' : 'none';

        prevButton.disabled = currentPage === 0;
        nextButton.disabled = currentPage >= totalPages - 1;
    }

    // --- MANEJO DE VISTA DE RESERVA ---
    function mostrarVistaReserva(teacher) {
        if (!reservaContainer || !carruselDocentesSection || !document.getElementById('reserva-nombre-docente')) {
            console.error("Elementos de la vista de reserva no encontrados.");
            return;
        }
        document.getElementById('reserva-nombre-docente').textContent = teacher.name;
        const imgPath = teacher.full_img_path || (ASSETS_URL + '/imagenes/' + teacher.img);
        document.getElementById('reserva-imagen-docente').src = imgPath;
        renderStarRating(teacher.rating, document.getElementById('reserva-calificacion-docente'));
        document.getElementById('reserva-exp-docente').textContent = `${teacher.experience} años de experiencia`;
        document.getElementById('reserva-desc-docente').textContent = teacher.description || "Descripción no disponible.";
        
        carruselDocentesSection.style.display = 'none';
        const barraSuperior = document.querySelector('.barra-superior-docentes');
        if (barraSuperior) barraSuperior.style.display = 'none';
        
        reservaContainer.classList.remove('reserva-vista-oculta');
        reservaContainer.classList.add('reserva-vista-visible');
        generarCalendario();
        window.scrollTo(0,0);
    }

    function ocultarVistaReserva() {
        if (!reservaContainer || !carruselDocentesSection) return;
        reservaContainer.classList.remove('reserva-vista-visible');
        reservaContainer.classList.add('reserva-vista-oculta');
        carruselDocentesSection.style.display = 'block'; // o 'flex' si el carrusel es flex
        const barraSuperior = document.querySelector('.barra-superior-docentes');
        if (barraSuperior) barraSuperior.style.display = 'flex';
        docenteSeleccionadoParaReserva = null;
    }
    volverADocentesBtn?.addEventListener('click', ocultarVistaReserva);

    // --- CALENDARIO Y MODAL DE HORARIOS ---
    function generarCalendario() {
        const calendarioDiv = document.getElementById('calendario-docente');
        if (!calendarioDiv || !docenteSeleccionadoParaReserva) return; // Asegurar que hay un docente seleccionado
        calendarioDiv.innerHTML = '';
        const fecha = new Date();
        const mesActual = fecha.getMonth();
        const añoActual = fecha.getFullYear();
        const primerDiaDelMes = new Date(añoActual, mesActual, 1).getDay();
        const diasEnMes = new Date(añoActual, mesActual + 1, 0).getDate();

        for (let i = 0; i < primerDiaDelMes; i++) {
            const diaVacio = document.createElement('div');
            diaVacio.className = 'dia-calendario dia-vacio';
            calendarioDiv.appendChild(diaVacio);
        }
        for (let dia = 1; dia <= diasEnMes; dia++) {
            const diaElem = document.createElement('div');
            diaElem.className = 'dia-calendario';
            diaElem.dataset.dia = dia;
            diaElem.innerHTML = `<span>${dia}</span>`;
            const bookingKey = `${docenteSeleccionadoParaReserva.id}-${dia}`;
            if (bookings[bookingKey]) {
                diaElem.classList.add('disponible');
            }
            diaElem.addEventListener('click', () => abrirModalHorarios(dia));
            calendarioDiv.appendChild(diaElem);
        }
    }

    function abrirModalHorarios(dia) {
        if (!modalHorarios || !docenteSeleccionadoParaReserva) return;
        diaSeleccionadoParaReserva = dia;
        const bookingKey = `${docenteSeleccionadoParaReserva.id}-${dia}`;
        const horarioReservado = bookings[bookingKey];

        horarioBtns.forEach(btn => {
            btn.classList.remove('seleccionado');
            btn.disabled = false;
            if (horarioReservado && btn.textContent.trim() === horarioReservado) {
                btn.classList.add('seleccionado');
            }
        });
        if (confirmEliminarBtn) confirmEliminarBtn.style.display = horarioReservado ? 'inline-block' : 'none';
        modalHorarios.style.display = 'flex';
    }

    function cerrarModalHorarios() {
        if (!modalHorarios) return;
        modalHorarios.style.display = 'none';
        // diaSeleccionadoParaReserva = null; // No resetear aquí, se usa al confirmar
    }

    closeModalHorariosBtn?.addEventListener('click', cerrarModalHorarios);
    window.addEventListener('click', (event) => { if (modalHorarios && event.target === modalHorarios) cerrarModalHorarios(); });

    horarioBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.classList.contains('seleccionado')) {
                 btn.classList.remove('seleccionado');
            } else {
                horarioBtns.forEach(b => b.classList.remove('seleccionado'));
                btn.classList.add('seleccionado');
            }
        });
    });

    confirmReservarBtn?.addEventListener('click', () => {
        const selectedHorarioBtn = modalHorarios ? modalHorarios.querySelector('.boton-horario.seleccionado') : null;
        if (!docenteSeleccionadoParaReserva || !diaSeleccionadoParaReserva || !selectedHorarioBtn) {
            alert('Por favor, selecciona un docente, un día y un horario.');
            return;
        }
        const horario = selectedHorarioBtn.textContent.trim();
        const bookingKey = `${docenteSeleccionadoParaReserva.id}-${diaSeleccionadoParaReserva}`;
        bookings[bookingKey] = horario;
        alert(`Reserva confirmada con ${docenteSeleccionadoParaReserva.name} el día ${diaSeleccionadoParaReserva} de ${horario}.`);
        const diaElemCalendario = document.querySelector(`.dia-calendario[data-dia="${diaSeleccionadoParaReserva}"]`);
        if (diaElemCalendario) diaElemCalendario.classList.add('disponible');
        cerrarModalHorarios();
    });
    
    confirmEliminarBtn?.addEventListener('click', () => {
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

    // --- FILTROS Y BÚSQUEDA ---
    function updateSubjectTitle() {
        if (!subjectTitleDocentes) return;
        const materiasSeleccionadas = Array.from(filterMateriaCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        if (materiasSeleccionadas.length === 1) {
            subjectTitleDocentes.textContent = materiasSeleccionadas[0].toUpperCase();
        } else if (materiasSeleccionadas.length > 1) {
            subjectTitleDocentes.textContent = "VARIAS MATERIAS";
        } else if (materiaFiltradaDesdePHP && !filterMateriaCheckboxes.some(cb => cb.checked)) {
            // Si vino filtrado por URL y el usuario no ha interactuado aún con los checkboxes de materia
            subjectTitleDocentes.textContent = materiaFiltradaDesdePHP.toUpperCase();
        } else {
            subjectTitleDocentes.textContent = "DOCENTES";
        }
    }

    function getFilteredTeachers() {
        let result = allTeachersOriginal.slice();
        const materiasSeleccionadas = Array.from(filterMateriaCheckboxes)
                                        .filter(checkbox => checkbox.checked)
                                        .map(cb => normalizarTexto(cb.value));

        const selectedRatingRadio = document.querySelector('.filtro-rating:checked');
        const minRating = selectedRatingRadio ? parseInt(selectedRatingRadio.value, 10) : 0;

        const terminoBusqueda = docentesSearchInput ? normalizarTexto(docentesSearchInput.value.trim()) : "";

        if (materiasSeleccionadas.length > 0) {
            result = result.filter(teacher => 
                teacher.subject && materiasSeleccionadas.includes(normalizarTexto(teacher.subject))
            );
        } else if (materiaFiltradaDesdePHP && !filterMateriaCheckboxes.some(cb => cb.checked)) {
            // Aplicar filtro PHP si no hay interacción de usuario con checkboxes de materia
            result = result.filter(teacher => 
                teacher.subject && normalizarTexto(teacher.subject) === normalizarTexto(materiaFiltradaDesdePHP)
            );
        }

        if (minRating > 0) {
            result = result.filter(teacher => teacher.rating >= minRating);
        }
        
        if (terminoBusqueda !== '') {
            result = result.filter(teacher => 
                normalizarTexto(teacher.name).includes(terminoBusqueda) ||
                (teacher.description && normalizarTexto(teacher.description).includes(terminoBusqueda))
            );
        }
        return result;
    }

    function applyFiltersAndRender() {
        currentPage = 0;
        currentFilteredTeachers = getFilteredTeachers();
        renderCards();
        updateSubjectTitle();
    }

    // --- EVENT LISTENERS PARA FILTROS Y PAGINACIÓN ---
    filterMateriaCheckboxes.forEach(input => input.addEventListener('change', applyFiltersAndRender));
    filterRatingRadios.forEach(input => input.addEventListener('change', applyFiltersAndRender));
    docentesSearchInput?.addEventListener('input', applyFiltersAndRender);

    prevButton?.addEventListener('click', () => {
        if (currentPage > 0) {
            currentPage--;
            renderCards();
        }
    });
    nextButton?.addEventListener('click', () => {
        const totalPages = Math.ceil(currentFilteredTeachers.length / cardsPerPage);
        if (currentPage < totalPages - 1) {
            currentPage++;
            renderCards();
        }
    });

    // --- INICIALIZACIÓN ---
    function aplicarFiltrosInicialesPHP() {
        if (typeof materiaFiltradaDesdePHP === 'string' && materiaFiltradaDesdePHP.trim() !== '') {
            let foundAndChecked = false;
            filterMateriaCheckboxes.forEach(checkbox => {
                if (normalizarTexto(checkbox.value) === normalizarTexto(materiaFiltradaDesdePHP)) {
                    checkbox.checked = true;
                    foundAndChecked = true;
                } else {
                    checkbox.checked = false; 
                }
            });
            // Si se encontró y marcó la materia de PHP, los filtros se aplicarán en applyFiltersAndRender
        }
        applyFiltersAndRender(); 
    }

    // Llamar a la inicialización
    aplicarFiltrosInicialesPHP();
});