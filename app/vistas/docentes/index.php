<?php
$docentes_json = json_encode($datos_vista['docentes'] ?? []);
$materia_filtrada_json = isset($datos_vista['materia_filtrada']) ? json_encode($datos_vista['materia_filtrada']) : 'null';
?>

<div class="vista-docentes-wrapper">

    <aside id="filtrosDocentes" class="barra-lateral-filtros-docentes">
        <!-- ****** INICIO DE CAMBIO: Estructura de la barra de búsqueda ****** -->
        <div class="search-bar-docentes"> <!-- Nueva clase contenedora, similar a la del header -->
          <input type="text" placeholder="Buscar docente o materia..." class="busqueda-input-docentes" id="docentesSearchInput">
          <button type="button" class="search-button-docentes" id="docentesSearchButton" aria-label="Buscar">
              <i class="fas fa-search"></i>
          </button>
        </div>
        <!-- ****** FIN DE CAMBIO: Estructura de la barra de búsqueda ****** -->
        
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
        <h4 class="barra-lateral__titulo-filtro">Calificación Mínima</h4>
        <label class="barra-lateral__item-filtro">
            <input type="radio" name="filtro_rating_radio" class="barra-lateral__checkbox-filtro filtro-rating" value="5">
            <i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i>
            <span class="rating-text"> y más</span>
        </label>
        <label class="barra-lateral__item-filtro">
            <input type="radio" name="filtro_rating_radio" class="barra-lateral__checkbox-filtro filtro-rating" value="4">
            <i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="far fa-star barra-lateral__icono-valoracion"></i>
            <span class="rating-text"> y más</span>
        </label>
        <label class="barra-lateral__item-filtro">
            <input type="radio" name="filtro_rating_radio" class="barra-lateral__checkbox-filtro filtro-rating" value="3">
            <i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="fas fa-star barra-lateral__icono-valoracion"></i><i class="far fa-star barra-lateral__icono-valoracion"></i><i class="far fa-star barra-lateral__icono-valoracion"></i>
            <span class="rating-text"> y más</span>
        </label>
         <label class="barra-lateral__item-filtro">
            <input type="radio" name="filtro_rating_radio" class="barra-lateral__checkbox-filtro filtro-rating" value="0" checked>
            Todos
        </label>
      </div>
    </aside>

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
              <!-- Las tarjetas de docentes se generan por JS -->
          </div>
          <button class="carrusel__boton carrusel__boton--siguiente" aria-label="Siguiente"><i class="fas fa-chevron-right"></i></button>
        </section>

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
</div>

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

<script>
    const teachersDataFromPHP = <?= $docentes_json ?>;
    const materiaFiltradaDesdePHP = <?= $materia_filtrada_json ?>;
</script>