<?php
// TUTORUP/publico/index.php
session_start(); // Iniciar sesión si se va a usar

// Cargar configuración
require_once dirname(__DIR__) . '/config/config.php';

// Lógica de Enrutamiento Simple
// Obtener la ruta de la URL después de la BASE_URL.
// Ej: Si BASE_URL es http://localhost/TUTORUP y la URL es http://localhost/TUTORUP/materias/calculo
// $route será /materias/calculo
$base_url_path = parse_url(BASE_URL, PHP_URL_PATH);
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($base_url_path && strpos($request_uri, $base_url_path) === 0) {
    $route = substr($request_uri, strlen($base_url_path));
} else {
    $route = $request_uri; // Caso raíz del dominio
}
$route = ltrim($route, '/');

// Valores por defecto
$controladorNombre = 'ControladorInicio';
$metodoNombre = 'index';
$parametros = [];

if (!empty($route)) {
    $partesRuta = explode('/', filter_var(rtrim($route, '/'), FILTER_SANITIZE_URL));

    // Controlador
    if (!empty($partesRuta[0])) {
        $controladorNombre = 'Controlador' . ucfirst(strtolower($partesRuta[0]));
    }

    // Método
    if (isset($partesRuta[1]) && !empty($partesRuta[1])) {
        $metodoNombre = strtolower($partesRuta[1]);
    }

    // Parámetros
    if (count($partesRuta) > 2) {
        $parametros = array_slice($partesRuta, 2);
    }
}

// Cargar archivo del controlador
$archivoControlador = ROOT_PATH . '/app/controladores/' . $controladorNombre . '.php';

if (file_exists($archivoControlador)) {
    require_once $archivoControlador;
    if (class_exists($controladorNombre)) {
        $controlador = new $controladorNombre();
        if (method_exists($controlador, $metodoNombre)) {
            // Llamar al método con parámetros
            call_user_func_array([$controlador, $metodoNombre], $parametros);
        } else {
            // Método no encontrado
            header("HTTP/1.0 404 Not Found");
            echo "Error 404: Método '$metodoNombre' no encontrado en '$controladorNombre'.";
        }
    } else {
        // Clase del controlador no encontrada
        header("HTTP/1.0 404 Not Found");
        echo "Error 404: Controlador '$controladorNombre' (clase) no encontrado.";
    }
} else {
    // Archivo del controlador no encontrado. Si es ControladorInicio, intentamos cargar el index por defecto.
    if ($controladorNombre === 'ControladorInicio' && file_exists(ROOT_PATH . '/app/controladores/ControladorInicio.php')) {
        require_once ROOT_PATH . '/app/controladores/ControladorInicio.php';
        $controlador = new ControladorInicio();
        $controlador->index();
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Error 404: Controlador '$controladorNombre' (archivo) no encontrado. Ruta solicitada: '$route'";
    }
}
?>