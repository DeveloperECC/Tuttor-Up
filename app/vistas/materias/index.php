<?php
$materias = $datos_vista['materias_data'] ?? [];
$termino_busqueda = $datos_vista['termino_busqueda'] ?? null;
?>

<?php if ($termino_busqueda): ?>
    <h2 class="titulo-seccion">Resultados para: "<?= htmlspecialchars($termino_busqueda) ?>"</h2>
<?php else: ?>
    <h2 class="titulo-seccion">Explora Nuestras Materias</h2>
<?php endif; ?>

<div class="contenedor-materias">
    <?php if (!empty($materias)): ?>
        <?php foreach ($materias as $materia): ?>
            <a href="<?= BASE_URL . htmlspecialchars($materia['accion_url']) ?>" class="materia-card <?= htmlspecialchars($materia['clase_css']) ?>">
                <div class="materia-content">
                     <div class="materia-image-placeholder"><i class="<?= htmlspecialchars($materia['icono_fa']) ?>"></i></div>
                     <div class="materia-info">
                        <span class="materia-codigo"><?= $materia['codigo_html'] // Asume que el HTML <br> ya está en el dato ?></span>
                        <p class="materia-descripcion"><?= htmlspecialchars($materia['descripcion']) ?></p>
                     </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="sin-resultados">No se encontraron materias<?= $termino_busqueda ? ' para "'.htmlspecialchars($termino_busqueda).'"' : '' ?>.</p>
    <?php endif; ?>
 </div>
 <style>
    .titulo-seccion { 
        text-align: center; 
        font-size: 2rem; 
        margin-bottom: 25px; 
        color: var(--color-tuttor-up-green);
    }
    .sin-resultados {
        grid-column: 1 / -1; /* Ocupar todo el ancho del grid */
        text-align: center;
        font-size: 1.2rem;
        color: var(--text-color-muted);
        padding: 20px;
    }
 </style>