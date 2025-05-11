<?php $materia = $datos_vista['materia'] ?? null; ?>

<div class="detalle-materia-container">
    <?php if ($materia): ?>
        <h2 class="titulo-detalle">Detalle de: <?= htmlspecialchars($materia['nombre']) ?></h2>
        <div class="info-materia">
            <p><strong>Código:</strong> <?= htmlspecialchars($materia['codigo']) ?></p>
            <p><strong>Nombre Corto:</strong> <?= htmlspecialchars($materia['subtitulo'] ?? 'N/A') ?></p>
            <p><strong>Variable de Color CSS:</strong> <?= htmlspecialchars($materia['color_var'] ?? $materia['color'] ?? 'No definido') ?></p>
            <p><strong>Icono:</strong> <i class="<?= htmlspecialchars($materia['icono']) ?>"></i></p>
            
            <!-- Aquí podrías añadir más información relevante de la materia -->
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam euismod, nisi vel consectetur interdum, nisl nunc egestas nunc, vitae tincidunt nisl nunc euismod nunc.</p>
        </div>
        <a href="<?= BASE_URL ?>/materias/mostrar" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Volver a todas las materias
        </a>
    <?php else: ?>
        <h2 class="titulo-detalle">Materia no encontrada</h2>
        <p class="mensaje-error">La materia que buscas no existe o no está disponible en este momento.</p>
        <a href="<?= BASE_URL ?>/materias/mostrar" class="btn-volver">
            <i class="fas fa-arrow-left"></i> Ver todas las materias
        </a>
    <?php endif; ?>
</div>
<style>
    .detalle-materia-container {
        padding: 20px;
        background-color: rgba(37, 41, 48, 0.5); /* Fondo semi-transparente */
        border-radius: 8px;
        max-width: 800px;
        margin: 20px auto;
        color: var(--text-color-light);
    }
    .titulo-detalle {
        color: var(--color-tuttor-up-green);
        margin-bottom: 20px;
        text-align: center;
        font-size: 1.8rem;
    }
    .info-materia p {
        margin-bottom: 10px;
        font-size: 1rem;
        line-height: 1.6;
    }
    .info-materia strong {
        color: var(--text-color-muted);
    }
    .btn-volver {
        display: inline-block;
        margin-top: 20px;
        padding: 10px 15px;
        background-color: var(--color-tuttor-up-green);
        color: var(--text-color-dark);
        text-decoration: none;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }
    .btn-volver i { margin-right: 8px; }
    .btn-volver:hover {
        background-color: #65d8c0;
    }
    .mensaje-error {
        text-align: center;
        font-size: 1.1rem;
        color: #ffc107; /* Amarillo advertencia */
        margin-bottom: 20px;
    }
</style>