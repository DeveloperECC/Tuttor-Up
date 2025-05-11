<?php
// TUTORUP/config/config.php

// Define la ruta raíz del proyecto (un nivel arriba de 'config')
define('ROOT_PATH', dirname(__DIR__));

// Define la URL base del proyecto
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];

// Asumiendo que el DocumentRoot de tu servidor apunta a TUTORUP/publico/
// Si no, necesitarás ajustar $script_name_base
// Ejemplo: Si TUTORUP está en localhost/TUTORUP/, y accedes a /TUTORUP/publico/index.php
// dirname($_SERVER['SCRIPT_NAME']) sería /TUTORUP/publico
// y si quieres que BASE_URL sea http://localhost/TUTORUP, debes quitar /publico.

$script_name_path = dirname($_SERVER['TUTTOR-UP/publico']);
// Si script_name_path termina con /publico (o \publico en Windows), lo quitamos.
if (substr($script_name_path, -7) === '/publico' || substr($script_name_path, -7) === '\\publico') {
    $base_dir = substr($script_name_path, 0, -7);
} else {
    // Si el DocumentRoot ya es /publico, dirname($_SERVER['SCRIPT_NAME']) podría ser solo '/' o el nombre del subdirectorio
    // donde está publico si no es la raíz del host.
    // Esta parte es la más dependiente de tu configuración de servidor.
    // Para el caso donde DocumentRoot es publico/ y el proyecto está en la raíz del host:
    // $base_dir = '';
    // Si el proyecto está en localhost/TUTORUP/ y DocumentRoot es TUTORUP/publico/:
    // $base_dir = '/TUTORUP';
    // Vamos a asumir un caso común donde el proyecto está en un subdirectorio
    // y el DocumentRoot es la carpeta padre de 'publico'.
    $base_dir = $script_name_path == '/' ? '' : $script_name_path; // Simplificación, ajustar si es necesario
}
// Asegurar que no termine con / si no es la raíz
$base_dir = rtrim($base_dir, '/');


define('BASE_URL', $protocol . $host . $base_dir);
define('ASSETS_URL', BASE_URL . '/publico'); // Los assets están dentro de publico

// Habilitar muestra de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>