<?php
// Estas variables vienen de $datos_layout['datos_vista'] en ControladorInicio.php
// y se hacen disponibles aquí por el extract() en el método cargarLayout del controlador.
$mensaje_bienvenida = $datos_vista['mensaje_bienvenida'] ?? 'Bienvenido a TUTTOR-UP';
$subtitulo_bienvenida = $datos_vista['subtitulo_bienvenida'] ?? 'Explora nuestras opciones y encuentra lo que necesitas.';
?>

<div class="hero-inicio">
    <div class="hero-contenido">
        <h1><?= htmlspecialchars($mensaje_bienvenida) ?></h1>
        <p class="subtitulo-hero"><?= htmlspecialchars($subtitulo_bienvenida) ?></p>
        <div class="hero-cta-botones">
            <a href="<?= BASE_URL ?>/materias" class="btn-hero-accion">
                <i class="fas fa-shapes"></i> Ver Nuestras Materias
            </a>
            <a href="<?= BASE_URL ?>/docentes" class="btn-hero-accion btn-secundario">
                <i class="fas fa-chalkboard-teacher"></i> Encontrar un Tutor
            </a>
        </div>
    </div>
    <div class="hero-imagen-decorativa">
        <img src="<?= ASSETS_URL ?>/imagenes/logo.png" alt="Logo Decorativo Tuttor-Up" class="img-fluid-hero">
    </div>
</div>

<section class="seccion-destacada-inicio">
    <h2>¿Qué quieres aprender hoy?</h2>
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