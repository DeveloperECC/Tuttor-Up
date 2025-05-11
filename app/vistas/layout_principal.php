<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo_pagina ?? 'TUTTOR-UP') ?></title>
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/variables.css">
    <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/estilos_globales.css">
    <?php if (isset($css_especifico) && is_array($css_especifico)): ?>
        <?php foreach ($css_especifico as $css_file): ?>
            <link rel="stylesheet" href="<?= ASSETS_URL ?>/css/<?= htmlspecialchars($css_file) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;700&family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php require_once ROOT_PATH . '/app/vistas/parciales/sidebar_unificado.php'; ?>

    <!-- Este div envuelve el header y el contenido principal para que el sidebar no lo afecte directamente -->
    <div class="main-wrapper">
        <?php require_once ROOT_PATH . '/app/vistas/parciales/header_unificado.php'; ?>
        
        <main class="contenido-pagina-actual">
            <?php require_once $vista_contenido; // Aquí se carga la vista específica ?>
        </main>

        <?php require_once ROOT_PATH . '/app/vistas/parciales/footer_unificado.php'; ?>
    </div>

    <script>
        const BASE_URL = '<?= rtrim(BASE_URL, '/') ?>'; // Asegurar que no haya / al final
        const ASSETS_URL = '<?= rtrim(ASSETS_URL, '/') ?>';
    </script>
    <script src="<?= ASSETS_URL ?>/js/menu_lateral.js"></script>
    <script src="<?= ASSETS_URL ?>/js/busqueda_header.js"></script>
    <script src="<?= ASSETS_URL ?>/js/script_global.js"></script>
    
    <?php if (isset($js_especifico) && is_array($js_especifico)): ?>
        <?php foreach ($js_especifico as $js_file): ?>
            <script src="<?= ASSETS_URL ?>/js/<?= htmlspecialchars($js_file) ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>