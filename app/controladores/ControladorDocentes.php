<?php
// app/controladores/ControladorDocentes.php
class ControladorDocentes 
{
    private function cargarLayout($vista_contenido_path, $datos_para_layout) {
        extract($datos_para_layout);
        $vista_contenido = $vista_contenido_path;
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }

    // Función helper para normalizar texto (quitar tildes y a minúsculas) en PHP
    private function normalizarTexto($texto) {
        if (!is_string($texto)) return '';
        $texto = strtolower($texto);
        // Transliterar caracteres acentuados a sus equivalentes sin acento
        if (function_exists('iconv')) { // Verificar si iconv está disponible
            $textoNormalizado = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
            if ($textoNormalizado !== false) { // iconv puede devolver false en error
                 $texto = $textoNormalizado;
            }
        }
        // Como fallback o adicional, eliminar caracteres no ASCII comunes si iconv no hizo todo el trabajo
        // Esta expresión regular es básica, se podría mejorar para más casos.
        $texto = preg_replace('/[^a-z0-9\s-]/', '', $texto);
        return $texto;
    }


    public function index() {
        require_once ROOT_PATH . '/app/modelos/ModeloDocentes.php';
        $modelo = new ModeloDocentes();
        
        $datos_vista['docentes'] = $modelo->obtenerTodos();
        $datos_vista['materia_filtrada'] = null; // Correcto: no hay filtro inicial

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
            $this->index(); 
            return;
        }

        require_once ROOT_PATH . '/app/modelos/ModeloDocentes.php';
        $modeloDocentes = new ModeloDocentes();
        
        // Convertir la URL de la materia (ej. 'calculo-avanzado') a un nombre legible ('Calculo Avanzado')
        // Y luego normalizarlo para la comparación
        $nombreMateriaFiltroOriginal = ucwords(str_replace('-', ' ', $nombreMateriaUrl));
        $nombreMateriaFiltroNormalizado = $this->normalizarTexto($nombreMateriaFiltroOriginal);

        $todosLosDocentes = $modeloDocentes->obtenerTodos();
        $docentesFiltrados = array_filter($todosLosDocentes, function($docente) use ($nombreMateriaFiltroNormalizado) {
            // Normalizar también la materia del docente antes de comparar
            $subjectDocenteNormalizado = isset($docente['subject']) ? $this->normalizarTexto($docente['subject']) : '';
            return $subjectDocenteNormalizado === $nombreMateriaFiltroNormalizado;
        });

        $datos_vista['docentes'] = array_values($docentesFiltrados); 
        // Pasar el nombre original (con mayúsculas y tildes si las tenía) a la vista para mostrarlo
        $datos_vista['materia_filtrada'] = $nombreMateriaFiltroOriginal; 

        $datos_layout['titulo_pagina'] = "Docentes de " . htmlspecialchars($nombreMateriaFiltroOriginal);
        $datos_layout['controlador_actual'] = 'ControladorDocentes';
        $datos_layout['metodo_actual'] = 'filtrarPorMateria/' . $nombreMateriaUrl;
        $datos_layout['css_especifico'] = ['estilos_docentes_especificos.css'];
        $datos_layout['js_especifico'] = ['docentes_interacciones.js'];
        $datos_layout['datos_vista'] = $datos_vista;

        $this->cargarLayout(ROOT_PATH . '/app/vistas/docentes/index.php', $datos_layout);
    }
}
?>