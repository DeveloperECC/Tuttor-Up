<?php
// app/modelos/ModeloDocentes.php

class ModeloDocentes {
    private $conn;
    private $tabla_nombre = "docentes"; // Nombre de tu tabla en la BD

    // Constructor para obtener la conexión a la base de datos
    public function __construct() {
        // Incluir y usar tu clase Database para la conexión
        // Asumimos que Database.php está en el mismo directorio o es accesible
        require_once __DIR__ . '/Database.php'; // Ajusta la ruta si Database.php está en otra parte
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($this->conn === null) {
            // Manejar el error de conexión, podrías lanzar una excepción o loguear.
            // Para simplificar, podemos permitir que continúe y el controlador lo maneje.
            // echo "Error de conexión en ModeloDocentes."; // Para depuración
        }
    }

    // Método para obtener todos los docentes
    public function obtenerTodos() {
        if ($this->conn === null) {
            return []; // Retornar array vacío si no hay conexión
        }

        $query = "SELECT 
                    id_docente, 
                    id_usuario, 
                    nombre_completo, 
                    email_contacto, 
                    materia_principal, 
                    descripcion, 
                    experiencia_anios, 
                    precio_hora, 
                    rating_promedio, 
                    total_opiniones, 
                    foto_url, 
                    es_destacado 
                  FROM " . $this->tabla_nombre . " ORDER BY es_destacado DESC, nombre_completo ASC";

        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            // echo "Error en prepare: " . $this->conn->error; // Para depuración
            return [];
        }

        $stmt->execute();
        $resultado = $stmt->get_result();
        $docentes = [];

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                // Mapear nombres de columnas de la BD a los nombres que espera tu JS/frontend
                // y construir la URL completa de la imagen.
                $docentes[] = $this->mapearDocente($fila);
            }
        }
        $stmt->close();
        return $docentes;
    }

    // Método para obtener un docente por su ID
    public function obtenerPorId($id) {
        if ($this->conn === null || !is_numeric($id)) {
            return null;
        }

        $query = "SELECT 
                    id_docente, 
                    id_usuario, 
                    nombre_completo, 
                    email_contacto, 
                    materia_principal, 
                    descripcion, 
                    experiencia_anios, 
                    precio_hora, 
                    rating_promedio, 
                    total_opiniones, 
                    foto_url, 
                    es_destacado 
                  FROM " . $this->tabla_nombre . " WHERE id_docente = ? LIMIT 1";

        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            // echo "Error en prepare: " . $this->conn->error; // Para depuración
            return null;
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado->num_rows == 1) {
            $fila = $resultado->fetch_assoc();
            $stmt->close();
            return $this->mapearDocente($fila);
        } else {
            $stmt->close();
            return null;
        }
    }

    /**
     * Helper para mapear los datos de la fila de la BD al formato que espera el frontend
     * y construir la URL completa de la imagen.
     */
    private function mapearDocente($fila) {
        // Nombres de clave que tu frontend (docentes_interacciones.js) espera:
        // id, name, rating, opinions, price, experience, featured, subject, img, description, full_img_path
        return [
            'id' => $fila['id_docente'], // 'id' es lo que usa el JS en dataset.teacherId
            'name' => $fila['nombre_completo'],
            'rating' => (float)$fila['rating_promedio'], // Convertir a float
            'opinions' => (int)$fila['total_opiniones'], // Convertir a int
            'price' => (float)$fila['precio_hora'], // Convertir a float
            'experience' => (int)$fila['experiencia_anios'], // Convertir a int
            'featured' => (bool)$fila['es_destacado'], // Convertir a boolean
            'subject' => $fila['materia_principal'],
            'img' => $fila['foto_url'], // Ruta relativa guardada en la BD (ej. 'fisica/Ana Garcia.png')
            'description' => $fila['descripcion'],
            'full_img_path' => (defined('ASSETS_URL') && $fila['foto_url'] ? ASSETS_URL . '/imagenes/' . $fila['foto_url'] : '') // Construir URL completa
        ];
    }

    
}
?>