<?php
// TUTORUP/publico/index.php
ini_set('display_errors', 1); // Es bueno tenerlo para desarrollo, comentar en producción
error_reporting(E_ALL);
session_start(); // Iniciar sesión para que esté disponible en toda la aplicación

// Cargar configuración principal (BASE_URL, ROOT_PATH, etc.)
require_once dirname(__DIR__) . '/config/config.php';

// --- Lógica de Enrutamiento ---

// 1. Obtener la ruta limpia después de la BASE_URL
$base_url_path = rtrim(parse_url(BASE_URL, PHP_URL_PATH), '/'); // rtrim para quitar la barra final de BASE_URL si la tiene
$request_uri_path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

$route = '';
if (strlen($base_url_path) > 0 && strpos($request_uri_path, $base_url_path) === 0) {
    $route = substr($request_uri_path, strlen($base_url_path));
} else if (empty($base_url_path)) { // Si BASE_URL es solo el host (ej. http://localhost:8888)
    $route = $request_uri_path;
}
$route = ltrim($route, '/'); // Quitar la barra inicial de la ruta relativa

// 2. Definir valores por defecto para controlador y método
$controladorNombre = 'ControladorInicio';
$metodoNombre = 'index';
$parametros = [];

// 3. Parsear la ruta para determinar controlador, método y parámetros
if (!empty($route)) {
    $partesRuta = explode('/', filter_var($route, FILTER_SANITIZE_URL));

    // Determinar el nombre del controlador
    if (!empty($partesRuta[0])) {
        $posibleControlador = strtolower($partesRuta[0]);
        if ($posibleControlador === 'auth') {
            $controladorNombre = 'ControladorAuth';
        } else {
            $controladorNombre = 'Controlador' . ucfirst($posibleControlador);
        }
    }

    // Determinar el nombre del método y los parámetros
    if ($controladorNombre === 'ControladorAuth') {
        if (isset($partesRuta[1]) && !empty($partesRuta[1])) {
            $metodoAuthDetectado = strtolower($partesRuta[1]);
            
            // Mapeo explícito de URLs a métodos para ControladorAuth
            $mapaMetodosAuth = [
                'loginform' => 'loginForm',
                'registroform' => 'registroForm',
                'procesarlogin' => 'procesarLogin',
                'procesarregistro' => 'procesarRegistro',
                'logout' => 'logout',
                'index' => 'index' // Si /auth/ o /auth/index deben ir al formulario principal de auth
            ];
            if (array_key_exists($metodoAuthDetectado, $mapaMetodosAuth)) {
                $metodoNombre = $mapaMetodosAuth[$metodoAuthDetectado];
            } else {
                $metodoNombre = 'index'; // Método por defecto para /auth/ruta_invalida
            }
        } else {
            $metodoNombre = 'index'; // Para /auth/ (mostrará login o registro según la lógica del controlador)
        }
        if (count($partesRuta) > 2) { // Aunque los métodos de auth usualmente no toman parámetros de URL
            $parametros = array_slice($partesRuta, 2);
        }
    } else { // Para otros controladores (Inicio, Materias, Docentes, etc.)
        if (isset($partesRuta[1]) && !empty($partesRuta[1])) {
            $metodoNombre = strtolower($partesRuta[1]);
        if ($controladorNombre === 'ControladorReservas' && $metodoNombre === 'cancelar' && isset($partesRuta[2])) {
            $parametros = [$partesRuta[2]]; // El ID del agendamiento es el primer parámetro
        }     
            if (count($partesRuta) > 2) {
                $parametros = array_slice($partesRuta, 2);
            }
        } else {
            // Si solo se especifica el controlador (ej. /materias), el método es 'index' por defecto
            $metodoNombre = 'index';
            if (count($partesRuta) > 1) { // Si la URL era /materias/algo/mas, 'algo' sería el primer parámetro
                $parametros = array_slice($partesRuta, 1); // En este caso no aplica porque el método es index
                                                          // Pero si un método 'index' aceptara parámetros, iría aquí.
                                                          // Para /controlador sin método, no hay parámetros de este tipo.
            }
        }
    }
}
// Si $route está vacía, se usarán los defaults: ControladorInicio, index

// --- Cargar y Ejecutar el Controlador y Método ---
$archivoControlador = ROOT_PATH . '/app/controladores/' . $controladorNombre . '.php';

if (file_exists($archivoControlador)) {
    require_once $archivoControlador;
    if (class_exists($controladorNombre)) {
        try {
            $controlador = new $controladorNombre(); // Instanciar el controlador

            if (method_exists($controlador, $metodoNombre)) {
                // Llamar al método con los parámetros (si los hay)
                call_user_func_array([$controlador, $metodoNombre], $parametros);
            } else {
                // Si el método específico no existe, verificar si existe un método 'index' en ese controlador
                // (esto es un fallback útil para URLs como /controlador/metodo_inexistente)
                // O si la URL fue solo /controlador y ese controlador no tiene 'index' explícitamente.
                if ($controladorNombre !== 'ControladorInicio' && method_exists($controlador, 'index')) {
                    call_user_func_array([$controlador, 'index'], $parametros); // Podrías pasar $partesRuta[1] como param si es relevante
                } else {
                    header("HTTP/1.0 404 Not Found");
                    echo "Error 404: Método '" . htmlspecialchars($metodoNombre) . "' no encontrado en el controlador '" . htmlspecialchars($controladorNombre) . "'.";
                }
            }
        } catch (Throwable $e) { // Captura excepciones y errores fatales durante la instanciación o ejecución
            // En producción, loguearías este error y mostrarías una página de error amigable.
            error_log("Error en el router: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
            header("HTTP/1.0 500 Internal Server Error");
            echo "Ocurrió un error inesperado. Por favor, inténtalo de nuevo más tarde.";
            // echo "DEBUG: " . $e->getMessage() . "<br><pre>" . $e->getTraceAsString() . "</pre>"; // Para depuración
        }
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "Error 404: Clase del controlador '" . htmlspecialchars($controladorNombre) . "' no encontrada en el archivo: " . htmlspecialchars($archivoControlador);
    }
} else {
    // Manejo para cuando el archivo del controlador no existe
    // Si la ruta estaba vacía, ya se intentó ControladorInicio, y si ese archivo no existe, entonces es un 404.
    // Si la ruta no estaba vacía pero el archivo del controlador calculado no existe:
    header("HTTP/1.0 404 Not Found");
    echo "Error 404: Archivo del controlador '" . htmlspecialchars($controladorNombre) . "' no encontrado en la ruta: " . htmlspecialchars($archivoControlador) . ". Ruta solicitada por el usuario: '" . htmlspecialchars($route) . "'";
}
?>