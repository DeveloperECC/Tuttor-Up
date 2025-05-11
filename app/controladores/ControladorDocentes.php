<?php
// app/controladores/ControladorDocentes.php
class ControladorDocentes {
    public function index() {
        require_once ROOT_PATH . '/app/modelos/ModeloDocentes.php';
        $modelo = new ModeloDocentes();
        
        $datos_vista['docentes'] = $modelo->obtenerTodos(); // Para pasar a JS en la vista

        $datos_layout['titulo_pagina'] = "Nuestros Docentes";
        $datos_layout['controlador_actual'] = 'ControladorDocentes';
        $datos_layout['metodo_actual'] = 'index';
        $datos_layout['css_especifico'] = ['estilos_docentes_especificos.css'];
        $datos_layout['js_especifico'] = ['docentes_interacciones.js'];
        $datos_layout['datos_vista'] = $datos_vista;

        $vista_contenido_path = ROOT_PATH . '/app/vistas/docentes/index.php';
        
        // Extraer datos para que estén disponibles en el layout y la vista
        extract($datos_layout);
        $vista_contenido = $vista_contenido_path;
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }
}
?>