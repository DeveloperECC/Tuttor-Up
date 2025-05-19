// publico/js/docentes_interacciones.js
document.addEventListener('DOMContentLoaded', () => {
    // Verificar si los datos de PHP están disponibles
    if (typeof teachersDataFromPHP === 'undefined') {
        console.error('teachersDataFromPHP no está definido. Asegúrate de pasarlo desde PHP en la vista docentes/index.php.');
        return;
    }
    if (typeof ASSETS_URL === 'undefined' || typeof BASE_URL === 'undefined') {
        console.error('ASSETS_URL o BASE_URL no están definidos. Asegúrate de que existen en el scope global (layout_principal.php).');
        return;
    }


    const allTeachersOriginal = teachersDataFromPHP;
    let currentFilteredTeachers = allTeachersOriginal.slice();
    let currentPage = 0;
    const cardsPerPage = 4;

    // Elementos del DOM
    const cardsContainer = document.getElementById('cards-container-docentes');
    const reservaContainer = document.getElementById('reserva-container-docentes');
    const carruselDocentesSection = document.getElementById('carouselDocentes');
    const volverADocentesBtn = document.getElementById('volver-a-docentes-btn');
    const modalHorarios = document.getElementById('horarioModalDocentes');
    const closeModalHorariosBtn = document.getElementById('closeModalHorariosDocentes');
    const horarioBtnsContainer = modalHorarios ? modalHorarios.querySelector('.horarios-grid') : null; // Contenedor de botones de horario
    const confirmReservarBtn = document.getElementById('confirmReservarDocente');
    const confirmEliminarBtn = document.getElementById('confirmEliminarDocente'); // No se usa para crear reserva
    const subjectTitleDocentes = document.getElementById('subject-title-docentes');
    const filterMateriaCheckboxes = document.querySelectorAll('.filtro-materia');
    const filterRatingRadios = document.querySelectorAll('.filtro-rating');
    const docentesSearchInput = document.getElementById('docentesSearchInput');
    const prevButton = document.querySelector('.carrusel-docentes .carrusel__boton--anterior');
    const nextButton = document.querySelector('.carrusel-docentes .carrusel__boton--siguiente');

    let diaSeleccionadoParaReserva = null; // Guarda el objeto Date completo del día seleccionado
    let docenteSeleccionadoParaReserva = null;
    // const bookings = {}; // Ya no necesitamos bookings local, se consultará al backend

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
        // Asegurarse que teacher.price es un número antes de usar toLocaleString
        const price = typeof teacher.price === 'number' ? teacher.price.toLocaleString() : (teacher.price || 'N/A');
        card.innerHTML = `
            <img src="${imagePath}" alt="Foto de ${teacher.name}" class="tarjeta__imagen-docente">
            <div class="tarjeta__contenido-docente">
                <h3 class="tarjeta__titulo-docente">${teacher.name}</h3>
                <div class="tarjeta__valoracion-docente">
                    ${renderStarRating(teacher.rating)}
                    <span class="tarjeta__texto-valoracion-docente">${teacher.opinions} opiniones</span>
                </div>
                <div class="tarjeta__precio-docente">$${price} COP</div>
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
        const filtrosAside = document.getElementById('filtrosDocentes');
        if (filtrosAside) filtrosAside.style.display = 'none';
        
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
        const filtrosAside = document.getElementById('filtrosDocentes');
        if (filtrosAside) filtrosAside.style.display = 'block'; // o 'flex'

        docenteSeleccionadoParaReserva = null;
        if (modalHorarios) modalHorarios.style.display = 'none';
    }
    volverADocentesBtn?.addEventListener('click', ocultarVistaReserva);

    // --- CALENDARIO Y MODAL DE HORARIOS ---
    function generarCalendario() {
        const calendarioDiv = document.getElementById('calendario-docente');
        if (!calendarioDiv) return;
        calendarioDiv.innerHTML = ''; // Limpiar calendario
        const hoy = new Date();
        const mesActual = hoy.getMonth();
        const añoActual = hoy.getFullYear();
    
        // Primer día del mes y número de días en el mes
        const primerDiaObj = new Date(añoActual, mesActual, 1);
        const primerDiaSemana = primerDiaObj.getDay(); // 0 (Dom) - 6 (Sáb)
        const diasEnMes = new Date(añoActual, mesActual + 1, 0).getDate();
    
        // Rellenar días vacíos al inicio del mes
        for (let i = 0; i < primerDiaSemana; i++) {
            const diaVacio = document.createElement('div');
            diaVacio.className = 'dia-calendario dia-vacio';
            calendarioDiv.appendChild(diaVacio);
        }
    
        // Generar días del mes
        for (let dia = 1; dia <= diasEnMes; dia++) {
            const diaElem = document.createElement('div');
            const fechaDiaActual = new Date(añoActual, mesActual, dia);
            
            diaElem.className = 'dia-calendario';
            // Solo permitir seleccionar días desde hoy en adelante
            if (fechaDiaActual < hoy && fechaDiaActual.toDateString() !== hoy.toDateString()) {
                diaElem.classList.add('dia-pasado');
            } else {
                diaElem.addEventListener('click', () => abrirModalHorarios(fechaDiaActual));
            }
            diaElem.dataset.fecha = fechaDiaActual.toISOString().split('T')[0]; // YYYY-MM-DD
            diaElem.innerHTML = `<span>${dia}</span>`;
            calendarioDiv.appendChild(diaElem);
        }
    }
    
    async function abrirModalHorarios(fechaObj) {
        if (!modalHorarios || !docenteSeleccionadoParaReserva || !horarioBtnsContainer) return;
        
        diaSeleccionadoParaReserva = fechaObj; // Guardar el objeto Date
        const fechaFormatoAPI = fechaObj.toISOString().split('T')[0]; // YYYY-MM-DD

        // Actualizar título del modal
        const modalTitle = modalHorarios.querySelector('h2');
        if(modalTitle) modalTitle.textContent = `Horarios para ${docenteSeleccionadoParaReserva.name} el ${fechaObj.toLocaleDateString()}`;

        // Limpiar horarios anteriores y mostrar "cargando"
        horarioBtnsContainer.innerHTML = '<p>Cargando horarios...</p>';
        modalHorarios.style.display = 'flex';

        try {
            const response = await fetch(`${BASE_URL}/reservas/horariosOcupados?idDocente=${docenteSeleccionadoParaReserva.id}&fechaTutoria=${fechaFormatoAPI}`);
            if (!response.ok) {
                throw new Error(`Error HTTP ${response.status}: ${response.statusText}`);
            }
            const data = await response.json();

            if (data.exito && data.horarios) {
                renderizarBotonesDeHorario(data.horarios);
            } else {
                horarioBtnsContainer.innerHTML = `<p>No se pudieron cargar los horarios. ${data.mensaje || ''}</p>`;
            }
        } catch (error) {
            console.error('Error al obtener horarios ocupados:', error);
            horarioBtnsContainer.innerHTML = `<p>Error al cargar horarios. Intenta de nuevo.</p>`;
        }
    }

    function renderizarBotonesDeHorario(horariosOcupados) {
        if (!horarioBtnsContainer) return;
        horarioBtnsContainer.innerHTML = ''; // Limpiar

        // Definir los bloques horarios disponibles estándar para un docente
        const bloquesDisponibles = [
            "08:00 AM - 09:00 AM", "09:00 AM - 10:00 AM", "10:00 AM - 11:00 AM",
            "11:00 AM - 12:00 PM", "01:00 PM - 02:00 PM", "02:00 PM - 03:00 PM",
            "03:00 PM - 04:00 PM", "04:00 PM - 05:00 PM", "05:00 PM - 06:00 PM"
        ];

        if (bloquesDisponibles.length === 0) {
            horarioBtnsContainer.innerHTML = "<p>No hay horarios configurados para este día.</p>";
            return;
        }

        let algunHorarioLibre = false;
        bloquesDisponibles.forEach(bloque => {
            const btn = document.createElement('button');
            btn.className = 'boton-horario';
            btn.textContent = bloque;
            if (horariosOcupados.includes(bloque)) {
                btn.disabled = true;
                btn.title = "Horario no disponible";
            } else {
                algunHorarioLibre = true;
                btn.addEventListener('click', () => {
                    // Deseleccionar otros y seleccionar este
                    horarioBtnsContainer.querySelectorAll('.boton-horario.seleccionado').forEach(b => b.classList.remove('seleccionado'));
                    btn.classList.add('seleccionado');
                });
            }
            horarioBtnsContainer.appendChild(btn);
        });
        if (!algunHorarioLibre && bloquesDisponibles.length > 0) {
             horarioBtnsContainer.innerHTML = "<p>No hay horarios disponibles para este día.</p>";
        }
    }


    function cerrarModalHorarios() {
        if (!modalHorarios) return;
        modalHorarios.style.display = 'none';
        // diaSeleccionadoParaReserva se mantiene para la confirmación
    }

    closeModalHorariosBtn?.addEventListener('click', cerrarModalHorarios);
    window.addEventListener('click', (event) => { if (modalHorarios && event.target === modalHorarios) cerrarModalHorarios(); });

    // ELIMINADO: Event listener para horarioBtns aquí, se añaden dinámicamente en renderizarBotonesDeHorario

    // ***** MODIFICACIÓN IMPORTANTE AQUÍ *****
    confirmReservarBtn?.addEventListener('click', async () => {
        const selectedHorarioBtn = horarioBtnsContainer ? horarioBtnsContainer.querySelector('.boton-horario.seleccionado') : null;
        
        if (!docenteSeleccionadoParaReserva || !diaSeleccionadoParaReserva || !selectedHorarioBtn) {
            alert('Por favor, selecciona un docente, un día y un horario.');
            return;
        }

        const horario = selectedHorarioBtn.textContent.trim();
        const fechaTutoria = diaSeleccionadoParaReserva.toISOString().split('T')[0]; // Formato YYYY-MM-DD
        const idDocente = docenteSeleccionadoParaReserva.id;
        const materiaDocente = docenteSeleccionadoParaReserva.subject; // Materia principal del docente

        const formData = new FormData();
        formData.append('id_docente', idDocente);
        formData.append('fecha_tutoria', fechaTutoria);
        formData.append('bloque_horario', horario);
        formData.append('materia', materiaDocente);

        try {
            // console.log('Enviando reserva:', {id_docente: idDocente, fecha_tutoria: fechaTutoria, bloque_horario: horario, materia: materiaDocente});
            const response = await fetch(`${BASE_URL}/reservas/crear`, {
                method: 'POST',
                body: formData
            });
            
            const resultado = await response.json();
            // console.log('Respuesta del servidor:', resultado);

            if (resultado.exito) {
                alert(resultado.mensaje + (resultado.id_agendamiento ? ` ID de reserva: R-${resultado.id_agendamiento}` : ''));
                cerrarModalHorarios();
                // Opcional: Actualizar el calendario para mostrar este horario como ocupado (si es necesario inmediatamente)
                // o redirigir a "Mis Agendamientos"
                window.location.href = `${BASE_URL}/reservas`; // Redirige para ver la reserva
            } else {
                alert('Error al reservar: ' + (resultado.mensaje || 'No se pudo completar la reserva.'));
            }
        } catch (error) {
            console.error('Error en la petición de reserva:', error);
            alert('Ocurrió un error de red al intentar realizar la reserva. Por favor, revisa la consola e inténtalo de nuevo.');
        }
    });

    // ELIMINADO: confirmEliminarBtn, ya que este flujo es solo para crear reservas.
    // La eliminación se manejaría en la vista de "Mis Agendamientos".

    // --- FILTROS Y BÚSQUEDA ---
    function updateSubjectTitle() {
        if (!subjectTitleDocentes) return;
        const materiasSeleccionadas = Array.from(filterMateriaCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        if (materiasSeleccionadas.length === 1) {
            subjectTitleDocentes.textContent = materiasSeleccionadas[0].toUpperCase();
        } else if (materiasSeleccionadas.length > 1) {
            subjectTitleDocentes.textContent = "VARIAS MATERIAS";
        } else if (materiaFiltradaDesdePHP && !filterMateriaCheckboxes.some(cb => cb.checked)) {
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
    // El botón de búsqueda explícito ahora se maneja por el input event, o puedes añadir un listener de 'click'
    const docentesSearchButton = document.getElementById('docentesSearchButton');
    docentesSearchButton?.addEventListener('click', applyFiltersAndRender);


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
        }
        applyFiltersAndRender(); 
    }
    if (cardsContainer && typeof teachersDataFromPHP !== 'undefined' && teachersDataFromPHP.length >= 0) {
        if(reservaContainer) reservaContainer.classList.add('reserva-vista-oculta');
        if(modalHorarios) modalHorarios.style.display = 'none';
        if(confirmEliminarBtn) confirmEliminarBtn.style.display = 'none'; // Ocultar botón de eliminar por defecto
        
        aplicarFiltrosInicialesPHP();
    }
});