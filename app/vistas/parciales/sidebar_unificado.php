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
            <li class="menu-lateral__item has-submenu <?= ($controlador_actual ?? '') === 'ControladorMaterias' ? 'submenu-abierto' : '' ?>">
                <a href="<?= BASE_URL ?>/materias" class="menu-lateral__enlace <?= ($controlador_actual ?? '') === 'ControladorMaterias' ? 'menu-lateral__enlace--activo' : '' ?>">
                    <i class="fas fa-book menu-lateral__icono"></i><span class="menu-lateral__texto">Materias</span>
                    <i class="fas fa-chevron-down submenu-flecha"></i>
                </a>
                <ul class="menu-lateral__submenu">
                    <li><a href="<?= BASE_URL ?>/materias/calculo" class="menu-lateral__submenu-enlace"><i class="fas fa-square-root-alt menu-lateral__submenu-icono"></i> Cálculo</a></li>
                    <li><a href="<?= BASE_URL ?>/materias/fisica" class="menu-lateral__submenu-enlace"><i class="fas fa-atom menu-lateral__submenu-icono"></i> Física</a></li>
                    <li><a href="<?= BASE_URL ?>/materias/matematicas" class="menu-lateral__submenu-enlace"><i class="fas fa-infinity menu-lateral__submenu-icono"></i> Matemáticas</a></li>
                    <li><a href="<?= BASE_URL ?>/materias/quimica" class="menu-lateral__submenu-enlace"><i class="fas fa-flask menu-lateral__submenu-icono"></i> Química</a></li>
                    <li><a href="<?= BASE_URL ?>/materias/logica" class="menu-lateral__submenu-enlace"><i class="fas fa-brain menu-lateral__submenu-icono"></i> Lógica</a></li>
                    <!-- Añadir más materias si tienen rutas directas -->
                </ul>
                              
                <ul class="menu-lateral__submenu">
                    <li><a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/calculo" class="menu-lateral__submenu-enlace"><i class="fas fa-square-root-alt menu-lateral__submenu-icono"></i> Cálculo</a></li>
                    <li><a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/fisica" class="menu-lateral__submenu-enlace"><i class="fas fa-atom menu-lateral__submenu-icono"></i> Física</a></li>
                    <li><a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/matematicas" class="menu-lateral__submenu-enlace"><i class="fas fa-infinity menu-lateral__submenu-icono"></i> Matemáticas</a></li>
                    <li><a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/quimica" class="menu-lateral__submenu-enlace"><i class="fas fa-flask menu-lateral__submenu-icono"></i> Química</a></li>
                    <li><a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/logica" class="menu-lateral__submenu-enlace"><i class="fas fa-brain menu-lateral__submenu-icono"></i> Lógica</a></li>
                    <li><a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/calculo-avanzado" class="menu-lateral__submenu-enlace"><i class="fas fa-wave-square menu-lateral__submenu-icono"></i> Cálculo Avanzado</a></li>
                    <!-- ... y para las demás materias ... -->
                </ul>
            </li>
            <li class="menu-lateral__item">
                <a href="<?= BASE_URL ?>/docentes" class="menu-lateral__enlace <?= ($controlador_actual ?? '') === 'ControladorDocentes' ? 'menu-lateral__enlace--activo' : '' ?>">
                    <i class="fas fa-chalkboard-teacher menu-lateral__icono"></i><span class="menu-lateral__texto">Docentes</span>
                </a>
            </li>
            <li class="menu-lateral__item">
                <a href="<?= BASE_URL ?>/reservas" class="menu-lateral__enlace <?= ($controlador_actual ?? '') === 'ControladorReservas' ? 'menu-lateral__enlace--activo' : '' ?>">
                    <i class="fas fa-calendar-alt menu-lateral__icono"></i><span class="menu-lateral__texto">Agendamientos</span>
                </a>
            </li>
            <li class="menu-lateral__item">
                <a href="<?= BASE_URL ?>/configuracion" id="enlaceConfiguracion" class="menu-lateral__enlace <?= ($controlador_actual ?? '') === 'ControladorConfiguracion' ? 'menu-lateral__enlace--activo' : '' ?>">
        <i class="fas fa-cog menu-lateral__icono"></i><span class="menu-lateral__texto">Configuración</span>
    </a>
            </li>
            <li class="menu-lateral__item">
           <a href="<?= BASE_URL ?>/auth/logout" id="enlaceCerrarSesion" class="menu-lateral__enlace">
        <i class="fas fa-sign-out-alt menu-lateral__icono"></i><span class="menu-lateral__texto">Cerrar Sesión</span>
      </a>
</li>
        </ul>
    </nav>
</aside>