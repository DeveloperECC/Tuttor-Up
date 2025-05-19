<aside class="menu-lateral">
    <div class="menu-lateral__logo-container">
         <img src="<?= ASSETS_URL ?>/imagenes/logo.png" alt="Logo Tuttor-Up" class="menu-lateral__logo-img">
        <span class="menu-lateral__logo-text">TUTTOR-UP</span>
    </div>
    <nav>
        <ul class="menu-lateral__lista">
            <li class="menu-lateral__item">
                <a href="<?= BASE_URL ?>/" class="menu-lateral__enlace <?= (($controlador_actual ?? '') === 'ControladorInicio' && ($metodo_actual ?? '') === 'index') ? 'menu-lateral__enlace--activo' : '' ?>">
                    <i class="fas fa-home menu-lateral__icono"></i><span class="menu-lateral__texto">Inicio</span>
                </a>
            </li>

            <!-- MODIFICACIÓN: El <li> de Materias ya NO tiene la clase 'has-submenu' NI el <ul> de submenú -->
            <li class="menu-lateral__item">
                <!-- El enlace principal de "Materias" lleva a la vista del grid de materias -->
                <a href="<?= BASE_URL ?>/materias" class="menu-lateral__enlace <?= ($controlador_actual ?? '') === 'ControladorMaterias' && ($metodo_actual ?? '') === 'index' ? 'menu-lateral__enlace--activo' : '' ?>">
                    <i class="fas fa-book menu-lateral__icono"></i><span class="menu-lateral__texto">Materias</span>
                    <!-- Se quita la flecha del submenú: <i class="fas fa-chevron-down submenu-flecha"></i> -->
                </a>
                <!-- Se ELIMINA completamente el bloque <ul class="menu-lateral__submenu"> ... </ul> que estaba aquí -->
            </li>

            <li class="menu-lateral__item">
                <!-- Este enlace lleva a la vista general de todos los docentes -->
                <a href="<?= BASE_URL ?>/docentes" class="menu-lateral__enlace <?= ($controlador_actual ?? '') === 'ControladorDocentes' && (($metodo_actual ?? '') === 'index') ? 'menu-lateral__enlace--activo' : '' ?>">
                    <i class="fas fa-chalkboard-teacher menu-lateral__icono"></i><span class="menu-lateral__texto">Docentes</span>
                </a>
            </li>
            <li class="menu-lateral__item">
                <a href="<?= BASE_URL ?>/reservas" class="menu-lateral__enlace <?= ($controlador_actual ?? '') === 'ControladorReservas' ? 'menu-lateral__enlace--activo' : '' ?>">
                    <i class="fas fa-calendar-alt menu-lateral__icono"></i><span class="menu-lateral__texto">Agendamientos</span>
                </a>
            
            <li class="menu-lateral__item">
                <a href="<?= BASE_URL ?>/auth/logout" id="enlaceCerrarSesion" class="menu-lateral__enlace">
                    <i class="fas fa-sign-out-alt menu-lateral__icono"></i><span class="menu-lateral__texto">Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>