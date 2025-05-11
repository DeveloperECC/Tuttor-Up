<?php
// modelos/ModeloMaterias.php

class ModeloMaterias {
    // Datos simulados de las materias (luego se reemplazarán con la conexión a BD)
    private $materias = [
        // Materias con colores de la paleta especificada
        ['id' => 1, 'codigo' => 'CAL', 'nombre' => 'Cálculo', 'subtitulo' => 'CULO', 'color' => '#76f0d6', 'icono' => 'fa-square-root-alt'], // Color actualizado
        ['id' => 2, 'codigo' => 'FI', 'nombre' => 'Física', 'subtitulo' => 'SICA', 'color' => '#349b79', 'icono' => 'fa-atom'], // Color actualizado
        ['id' => 3, 'codigo' => 'MATE', 'nombre' => 'Matemáticas', 'subtitulo' => 'MATICAS', 'color' => '#5ce1e6', 'icono' => 'fa-infinity'], // Color actualizado
        ['id' => 4, 'codigo' => 'QUI', 'nombre' => 'Química', 'subtitulo' => 'MICA', 'color' => '#ffffff', 'icono' => 'fa-flask'], // Color actualizado

        // Otras materias con sus colores existentes del modelo original
        ['id' => 5, 'codigo' => 'CU', 'nombre' => 'Curso Universitario', 'subtitulo' => 'UNIVERSITARIO', 'color' => '#9b59b6', 'icono' => 'fa-graduation-cap'], // Color existente
        ['id' => 6, 'codigo' => 'LO', 'nombre' => 'Lógica', 'subtitulo' => 'GICA', 'color' => '#e74c3c', 'icono' => 'fa-brain'], // Color existente
        ['id' => 7, 'codigo' => 'FISI', 'nombre' => 'Física Avanzada', 'subtitulo' => 'AVANZADA', 'color' => '#2e856e', 'icono' => 'fa-atom'], // Color existente
        ['id' => 8, 'codigo' => 'CA', 'nombre' => 'Cálculo Avanzado', 'subtitulo' => 'AVANZADO', 'color' => '#4dc9b7', 'icono' => 'fa-square-root-alt'], // Color existente
        ['id' => 9, 'codigo' => 'MATI', 'nombre' => 'Matemáticas Industriales', 'subtitulo' => 'INDUSTRIALES', 'color' => '#3acad8', 'icono' => 'fa-calculator'], // Color existente
        ['id' => 10, 'codigo' => 'CAS', 'nombre' => 'Ciencias Ambientales', 'subtitulo' => 'AMBIENTALES', 'color' => '#48b8c2', 'icono' => 'fa-leaf'] // Color existente
    ];

    /**
     * Obtiene todas las materias disponibles.
     * @return array Listado completo de materias.
     */
    public function obtenerTodasLasMaterias() {
        return $this->materias;
    }

    /**
     * Busca materias que coincidan con el término de búsqueda.
     * @param string $termino Término de búsqueda.
     * @return array Materias que coinciden con la búsqueda.
     */
    public function buscarMaterias($termino) {
        if (empty($termino)) {
            return $this->materias; // Si el término está vacío, devuelve todas las materias
        }

        return array_filter($this->materias, function($materia) use ($termino) {
            // Filtra las materias si el término se encuentra en el nombre, código o subtítulo (sin distinguir mayúsculas/minúsculas)
            return stripos($materia['nombre'], $termino) !== false ||
                   stripos($materia['codigo'], $termino) !== false ||
                   stripos($materia['subtitulo'], $termino) !== false;
        });
    }

    /**
     * Obtiene una materia específica por su nombre.
     * @param string $nombre Nombre de la materia a buscar.
     * @return array|null Datos de la materia o null si no se encuentra.
     */
    public function obtenerMateriaPorNombre($nombre) {
        foreach ($this->materias as $materia) {
            // Compara el nombre sin distinguir mayúsculas/minúsculas
            if (strtolower($materia['nombre']) === strtolower($nombre)) {
                return $materia;
            }
        }
        return null; // Materia no encontrada
    }

    /**
     * Obtiene una materia específica por su código.
     * @param string $codigo Código de la materia a buscar.
     * @return array|null Datos de la materia o null si no se encuentra.
     */
    public function obtenerMateriaPorCodigo($codigo) {
        foreach ($this->materias as $materia) {
            // Compara el código sin distinguir mayúsculas/minúsculas
            if (strtoupper($materia['codigo']) === strtoupper($codigo)) {
                return $materia;
            }
        }
        return null; // Materia no encontrada
    }

    /**
     * Obtiene materias relacionadas (excluyendo la actual).
     * @param int $idMateriaActual ID de la materia actual a excluir.
     * @param int $limite Cantidad máxima de materias relacionadas a devolver.
     * @return array Listado de materias relacionadas.
     */
    public function obtenerMateriasRelacionadas($idMateriaActual, $limite = 3) {
        // Filtra la materia actual y luego toma las primeras 'limite' materias
        return array_slice(array_filter($this->materias, function($materia) use ($idMateriaActual) {
            return $materia['id'] != $idMateriaActual;
        }), 0, $limite);
    }
}