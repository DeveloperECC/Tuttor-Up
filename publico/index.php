<?php
// publico/index.php - Router Principal

// Configuración básica para mostrar errores (útil durante el desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Definir rutas base
// Asume que la carpeta 'publico' es la raíz de tu servidor web
define('ROOT_PATH', dirname(__DIR__)); // Ruta a la raíz del proyecto (la carpeta padre de publico)

// Construir la URL base para los enlaces (ej: http://localhost/tuttor-up)
// str_replace('/publico/index.php', '', $_SERVER['SCRIPT_NAME']) elimina '/publico/index.php' de la ruta del script
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('/publico/index.php', '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', $base_url);

// Definir la URL para los archivos estáticos (CSS, JS, imágenes)
define('ASSETS_URL', BASE_URL . '/publico');


// Incluir el controlador que maneja las acciones relacionadas con las materias
// Asegúrate de que la ruta sea correcta desde ROOT_PATH
require_once ROOT_PATH . '/controladores/ControladorMaterias.php';

// Obtener la acción solicitada desde el parámetro 'accion' en la URL
// Si no se especifica 'accion', el valor por defecto es 'mostrar'
$accion = $_GET['accion'] ?? 'mostrar';

// Crear una instancia del controlador de materias
$controlador = new ControladorMaterias();

// Verificar si el método correspondiente a la acción existe en el controlador
if (method_exists($controlador, $accion)) {
    // Si existe, llamar al método del controlador
    $controlador->$accion();
} else {
    // Si la acción no existe, ejecutar la acción por defecto (mostrar la página principal)
    // Esto puede servir como una página de error 404 simple o redirigir al inicio
    echo "Error: Acción no encontrada.";
    // O llamar a la acción por defecto:
    // $controlador->mostrar();
}