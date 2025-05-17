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