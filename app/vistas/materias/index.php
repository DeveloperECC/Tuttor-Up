<?php
// Accedemos a las variables a través del array $datos_vista que fue creado por extract() en cargarLayout
$materias = $datos_vista['materias_data'] ?? [];
$termino_busqueda = $datos_vista['termino_busqueda'] ?? null; // Esto ahora debería funcionar porque $datos_vista['termino_busqueda'] está definido (como null)
?>

<?php if ($termino_busqueda): // Esto evaluará a false si $termino_busqueda es null ?>
    <h2 class="titulo-seccion">Resultados para: "<?= htmlspecialchars($termino_busqueda) ?>"</h2>
<?php else: ?>
    <h2 class="titulo-seccion-materias">Explora Nuestras Materias</h2>
<?php endif; ?>

<div class="contenedor-materias">
    <?php if (!empty($materias)): ?>
        <?php foreach ($materias as $materia): ?>
            <a href="<?= BASE_URL . htmlspecialchars($materia['accion_url']) ?>" class="materia-card <?= htmlspecialchars($materia['clase_css']) ?>">
                <div class="materia-content">
                     <div class="materia-image-placeholder"><i class="<?= htmlspecialchars($materia['icono_fa']) ?>"></i></div>
                     <div class="materia-info">
                        <span class="materia-codigo"><?= $materia['codigo_html'] ?></span>
                        <p class="materia-descripcion"><?= htmlspecialchars($materia['descripcion']) ?></p>
                     </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <!-- La línea 22 es esta. Ahora $termino_busqueda debería estar definido como null si no hay búsqueda. -->
        <p class="sin-resultados">No se encontraron materias<?= $termino_busqueda ? ' para "'.htmlspecialchars($termino_busqueda).'"' : '' ?>.</p>
    <?php endif; ?>
 </div>