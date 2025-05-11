<?php
// TUTORUP/controladores/ControladorBase.php
class ControladorBase {
    /**
     * Carga una vista dentro de la plantilla principal.
     * @param string $nombreVistaRelativa Ruta a la vista desde la carpeta 'vistas/' (ej. 'materias/index').
     * @param array $datos Datos para pasar a la vista.
     */
    protected function vista($nombreVistaRelativa, $datos = []) {
        if (file_exists(APPROOT . '/vistas/' . $nombreVistaRelativa . '.php')) {
            // Extraer los datos para que estén disponibles como variables en la vista y plantilla
            extract($datos);

            // $rutaVistaEspecifica se usará dentro de principal.php para incluir el contenido correcto
            $rutaVistaEspecifica = APPROOT . '/vistas/' . $nombreVistaRelativa . '.php';

            // Cargar la plantilla principal
            require_once APPROOT . '/vistas/plantillas/principal.php';
        } else {
            die('Error en ControladorBase: Vista específica no encontrada en ' . APPROOT . '/vistas/' . $nombreVistaRelativa . '.php');
        }
    }
}
?>