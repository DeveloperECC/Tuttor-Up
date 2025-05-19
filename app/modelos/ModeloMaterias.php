<?php
// TUTORUP/app/modelos/ModeloMaterias.php

class ModeloMaterias {
    // Datos simulados de las materias
    private $materias = [
        ['id' => 1, 'codigo' => 'CAL', 'nombre' => 'Cálculo', 'subtitulo' => 'CULO', 'color_var' => '--color-calculo', 'icono' => 'fas fa-calculator'],
        ['id' => 2, 'codigo' => 'FI', 'nombre' => 'Física', 'subtitulo' => 'SICA', 'color_var' => '--color-fisica', 'icono' => 'fas fa-atom'],
        ['id' => 3, 'codigo' => 'MATE', 'nombre' => 'Matemáticas', 'subtitulo' => 'MATICAS', 'color_var' => '--color-matematicas', 'icono' => 'fas fa-infinity'],
        ['id' => 4, 'codigo' => 'QUI', 'nombre' => 'Química', 'subtitulo' => 'MICA', 'color_var' => '--color-quimica', 'icono' => 'fas fa-flask'],
        
    ];

    private function normalizarTexto($texto) {
    if (!is_string($texto)) return '';
    $textoNormalizado = strtolower($texto);
    if (function_exists('iconv')) {
        // El //IGNORE es importante para evitar errores con caracteres que no puede transliterar
        $resultadoIconv = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $textoNormalizado);
        if ($resultadoIconv !== false) {
            $textoNormalizado = $resultadoIconv;
        }
    }
    // Eliminar caracteres que no sean letras, números o espacios después de la transliteración
    $textoNormalizado = preg_replace('/[^a-z0-9\s-]/', '', $textoNormalizado);
    // Reemplazar múltiples espacios con uno solo
    $textoNormalizado = preg_replace('/\s+/', ' ', $textoNormalizado);
    return trim($textoNormalizado);
}

    public function obtenerTodasLasMaterias() {
        return $this->materias;
    }

    public function buscarMaterias($termino) {
        if (empty($termino)) return $this->materias;
        return array_filter($this->materias, function($materia) use ($termino) {
            return stripos($materia['nombre'], $termino) !== false ||
                   stripos($materia['codigo'], $termino) !== false ||
                   (isset($materia['subtitulo']) && stripos($materia['subtitulo'], $termino) !== false);
        });
    }

    public function obtenerMateriaPorNombreFlexible($nombreBusqueda) {
    if (empty(trim($nombreBusqueda))) return null;
    $nombreBusquedaNormalizado = $this->normalizarTexto($nombreBusqueda);
    if (empty($nombreBusquedaNormalizado)) return null;

    // Intenta coincidencia exacta primero (normalizada)
    foreach ($this->materias as $materia) {
        if ($this->normalizarTexto($materia['nombre']) === $nombreBusquedaNormalizado) {
            return $materia;
        }
    }
    // Si no hay coincidencia exacta, intenta coincidencia parcial (stripos)
    // que el nombre de la materia CONTENGA el término buscado.
    foreach ($this->materias as $materia) {
        if (stripos($this->normalizarTexto($materia['nombre']), $nombreBusquedaNormalizado) !== false) {
            return $materia; // Devuelve la primera coincidencia parcial
        }
    }
    return null;
}
}
?>