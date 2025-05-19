<?php
// app/controladores/ControladorInicio.php
class ControladorInicio {
    private function cargarLayout($vista_contenido_path, $datos_para_layout) {
        extract($datos_para_layout);
        $vista_contenido = $vista_contenido_path;
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }
    public function index() {
        $datos_para_vista['titulo_pagina'] = "Bienvenido a TUTTOR-UP";
        $datos_para_vista['controlador_actual'] = 'ControladorInicio'; // Para el menú activo
        $datos_para_vista['metodo_actual'] = 'index';

        $datos_vista_especificos['subtitulo_bienvenida'] = "Tu plataforma ideal para encontrar el tutor perfecto y potenciar tu aprendizaje.";

        $datos_layout['titulo_pagina'] = "Inicio - TUTTOR-UP"; // Título para la pestaña del navegador
        $datos_layout['controlador_actual'] = 'ControladorInicio';
        $datos_layout['metodo_actual'] = 'index';
        $datos_layout['datos_vista'] = $datos_vista_especificos; // Pasar los datos específicos de la vista
        $datos_layout['mostrar_buscador_header'] = true;

        $this->cargarLayout(ROOT_PATH . '/app/vistas/inicio/index.php', $datos_layout);
    

        $vista_contenido = ROOT_PATH . '/app/vistas/inicio/index.php';
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }
}
?>