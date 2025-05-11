<?php
// Los datos de los docentes vienen de $datos_layout['datos_vista']['docentes']
// que se convierten en $datos_vista['docentes'] en el layout.
$docentes = $datos_vista['docentes'] ?? [];
?>
<!-- La barra lateral de filtros se incluye aquí, pero su CSS debe estar en estilos_docentes_especificos.css -->
<aside id="filtrosDocentes" class="barra-lateral-filtros-docentes">
    <div class="barra-lateral__busqueda">
      <i class="fas fa-user-graduate barra-lateral__icono-busqueda"></i>
      <input type="text" placeholder="¿Qué quieres aprender hoy?" class="barra-lateral__entrada" id="docentesSearchInput">
      <button class="barra-lateral__boton-busqueda" id="docentesSearchButton">Buscar</button>
    </div>
    <div class="barra-lateral__grupo-filtro">
      <h4 class="barra-lateral__titulo-filtro">Áreas de estudio</h4>
      <label class="barra-lateral__item-filtro">
        <input type="checkbox" class="barra-lateral__checkbox-filtro filtro-materia" value="Cálculo"> Cálculo
      </label>
      <label class="barra-lateral__item-filtro">
        <input type="checkbox" class="barra-lateral__checkbox-filtro filtro-materia" value="Matemáticas"> Matemáticas
      </label>
      <label class="barra-lateral__item-filtro">
        <input type="checkbox" class="barra-lateral__checkbox-filtro filtro-materia" value="Química"> Química
      </label>
      <label class="barra-lateral__item-filtro">
        <input type="checkbox" class="barra-lateral__checkbox-filtro filtro-materia" value="Física"> Física
      </label>
    </div>
   <div class="barra-lateral__grupo-filtro">
    <h4 class="barra-lateral__titulo-filtro">Calificación</h4>
    <label class="barra-lateral__item-filtro">
        <input type="checkbox" class="barra-lateral__checkbox-filtro filtro-rating" value="5">
        <i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i>
    </label>
    <label class="barra-lateral__item-filtro">
        <input type="checkbox" class="barra-lateral__checkbox-filtro filtro-rating" value="4">
        <i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="far fa-star barra-lateral__icono-valoracion"></i> <!-- far para vacía -->
    </label>
    <label class="barra-lateral__item-filtro">
        <input type="checkbox" class="barra-lateral__checkbox-filtro filtro-rating" value="3">
        <i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="far fa-star barra-lateral__icono-valoracion"></i><i class="far fa-star barra-lateral__icono-valoracion"></i>
    </label>
  </div>
</aside>

<!-- Contenedor principal para la lista de docentes y el área de reserva -->
<div class="contenido-principal-docentes">
    <header class="barra-superior-docentes">
      <h1 id="subject-title-docentes" class="barra-superior__titulo-docentes">DOCENTES</h1>
      <div class="barra-superior__acciones-docentes">
        <button aria-label="Videos de YouTube"><i class="fab fa-youtube"></i></button>
        <button aria-label="Realidad Aumentada">AR</button>
      </div>
    </header>
    <section id="carouselDocentes" class="carrusel-docentes">
      <button class="carrusel__boton carrusel__boton--anterior" aria-label="Anterior"><i class="fas fa-chevron-left"></i></button>
      <div class="carrusel__tarjetas" id="cards-container-docentes">
          <!-- Las tarjetas de docentes se generan por JS (docentes_interacciones.js) -->
      </div>
      <button class="carrusel__boton carrusel__boton--siguiente" aria-label="Siguiente"><i class="fas fa-chevron-right"></i></button>
    </section>

    <!-- Vista de Reserva (inicialmente oculta) -->
    <div id="reserva-container-docentes" class="reserva-vista-oculta">
        <button id="volver-a-docentes-btn" class="boton-volver-docentes"><i class="fas fa-arrow-left"></i> Volver a Docentes</button>
        <div class="reserva-contenido">
            <div class="reserva-perfil">
                <img id="reserva-imagen-docente" src="" alt="Imagen del docente" class="reserva-imagen">
                <div id="reserva-calificacion-docente" class="reserva-calificacion"></div>
                <p id="reserva-desc-docente" class="reserva-descripcion"></p>
                <p id="reserva-exp-docente" class="reserva-experiencia"></p>
            </div>
            <div class="reserva-calendario-contenedor">
                <header class="reserva-header-calendario">
                <h2 id="reserva-nombre-docente" class="reserva-titulo-calendario"></h2>
                </header>
                <div class="reserva-calendario-header-dias">
                    <div>Dom</div><div>Lun</div><div>Mar</div><div>Mié</div>
                    <div>Jue</div><div>Vie</div><div>Sáb</div>
                </div>
                <div id="calendario-docente" class="reserva-calendario-dias"></div>
            </div>
        </div>
    </div>
</div>


<!-- Modal de Horarios (inicialmente oculto) -->
<div id="horarioModalDocentes" class="modal-horarios">
    <div class="modal-contenido">
      <span class="cerrar-modal" id="closeModalHorariosDocentes">×</span>
      <h2>Selecciona un horario</h2>
      <div class="horarios-grid">
        <button class="boton-horario">10AM - 11AM</button>
        <button class="boton-horario">11AM - 12PM</button>
        <button class="boton-horario">12PM - 1PM</button>
        <button class="boton-horario">1PM - 2PM</button>
        <button class="boton-horario">2PM - 3PM</button>
        <button class="boton-horario">4PM - 5PM</button>
      </div>
      <div class="acciones-modal">
        <button id="confirmReservarDocente" class="boton-modal">Reservar</button>
        <button id="confirmEliminarDocente" class="boton-modal">Eliminar Reserva</button>
      </div>
    </div>
</div>

<!-- Pasar datos de PHP a JavaScript -->
<script>
    const teachersDataFromPHP = <?= json_encode($docentes) ?>;
</script>