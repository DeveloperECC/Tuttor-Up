<?php
// TUTORUP/app/modelos/ModeloMaterias.php

class ModeloMaterias {
    // Datos simulados de las materias
    private $materias = [
        ['id' => 1, 'codigo' => 'CAL', 'nombre' => 'Cálculo', 'subtitulo' => 'CULO', 'color_var' => '--color-calculo', 'icono' => 'fas fa-calculator'],
        ['id' => 2, 'codigo' => 'FI', 'nombre' => 'Física', 'subtitulo' => 'SICA', 'color_var' => '--color-fisica', 'icono' => 'fas fa-atom'],
        ['id' => 3, 'codigo' => 'MATE', 'nombre' => 'Matemáticas', 'subtitulo' => 'MATICAS', 'color_var' => '--color-matematicas', 'icono' => 'fas fa-infinity'],
        ['id' => 4, 'codigo' => 'QUI', 'nombre' => 'Química', 'subtitulo' => 'MICA', 'color_var' => '--color-quimica', 'icono' => 'fas fa-flask'],
        ['id' => 6, 'codigo' => 'LO', 'nombre' => 'Lógica', 'subtitulo' => 'GICA', 'color' => '#e74c3c', 'icono' => 'fas fa-brain'], // color_var faltante, usar color como fallback
        ['id' => 7, 'codigo' => 'FISI', 'nombre' => 'Física Avanzada', 'subtitulo' => 'AVANZADA', 'color' => '#2e856e', 'icono' => 'fas fa-atom'],
        ['id' => 8, 'codigo' => 'CA', 'nombre' => 'Cálculo Avanzado', 'subtitulo' => 'AVANZADO', 'color' => '#4dc9b7', 'icono' => 'fas fa-square-root-alt'],
        ['id' => 9, 'codigo' => 'MATI', 'nombre' => 'Matemáticas Industriales', 'subtitulo' => 'INDUSTRIALES', 'color' => '#3acad8', 'icono' => 'fas fa-calculator'],
        ['id' => 10, 'codigo' => 'CAS', 'nombre' => 'Ciencias Ambientales', 'subtitulo' => 'AMBIENTALES', 'color' => '#48b8c2', 'icono' => 'fas fa-leaf']
    ];

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

    public function obtenerMateriaPorNombre($nombre) {
        foreach ($this->materias as $materia) {
            if (strtolower($materia['nombre']) === strtolower($nombre)) {
                return $materia;
            }
        }
        return null;
    }
}
?>