<?php
// app/controladores/ControladorMaterias.php

class ControladorMaterias {

    private function cargarLayout($vista_contenido_path, $datos_para_layout) {
        // Extraer datos para que estén disponibles en el layout y la vista
        extract($datos_para_layout);
        $vista_contenido = $vista_contenido_path; // Para el layout_principal.php
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }

    /**
     * Muestra la vista principal con la cuadrícula de materias.
     */
    public function mostrar() {
        require_once ROOT_PATH . '/app/modelos/ModeloMaterias.php';
        $modeloMateria = new ModeloMaterias();
        $materias_raw = $modelo->obtenerTodasLasMaterias();

        $materias_data_vista = [];
        foreach ($materias_raw as $m) {
            $nombre_url = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $m['nombre'])));
            $materias_data_vista[] = [
                'id' => $m['id'],
                'codigo_html' => $this->generarCodigoHtmlMateria($m['nombre']),
                'nombre' => $m['nombre'],
                'descripcion' => 'Descubre ' . $m['nombre'] . ', explora sus conceptos y aplicaciones.',
                'clase_css' => strtolower(str_replace(' ', '', preg_replace("/[^A-Za-z0-9 ]/", '', $m['nombre']))),
                'icono_fa' => $m['icono'],
                'accion_url' => '/materias/' . $nombre_url
            ];
        }
        
        $datos_layout['titulo_pagina'] = "Nuestras Materias";
        $datos_layout['controlador_actual'] = 'ControladorMaterias';
        $datos_layout['metodo_actual'] = 'mostrar';
        $datos_layout['datos_vista'] = ['materias_data' => $materias_data_vista]; // Datos específicos para la vista

        $this->cargarLayout(ROOT_PATH . '/app/vistas/materias/index.php', $datos_layout);
    }

    private function generarCodigoHtmlMateria($nombre) {
        // Lógica para generar el HTML del código como CAL<br>CU<br>LO
        $partes = explode(" ", $nombre);
        $html = "";
        if (count($partes) > 1) { // Ej: Cálculo Avanzado
            $html = strtoupper(substr($partes[0],0,3)) . "<br>" . strtoupper(substr($partes[1],0,3));
        } else { // Ej: Cálculo
            $palabra = strtoupper($nombre);
            $len = strlen($palabra);
            if ($len <= 3) $html = $palabra;
            elseif ($len <= 5) $html = substr($palabra,0,3) . "<br>" . substr($palabra,3);
            else $html = substr($palabra,0,3) . "<br>" . substr($palabra,3,2) . "<br>" . substr($palabra,5,2);
        }
        return trim($html, "<br>");
    }


    public function buscar() {
        require_once ROOT_PATH . '/app/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        $termino = $_GET['q'] ?? '';
        $materias_raw = $modelo->buscarMaterias($termino);

        $materias_data_vista = [];
         foreach ($materias_raw as $m) {
            $nombre_url = strtolower(str_replace(' ', '-', preg_replace("/[^A-Za-z0-9 ]/", '', $m['nombre'])));
            $materias_data_vista[] = [
                'id' => $m['id'],
                'codigo_html' => $this->generarCodigoHtmlMateria($m['nombre']),
                'nombre' => $m['nombre'],
                'descripcion' => 'Descubre ' . $m['nombre'] . ', explora sus conceptos y aplicaciones.',
                'clase_css' => strtolower(str_replace(' ', '', preg_replace("/[^A-Za-z0-9 ]/", '', $m['nombre']))),
                'icono_fa' => $m['icono'],
                'accion_url' => '/materias/' . $nombre_url
            ];
        }

        $datos_layout['titulo_pagina'] = "Resultados para: " . htmlspecialchars($termino);
        $datos_layout['controlador_actual'] = 'ControladorMaterias';
        $datos_layout['metodo_actual'] = 'buscar';
        $datos_layout['datos_vista'] = [
            'materias_data' => $materias_data_vista,
            'termino_busqueda' => $termino
        ];

        $this->cargarLayout(ROOT_PATH . '/app/vistas/materias/index.php', $datos_layout);
    }

    private function mostrarMateriaEspecificaPorUrl($nombreMateriaUrl) {
        require_once ROOT_PATH . '/app/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        $nombreMateriaOriginal = ucwords(str_replace('-', ' ', $nombreMateriaUrl)); // Convertir 'calculo-avanzado' a 'Calculo Avanzado'
        
        $datosMateria = $modelo->obtenerMateriaPorNombre($nombreMateriaOriginal);

        $datos_layout['controlador_actual'] = 'ControladorMaterias';
        $datos_layout['metodo_actual'] = $nombreMateriaUrl; // Para el menú activo del submenu
        $datos_layout['datos_vista']['materia'] = $datosMateria;

        if ($datosMateria) {
            $datos_layout['titulo_pagina'] = "Detalle: " . htmlspecialchars($datosMateria['nombre']);
        } else {
            $datos_layout['titulo_pagina'] = "Materia no encontrada";
        }
        $this->cargarLayout(ROOT_PATH . '/app/vistas/materias/detalle.php', $datos_layout);
    }

    // Métodos públicos para materias específicas, llamados por el router
    // El router pasará el nombre de la materia como argumento desde la URL
    // ej. /materias/calculo -> se llama $controlador->calculo()
    public function calculo() { $this->mostrarMateriaEspecificaPorUrl('calculo'); }
    public function fisica() { $this->mostrarMateriaEspecificaPorUrl('fisica'); }
    public function matematicas() { $this->mostrarMateriaEspecificaPorUrl('matematicas'); }
    public function quimica() { $this->mostrarMateriaEspecificaPorUrl('quimica'); }
    public function logica() { $this->mostrarMateriaEspecificaPorUrl('logica'); }
    public function fisicaavanzada() { $this->mostrarMateriaEspecificaPorUrl('fisica-avanzada'); } // URL amigable
    public function calculoavanzado() { $this->mostrarMateriaEspecificaPorUrl('calculo-avanzado'); }
    public function matematicasindustriales() { $this->mostrarMateriaEspecificaPorUrl('matematicas-industriales'); }
    public function cienciasambientales() { $this->mostrarMateriaEspecificaPorUrl('ciencias-ambientales'); }
}
?>