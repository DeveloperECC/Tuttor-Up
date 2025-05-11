<?php
// Configuración para redirección inteligente
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script_name = $_SERVER['SCRIPT_NAME'];

// Detectar la ruta base del proyecto automáticamente
$project_path = str_replace('/index.php', '', $script_name);

// Redirigir a la carpeta pública manteniendo cualquier parámetro de URL
if ($request_uri === $project_path || $request_uri === $project_path.'/index.php') {
    $query_string = $_SERVER['QUERY_STRING'] ? '?'.$_SERVER['QUERY_STRING'] : '';
    header('Location: '.$project_path.'/publico/index.php'.$query_string);
    exit;
}

// Si se accede directamente a este archivo sin coincidir con las condiciones anteriores
header('HTTP/1.1 404 Not Found');
echo 'Acceso no autorizado';
exit;
?>