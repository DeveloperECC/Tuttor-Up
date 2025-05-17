<?php
// app/controladores/ControladorMaterias.php

class ControladorMaterias {

    private function cargarLayout($vista_contenido_path, $datos_para_layout) {
        // Extraer datos para que estén disponibles en el layout y la vista
        extract($datos_para_layout);
        $vista_contenido = $vista_contenido_path; // Para el layout_principal.php
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }

    private function generarCodigoHtmlMateria($nombre) {
        // Lógica para generar el HTML del código como CAL<br>CU<br>LO
        $partes = explode(" ", $nombre);
        $html = "";
        if (count($partes) > 1) { // Ej: Cálculo Avanzado
            $p1 = strtoupper(substr($partes[0],0,3));
            $p2 = isset($partes[1]) ? strtoupper(substr($partes[1],0,2)) : '';
            $p3 = isset($partes[1]) && strlen($partes[1]) > 2 ? strtoupper(substr($partes[1],2,2)) : '';
            $html = $p1 . ($p2 ? "<br>" . $p2 : '') . ($p3 ? "<br>" . $p3 : '');
        } else { // Ej: Cálculo
            $palabra = strtoupper($nombre);
            $len = strlen($palabra);
            if ($len <= 3) $html = $palabra;
            elseif ($len <= 5) $html = substr($palabra,0,3) . "<br>" . substr($palabra,3);
            else $html = substr($palabra,0,4) . "<br>" . substr($palabra,4,8);
        }
        return trim(trim($html), "<br>");
    }

    /**
     * Muestra la vista principal con la cuadrícula de materias.
     */
    public function index() {
        require_once ROOT_PATH . '/app/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        
        // Nombres de las 4 materias principales en el orden visual deseado para el mockup
        $materiasPrincipalesNombres = ['Cálculo', 'Física', 'Matemáticas', 'Química'];
        $materias_raw = $modelo->obtenerTodasLasMaterias();
         
        $materias_data_vista = [];

        // Descripciones específicas como en el mockup para las materias principales
        $descripciones_mockup = [
            'Cálculo' => 'El fascinante mundo del cambio y sus aplicaciones fundamentales.',
            'Física' => 'Entiende las leyes que rigen el universo y sus fenómenos.',
            'Matemáticas' => 'La base abstracta de toda ciencia y tecnología moderna.',
            'Química' => 'La ciencia de la materia, sus propiedades y transformaciones.'
        ];

        foreach ($materias_raw as $m) {
            if (in_array($m['nombre'], $materiasPrincipalesNombres)) {
                
                $nombre_limpio_url = strtolower($m['nombre']);
                $nombre_limpio_url = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre_limpio_url);
                $nombre_limpio_url = preg_replace('/[^a-z0-9\s-]/', '', $nombre_limpio_url);
                $nombre_limpio_url = preg_replace('/\s+/', '-', $nombre_limpio_url);
                $nombre_limpio_url = trim($nombre_limpio_url, '-');

                $clase_css_tarjeta = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $m['nombre']));
                $clase_css_tarjeta = preg_replace('/[^a-z0-9]/', '', $clase_css_tarjeta);

                $materias_data_vista[] = [
                    'id' => $m['id'],
                    'codigo_html' => $this->generarCodigoHtmlMateria($m['nombre']),
                    'nombre' => $m['nombre'],
                    'descripcion' => $descripciones_mockup[$m['nombre']] ?? ('Encuentra tutores expertos en ' . $m['nombre'] . '.'),
                    'clase_css' => $clase_css_tarjeta,
                    'icono_fa' => $m['icono'] ?? 'fas fa-book-open',
                    'accion_url' => '/docentes/filtrarPorMateria/' . $nombre_limpio_url
                ];
            }
        }

        usort($materias_data_vista, function($a, $b) use ($materiasPrincipalesNombres) {
            return array_search($a['nombre'], $materiasPrincipalesNombres) - array_search($b['nombre'], $materiasPrincipalesNombres);
        });
        
        $datos_layout['titulo_pagina'] = "Nuestras Materias";
        $datos_layout['controlador_actual'] = 'ControladorMaterias';
        $datos_layout['metodo_actual'] = 'index';
        $datos_layout['datos_vista'] = [
            'materias_data' => $materias_data_vista,
            'termino_busqueda' => null // <--- AJUSTE AQUÍ: Añadido para evitar el warning en la vista
        ];

        $this->cargarLayout(ROOT_PATH . '/app/vistas/materias/index.php', $datos_layout);
    }

    public function buscar() {
        require_once ROOT_PATH . '/app/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        $termino = $_GET['q'] ?? '';
        $materias_raw = $modelo->buscarMaterias($termino);

        $materias_data_vista = [];
         foreach ($materias_raw as $m) {
            $nombre_limpio_url = strtolower($m['nombre']);
            $nombre_limpio_url = iconv('UTF-8', 'ASCII//TRANSLIT', $nombre_limpio_url);
            $nombre_limpio_url = preg_replace('/[^a-z0-9\s-]/', '', $nombre_limpio_url);
            $nombre_limpio_url = preg_replace('/\s+/', '-', $nombre_limpio_url);
            $nombre_limpio_url = trim($nombre_limpio_url, '-');

            $clase_css_tarjeta = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $m['nombre']));
            $clase_css_tarjeta = preg_replace('/[^a-z0-9]/', '', $clase_css_tarjeta);

            $materias_data_vista[] = [
                'id' => $m['id'],
                'codigo_html' => $this->generarCodigoHtmlMateria($m['nombre']),
                'nombre' => $m['nombre'],
                'descripcion' => 'Información sobre ' . $m['nombre'] . '.', 
                'clase_css' => $clase_css_tarjeta,
                'icono_fa' => $m['icono'] ?? 'fas fa-search',
                'accion_url' => '/docentes/filtrarPorMateria/' . $nombre_limpio_url 
            ];
        }

        $datos_layout['titulo_pagina'] = "Resultados para: " . htmlspecialchars($termino);
        $datos_layout['controlador_actual'] = 'ControladorMaterias';
        $datos_layout['metodo_actual'] = 'buscar';
        $datos_layout['datos_vista'] = [
            'materias_data' => $materias_data_vista,
            'termino_busqueda' => $termino // Aquí sí se define con el valor de la búsqueda
        ];

        $this->cargarLayout(ROOT_PATH . '/app/vistas/materias/index.php', $datos_layout);
    }

    private function mostrarMateriaEspecificaPorUrl($nombreMateriaUrl) {
        require_once ROOT_PATH . '/app/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        $nombreMateriaOriginal = ucwords(str_replace('-', ' ', $nombreMateriaUrl));
        
        $datosMateria = $modelo->obtenerMateriaPorNombre($nombreMateriaOriginal);

        $datos_layout['controlador_actual'] = 'ControladorMaterias';
        $datos_layout['metodo_actual'] = $nombreMateriaUrl;
        $datos_layout['datos_vista']['materia'] = $datosMateria;
        // Para la vista de detalle, no necesitamos 'termino_busqueda' explícitamente
        // a menos que el layout_principal lo requiera siempre. Si es así, añádelo como null.
        // $datos_layout['datos_vista']['termino_busqueda'] = null; 


        if ($datosMateria) {
            $datos_layout['titulo_pagina'] = "Detalle: " . htmlspecialchars($datosMateria['nombre']);
        } else {
            $datos_layout['titulo_pagina'] = "Materia no encontrada";
        }
        $this->cargarLayout(ROOT_PATH . '/app/vistas/materias/detalle.php', $datos_layout);
    }

    // --- Métodos para rutas de materias específicas (ej. /materias/calculo) ---
    public function calculo() { $this->mostrarMateriaEspecificaPorUrl('calculo'); }
    public function fisica() { $this->mostrarMateriaEspecificaPorUrl('fisica'); }
    public function matematicas() { $this->mostrarMateriaEspecificaPorUrl('matematicas'); }
    public function quimica() { $this->mostrarMateriaEspecificaPorUrl('quimica'); }
    
}
?>