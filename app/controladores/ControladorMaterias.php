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
        
        $materiasPrincipalesNombres = ['Cálculo', 'Física', 'Matemáticas', 'Química'];
        $materias_raw = $modelo->obtenerTodasLasMaterias();
         
        $materias_data_vista = [];

        $descripciones_mockup = [
            'Cálculo' => 'El fascinante mundo del cambio y sus aplicaciones fundamentales.',
            'Física' => 'Entiende las leyes que rigen el universo y sus fenómenos.',
            'Matemáticas' => 'La base abstracta de toda ciencia y tecnología moderna.',
            'Química' => 'La ciencia de la materia, sus propiedades y transformaciones.'
        ];

        foreach ($materias_raw as $m) {
            if (in_array($m['nombre'], $materiasPrincipalesNombres)) {
                
                $nombre_limpio_url = strtolower($m['nombre']);
                // Normalización para URL más robusta
                if (function_exists('iconv')) {
                    $nombre_temp = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombre_limpio_url);
                    if ($nombre_temp !== false) $nombre_limpio_url = $nombre_temp;
                }
                $nombre_limpio_url = preg_replace('/[^a-z0-9\s-]/', '', $nombre_limpio_url);
                $nombre_limpio_url = preg_replace('/\s+/', '-', $nombre_limpio_url);
                $nombre_limpio_url = trim($nombre_limpio_url, '-');

                $clase_css_tarjeta = ''; // Regenerar si es necesario
                if (function_exists('iconv')) {
                    $clase_css_tarjeta = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $m['nombre']));
                } else {
                    $clase_css_tarjeta = strtolower($m['nombre']);
                }
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
            'termino_busqueda' => null
        ];
        // MODIFICACIÓN: Mostrar buscador del header en la página principal de materias también
        $datos_layout['mostrar_buscador_header'] = true; 
        $this->cargarLayout(ROOT_PATH . '/app/vistas/materias/index.php', $datos_layout);
    }

    /**
     * Método invocado por la búsqueda del header.
     * Intenta encontrar una materia por el término de búsqueda.
     * Si la encuentra, redirige a la vista de docentes filtrados por esa materia.
     * Si no, muestra una lista de materias que coincidan parcialmente con el término.
     */
    public function buscar() {
        $termino = trim($_GET['q'] ?? ''); // Obtener y limpiar término de búsqueda

        if (empty($termino)) {
            // Si no hay término de búsqueda, redirigir a la página principal de materias
            header('Location: ' . BASE_URL . '/materias');
            exit();
        }

        require_once ROOT_PATH . '/app/modelos/ModeloMaterias.php';
        $modeloMaterias = new ModeloMaterias();
        
        // Usar el método obtenerMateriaPorNombreFlexible que busca coincidencias exactas y luego parciales
        $materiaEncontrada = $modeloMaterias->obtenerMateriaPorNombreFlexible($termino);

        if ($materiaEncontrada) {
            // Si se encuentra una materia (exacta o la primera parcial), construir la URL para filtrar docentes
            $nombreMateriaUrl = strtolower($materiaEncontrada['nombre']);
            // Normalizar para URL (quitar acentos, reemplazar espacios con guiones, etc.)
            if (function_exists('iconv')) {
                $nombre_temp = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombreMateriaUrl);
                if ($nombre_temp !== false) $nombreMateriaUrl = $nombre_temp;
            }
            $nombreMateriaUrl = preg_replace('/[^a-z0-9\s-]/', '', $nombreMateriaUrl); // Permitir guiones
            $nombreMateriaUrl = preg_replace('/\s+/', '-', $nombreMateriaUrl);       // Espacios a guiones
            $nombreMateriaUrl = trim($nombreMateriaUrl, '-');                       // Quitar guiones al inicio/final

            // Redirigir a la vista de docentes filtrados por esta materia
            header('Location: ' . BASE_URL . '/docentes/filtrarPorMateria/' . $nombreMateriaUrl);
            exit();
        } else {
            // Si no se encuentra una materia específica por nombre (ni exacta ni parcial directa),
            // entonces buscamos todas las materias que podrían coincidir parcialmente para listarlas.
            // Esto mantiene la funcionalidad original de la vista de búsqueda de materias si no hay un match directo.
            $materiasCoincidentes = $modeloMaterias->buscarMaterias($termino); // Este método ya es flexible
            $materias_data_vista = [];

            foreach ($materiasCoincidentes as $m) {
                $nombre_limpio_url_materia = strtolower($m['nombre']);
                if (function_exists('iconv')) {
                    $nombre_temp_materia = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombre_limpio_url_materia);
                    if ($nombre_temp_materia !== false) $nombre_limpio_url_materia = $nombre_temp_materia;
                }
                $nombre_limpio_url_materia = preg_replace('/[^a-z0-9\s-]/', '', $nombre_limpio_url_materia);
                $nombre_limpio_url_materia = preg_replace('/\s+/', '-', $nombre_limpio_url_materia);
                $nombre_limpio_url_materia = trim($nombre_limpio_url_materia, '-');

                $clase_css_tarjeta = '';
                if (function_exists('iconv')) {
                    $clase_css_tarjeta = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $m['nombre']));
                } else {
                    $clase_css_tarjeta = strtolower($m['nombre']);
                }
                $clase_css_tarjeta = preg_replace('/[^a-z0-9]/', '', $clase_css_tarjeta);

                $materias_data_vista[] = [
                    'id' => $m['id'],
                    'codigo_html' => $this->generarCodigoHtmlMateria($m['nombre']),
                    'nombre' => $m['nombre'],
                    'descripcion' => 'Información sobre ' . htmlspecialchars($m['nombre']) . '.', 
                    'clase_css' => $clase_css_tarjeta,
                    'icono_fa' => $m['icono'] ?? 'fas fa-search',
                    'accion_url' => '/docentes/filtrarPorMateria/' . $nombre_limpio_url_materia 
                ];
            }

            // Cargar la vista de materias (que mostrará las coincidencias o un mensaje de "no encontrado")
            $datos_layout['titulo_pagina'] = "Resultados de Búsqueda para: \"" . htmlspecialchars($termino) . "\"";
            $datos_layout['controlador_actual'] = 'ControladorMaterias';
            $datos_layout['metodo_actual'] = 'buscar'; 
            $datos_layout['datos_vista'] = [
                'materias_data' => $materias_data_vista, 
                'termino_busqueda' => $termino
            ];
            // MODIFICACIÓN: Mostrar buscador del header en la página de resultados de búsqueda también
            $datos_layout['mostrar_buscador_header'] = true; 
            $this->cargarLayout(ROOT_PATH . '/app/vistas/materias/index.php', $datos_layout);
        }
    }

    private function mostrarMateriaEspecificaPorUrl($nombreMateriaUrl) {
        require_once ROOT_PATH . '/app/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        $nombreMateriaOriginal = ucwords(str_replace('-', ' ', $nombreMateriaUrl));
        
        // Usar el método que ya normaliza internamente para obtener el detalle
        $datosMateria = $modelo->obtenerMateriaPorNombre($nombreMateriaOriginal); 

        $datos_layout['controlador_actual'] = 'ControladorMaterias';
        $datos_layout['metodo_actual'] = $nombreMateriaUrl; // Para marcar activo el menú si es necesario
        $datos_layout['datos_vista']['materia'] = $datosMateria;
        // MODIFICACIÓN: Mostrar buscador del header en la página de detalle de materia
        $datos_layout['mostrar_buscador_header'] = true; 

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