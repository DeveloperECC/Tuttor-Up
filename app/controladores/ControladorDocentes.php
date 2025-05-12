<?php
// app/controladores/ControladorDocentes.php
class ControladorDocentes 
{
    private function cargarLayout($vista_contenido_path, $datos_para_layout) {
        extract($datos_para_layout);
        $vista_contenido = $vista_contenido_path;
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }

    public function index() {
        require_once ROOT_PATH . '/app/modelos/ModeloDocentes.php';
        $modelo = new ModeloDocentes();
        
        $datos_vista['docentes'] = $modelo->obtenerTodos(); // Para pasar a JS en la vista
        $datos_vista['materia_filtrada'] = null;

        $datos_layout['titulo_pagina'] = "Nuestros Docentes";
        $datos_layout['controlador_actual'] = 'ControladorDocentes';
        $datos_layout['metodo_actual'] = 'index';
        $datos_layout['css_especifico'] = ['estilos_docentes_especificos.css'];
        $datos_layout['js_especifico'] = ['docentes_interacciones.js'];
        $datos_layout['datos_vista'] = $datos_vista;

        $this->cargarLayout(ROOT_PATH . '/app/vistas/docentes/index.php', $datos_layout);
    }

    public function filtrarPorMateria($nombreMateriaUrl = null) {
        if (!$nombreMateriaUrl) {
            // Si no se proporciona materia, redirigir a la vista principal de docentes
            $this->index(); 
            return;
        }

        require_once ROOT_PATH . '/app/modelos/ModeloDocentes.php';
        $modeloDocentes = new ModeloDocentes();
        
        $nombreMateriaFiltro = ucwords(str_replace('-', ' ', $nombreMateriaUrl));

        $todosLosDocentes = $modeloDocentes->obtenerTodos();
        $docentesFiltrados = array_filter($todosLosDocentes, function($docente) use ($nombreMateriaFiltro) {
            return strtolower($docente['subject']) === strtolower($nombreMateriaFiltro);
        });

        // Re-indexar el array para que json_encode no lo convierta en objeto si las claves no son secuenciales
        $datos_vista['docentes'] = array_values($docentesFiltrados); 
        $datos_vista['materia_filtrada'] = $nombreMateriaFiltro;

        $datos_layout['titulo_pagina'] = "Docentes de " . htmlspecialchars($nombreMateriaFiltro);
        $datos_layout['controlador_actual'] = 'ControladorDocentes';
        $datos_layout['metodo_actual'] = 'filtrarPorMateria/' . $nombreMateriaUrl; // Para el menú activo
        $datos_layout['css_especifico'] = ['estilos_docentes_especificos.css'];
        $datos_layout['js_especifico'] = ['docentes_interacciones.js'];
        $datos_layout['datos_vista'] = $datos_vista;

        $this->cargarLayout(ROOT_PATH . '/app/vistas/docentes/index.php', $datos_layout);
    }
}
?>