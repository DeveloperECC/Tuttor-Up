<?php
// Estas variables vienen de $datos_layout['datos_vista'] en ControladorInicio.php
// y se hacen disponibles aquí por el extract() en el método cargarLayout del controlador.
$mensaje_bienvenida = $datos_vista['mensaje_bienvenida'] ?? 'Bienvenido a TUTTOR-UP';

// El <br> ya está en la cadena, así que no se escapará al imprimir para que funcione como salto de línea.
// Si $datos_vista['subtitulo_bienvenida'] viene del controlador y ya contiene <br>, no necesita htmlspecialchars.
// Si viniera de una fuente no confiable y quisieras escapar todo MENOS el <br>,
// la sanitización debería hacerse en el controlador antes de pasar el dato.
// Para el texto de fallback, el <br> es seguro.
$subtitulo_bienvenida = $datos_vista['subtitulo_bienvenida'] ?? 'Tu plataforma ideal para encontrar el tutor perfecto y potenciar tu aprendizaje.<br>Navega a través del menú lateral y descubre todo lo que tenemos para ofrecerte.';
?>

<div class="hero-inicio">
    <div class="hero-contenido">
        <h1><?= htmlspecialchars($mensaje_bienvenida) // Siempre es buena idea escapar el contenido dinámico principal ?></h1>
        <p class="subtitulo-hero"><?= $subtitulo_bienvenida // No usamos htmlspecialchars aquí para permitir que <br> funcione ?></p>
        <div class="hero-cta-botones">
            <a href="<?= BASE_URL ?>/materias" class="btn-hero-accion">
                <i class="fas fa-shapes"></i> Ver Nuestras Materias
            </a>
            <a href="<?= BASE_URL ?>/docentes" class="btn-hero-accion btn-secundario">
                <i class="fas fa-chalkboard-teacher"></i> Encontrar un Tutor
            </a>
        </div>
    
</div>

<section class="seccion-destacada-inicio">
    <h1>Navega a traves de nuestro menú lateral , y entérate de lo que tenemos para ofrecerte</h1>
    <div class="acciones-rapidas-inicio">
        <a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/calculo" class="accion-rapida-card">
            <i class="fas fa-calculator"></i>
            <span>Cálculo</span>
        </a>
        <a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/fisica" class="accion-rapida-card">
            <i class="fas fa-atom"></i>
            <span>Física</span>
        </a>
        <a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/matematicas" class="accion-rapida-card">
            <i class="fas fa-infinity"></i>
            <span>Matemáticas</span>
        </a>
        <a href="<?= BASE_URL ?>/docentes/filtrarPorMateria/quimica" class="accion-rapida-card">
            <i class="fas fa-flask"></i>
            <span>Química</span>
        </a>
    </div>
</section>