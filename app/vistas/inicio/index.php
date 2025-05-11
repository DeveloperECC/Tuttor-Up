<div class="contenedor-inicio">
    <h1><?= htmlspecialchars($datos_vista['titulo_pagina'] ?? $titulo_pagina) ?></h1>
    <p>Bienvenido a la plataforma TUTTOR-UP. Navega por nuestras secciones usando el menú lateral.</p>
    <div class="acciones-inicio">
        <a href="<?= BASE_URL ?>/materias/mostrar" class="btn-accion-inicio">
            <i class="fas fa-book"></i> Ver Materias
        </a>
        <a href="<?= BASE_URL ?>/docentes" class="btn-accion-inicio">
            <i class="fas fa-chalkboard-teacher"></i> Conocer Docentes
        </a>
        <a href="<?= BASE_URL ?>/reservas" class="btn-accion-inicio">
            <i class="fas fa-calendar-alt"></i> Mis Agendamientos
        </a>
    </div>
</div>
<style>
    .contenedor-inicio { text-align: center; padding: 40px 20px; }
    .contenedor-inicio h1 { font-size: 2.5rem; margin-bottom: 20px; color: var(--color-tuttor-up-green); }
    .contenedor-inicio p { font-size: 1.1rem; margin-bottom: 30px; color: var(--text-color-light); }
    .acciones-inicio { display: flex; justify-content: center; gap: 20px; flex-wrap: wrap; }
    .btn-accion-inicio {
        background-color: var(--color-tuttor-up-green);
        color: var(--text-color-dark);
        padding: 15px 25px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 1rem;
        font-weight: 500;
        transition: background-color 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-accion-inicio:hover { background-color: #65d8c0; }
</style>