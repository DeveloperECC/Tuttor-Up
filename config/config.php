<?php
// TUTORUP/config/config.php

// Define la ruta raíz del proyecto (un nivel arriba de 'config', es decir, TUTORUP/)
define('ROOT_PATH', dirname(__DIR__));

// --- Cálculo de BASE_URL y ASSETS_URL ---
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST']; // Esto será 'localhost:8888'

// El subdirectorio donde reside tu proyecto, relativo a la raíz del host.
// En tu caso, es '/Tuttor-Up'.
$project_base_path = '/Tuttor-Up';

// BASE_URL apunta a la carpeta 'publico' dentro de tu proyecto, ya que ahí está el router.
define('BASE_URL', rtrim($protocol . $host . $project_base_path . '/publico', '/'));

// ASSETS_URL también apunta a la carpeta 'publico', ya que tus assets están ahí.
define('ASSETS_URL', BASE_URL);

// --- Depuración de Rutas (opcional, puedes comentar esto después) ---
// echo "DEBUG config.php:<br>";
// echo "Protocol: " . htmlspecialchars($protocol) . "<br>";
// echo "Host: " . htmlspecialchars($host) . "<br>";
// echo "Project Base Path: " . htmlspecialchars($project_base_path) . "<br>";
// echo "BASE_URL: " . htmlspecialchars(BASE_URL) . "<br>";
// echo "ASSETS_URL: " . htmlspecialchars(ASSETS_URL) . "<br>";
// echo "ROOT_PATH: " . htmlspecialchars(ROOT_PATH) . "<br><hr>";
// --- Fin Depuración ---

// Habilitar muestra de errores para desarrollo
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>