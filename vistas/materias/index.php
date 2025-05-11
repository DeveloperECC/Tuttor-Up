<?php
// Simulación de constantes para que el HTML funcione de forma aislada
// En la aplicación real, estas vendran de config.php o similar
if (!defined('ASSETS_URL')) {
    define('ASSETS_URL', '.'); // Ruta relativa para assets si se ejecuta directamente
}
if (!defined('BASE_URL')) {
    define('BASE_URL', '.');   // Ruta base para enlaces
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUTTOR-UP - Materias</title>
    <!-- Asegúrate que las rutas a tus CSS son correctas -->
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/variables.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

</head>
<body>
    <aside class="menu-lateral">
        <div class="menu-lateral__logo-container">
             <img src="<?= ASSETS_URL ?>/imagenes/logo.png" alt="Logo Tuttor-Up" class="menu-lateral__logo-img">
            <span class="menu-lateral__logo-text">TUTTOR-UP</span>
        </div>

        <nav> <!-- Envolver en nav por semántica -->
            <ul class="menu-lateral__lista">
                <li class="menu-lateral__item">
                    <a href="<?= BASE_URL ?>" class="menu-lateral__enlace menu-lateral__enlace--activo"> <!-- Ejemplo de clase activa -->
                        <i class="fas fa-home menu-lateral__icono"></i>
                        <span class="menu-lateral__texto">Inicio</span>
                    </a>
                </li>

                <li class="menu-lateral__item has-submenu"> <!-- Añadir clase 'has-submenu' si tiene submenú -->
                    <a href="#" class="menu-lateral__enlace"> <!-- El href="#" evita navegación si es solo para desplegar -->
                        <i class="fas fa-book menu-lateral__icono"></i>
                        <span class="menu-lateral__texto">Materias</span>
                        <i class="fas fa-chevron-down submenu-flecha"></i>
                    </a>
                    <ul class="menu-lateral__submenu">
                         <li><a href="<?= BASE_URL ?>/?accion=calculo" class="menu-lateral__submenu-enlace"><i class="fas fa-square-root-alt menu-lateral__submenu-icono"></i> Cálculo</a></li>
                        <li><a href="<?= BASE_URL ?>/?accion=fisica" class="menu-lateral__submenu-enlace"><i class="fas fa-atom menu-lateral__submenu-icono"></i> Física</a></li>
                        <li><a href="<?= BASE_URL ?>/?accion=matematicas" class="menu-lateral__submenu-enlace"><i class="fas fa-infinity menu-lateral__submenu-icono"></i> Matemáticas</a></li>
                        <li><a href="<?= BASE_URL ?>/?accion=quimica" class="menu-lateral__submenu-enlace"><i class="fas fa-flask menu-lateral__submenu-icono"></i> Química</a></li>
                        <!-- Icono de Curso Universitario Removido -->
                        <li><a href="<?= BASE_URL ?>/?accion=logica" class="menu-lateral__submenu-enlace"><i class="fas fa-brain menu-lateral__submenu-icono"></i> Lógica</a></li>
                         <li><a href="<?= BASE_URL ?>/?accion=fisicaAvanzada" class="menu-lateral__submenu-enlace"><i class="fas fa-atom menu-lateral__submenu-icono"></i> Física Avanzada</a></li>
                         <li><a href="<?= BASE_URL ?>/?accion=calculoAvanzado" class="menu-lateral__submenu-enlace"><i class="fas fa-square-root-alt menu-lateral__submenu-icono"></i> Cálculo Avanzado</a></li>
                         <li><a href="<?= BASE_URL ?>/?accion=matematicasIndustriales" class="menu-lateral__submenu-enlace"><i class="fas fa-calculator menu-lateral__submenu-icono"></i> Matemáticas Industriales</a></li>
                         <li><a href="<?= BASE_URL ?>/?accion=cienciasAmbientales" class="menu-lateral__submenu-enlace"><i class="fas fa-leaf menu-lateral__submenu-icono"></i> Ciencias Ambientales</a></li>
                    </ul>
                </li>
                <li class="menu-lateral__item">
                    <a href="<?= BASE_URL ?>/docentes" class="menu-lateral__enlace">
                        <i class="fas fa-chalkboard-teacher menu-lateral__icono"></i>
                        <span class="menu-lateral__texto">Docentes</span>
                    </a>
                </li>
                </ul>
        </nav>
    </aside>

    <main class="contenido-principal">
        <header class="header">
             <div class="search-bar">
                 <input type="text" placeholder="¿Qué quieres aprender hoy?" class="busqueda-input">
                 <button type="button"><i class="fas fa-search"></i></button> <!-- Añadido type="button" -->
            </div>
             <div class="right-icons">
                 <button type="button" class="icon-button"><i class="fas fa-bell"></i></button> <!-- Añadido type="button" -->
                 <button type="button" class="icon-button"><i class="fas fa-user-circle"></i></button> <!-- Añadido type="button" -->
                 <a href="#" class="icon-button ar-button"> <i class="fas fa-vr-cardboard"></i>
                     <span class="ar-text">AR</span>
                 </a>
            </div>
        </header>

        <div class="contenedor-materias">
            <!-- Tarjeta de Cálculo -->
            <a href="<?= BASE_URL ?>/?accion=calculo" class="materia-card calculo">
                <div class="materia-content">
                     <div class="materia-image-placeholder">
                          <i class="fas fa-square-root-alt"></i> <!-- fa-4x se controla con font-size en CSS -->
                     </div>
                     <div class="materia-info">
                        <span class="materia-codigo">CAL<br>CU<br>LO</span>
                        <p class="materia-descripcion">Explore el fascinante mundo del cambio y sus aplicaciones.</p>
                     </div>
                </div>
            </a>

            <!-- Tarjeta de Física -->
            <a href="<?= BASE_URL ?>/?accion=fisica" class="materia-card fisica">
                 <div class="materia-content">
                     <div class="materia-image-placeholder">
                          <i class="fas fa-atom"></i>
                     </div>
                     <div class="materia-info">
                         <span class="materia-codigo">FI<br>SI<br>CA</span>
                         <p class="materia-descripcion">Entiende las leyes fundamentales que rigen el universo.</p>
                     </div>
                 </div>
            </a>

            <!-- Tarjeta de Química -->
             <a href="<?= BASE_URL ?>/?accion=quimica" class="materia-card quimica">
                 <div class="materia-content">
                     <div class="materia-image-placeholder">
                          <i class="fas fa-flask"></i>
                     </div>
                     <div class="materia-info">
                        <span class="materia-codigo">QUI<br>MI<br>CA</span>
                        <p class="materia-descripcion">Descubre la composición, estructura y propiedades de la materia.</p>
                     </div>
                 </div>
             </a>

            <!-- Tarjeta de Matemáticas -->
             <a href="<?= BASE_URL ?>/?accion=matematicas" class="materia-card matematica">
                 <div class="materia-content">
                     <div class="materia-image-placeholder">
                          <i class="fas fa-infinity"></i>
                     </div>
                     <div class="materia-info">
                        <span class="materia-codigo">MATE<br>MATI<br>CAS</span>
                        <p class="materia-descripcion">La base de toda ciencia, tecnología e ingeniería.</p>
                     </div>
                 </div>
             </a>
         </div>
    </main>

    <!-- Si necesitas JS para el submenú, asegúrate de incluirlo -->
    <!-- <script src="<?= ASSETS_URL ?>/js/menu.js"></script> -->
    <!-- <script src="<?= ASSETS_URL ?>/js/busqueda.js"></script> -->
    <script>
        // Script simple para toggle de submenús si no tienes un menu.js complejo
        document.querySelectorAll('.menu-lateral__item.has-submenu > .menu-lateral__enlace').forEach(enlace => {
            enlace.addEventListener('click', function(event) {
                // Prevenir navegación si el enlace es solo para desplegar (ej. href="#")
                if (this.getAttribute('href') === '#') {
                    event.preventDefault();
                }
                const itemPadre = this.closest('.menu-lateral__item.has-submenu');
                if (itemPadre) {
                    itemPadre.classList.toggle('submenu-abierto');
                }
            });
        });
    </script>
</body>
</html>