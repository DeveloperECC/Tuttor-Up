<?php
// app/modelos/ModeloDocentes.php
class ModeloDocentes {
    // Datos extraídos de tu DOCENTES/SCRIPT.JS y adaptados
    private $docentes = [
        [
            'id' => 1, 'name' => 'Ana García', 'rating' => 4, 'opinions' => 42,
            'price' => 63785, 'experience' => 12, 'featured' => true, 'subject' => 'Física',
            'img' => 'fisica/Ana García.png', // Ruta relativa a publico/imagenes/
            'description' => 'Ingeniera Industrial, Máster en Docencia Universitaria, con 12 años de experiencia en gestión empresarial, cálculo diferencial, álgebra y más.'
        ],
        [
            'id' => 2, 'name' => 'Luis Fernández', 'rating' => 5, 'opinions' => 128,
            'price' => 98520, 'experience' => 8, 'featured' => true, 'subject' => 'Física',
            'img' => 'fisica/Luis Fernández.png',
            'description' => 'Físico Teórico con 8 años impartiendo clases particulares y universitarias. Apasionado por la divulgación científica.'
        ],
        [
            'id' => 3, 'name' => 'María Rodríguez', 'rating' => 4, 'opinions' => 87,
            'price' => 74210, 'experience' => 20, 'featured' => true, 'subject' => 'Física',
            'img' => 'fisica/María Rodríguez.png',
            'description' => 'Docente de Física con 20 años de trayectoria en bachillerato y preparación para pruebas de acceso a la universidad.'
        ],
        [
            'id' => 4, 'name' => 'Carlos Sánchez', 'rating' => 5, 'opinions' => 56,
            'price' => 50330, 'experience' => 15, 'featured' => true, 'subject' => 'Cálculo',
            'img' => 'fisica/Carlos Sánchez.png', // Asumo que las imágenes están todas en 'fisica' por el ejemplo
            'description' => 'Matemático especializado en Cálculo Diferencial e Integral. 15 años de experiencia ayudando a estudiantes a superar sus desafíos.'
        ],
        [
            'id' => 5, 'name' => 'Laura Gómez', 'rating' => 4, 'opinions' => 32,
            'price' => 89100, 'experience' => 5, 'featured' => true, 'subject' => 'Cálculo',
            'img' => 'fisica/Laura Gómez.png',
            'description' => 'Joven y entusiasta profesora de Cálculo, con métodos innovadores para un aprendizaje efectivo. 5 años de experiencia.'
        ],
        [
            'id' => 6, 'name' => 'Andrés Pérez', 'rating' => 5, 'opinions' => 192,
            'price' => 67840, 'experience' => 11, 'featured' => true, 'subject' => 'Matemáticas',
            'img' => 'fisica/Andrés Pérez.png',
            'description' => 'Experto en Álgebra Lineal y Geometría Analítica. 11 años haciendo que las matemáticas sean comprensibles y amenas.'
        ],
        [
            'id' => 7, 'name' => 'Valentina Martínez', 'rating' => 4, 'opinions' => 74,
            'price' => 55050, 'experience' => 25, 'featured' => true, 'subject' => 'Matemáticas',
            'img' => 'fisica/Valentina Martínez.png',
            'description' => 'Profesora de Matemáticas con una vasta experiencia de 25 años. Paciencia y dedicación garantizadas.'
        ],
        [
            'id' => 8, 'name' => 'Diego Torres', 'rating' => 3, 'opinions' => 154,
            'price' => 99700, 'experience' => 3, 'featured' => false, 'subject' => 'Química',
            'img' => 'fisica/Diego Torres.png',
            'description' => 'Químico Farmacéutico con 3 años de experiencia en tutorías de Química Orgánica e Inorgánica.'
        ],
        [
            'id' => 9, 'name' => 'Sofía López', 'rating' => 3, 'opinions' => 65,
            'price' => 81230, 'experience' => 18, 'featured' => false, 'subject' => 'Química',
            'img' => 'fisica/Sofía López.png',
            'description' => 'Licenciada en Química, 18 años impartiendo clases de todos los niveles. Especialista en preparación para exámenes.'
        ]
    ];

    public function obtenerTodos() {
        // Añadir la ruta completa a la imagen para la vista/JS
        return array_map(function($docente) {
            $docente['full_img_path'] = ASSETS_URL . '/imagenes/' . $docente['img'];
            return $docente;
        }, $this->docentes);
    }

    public function obtenerPorId($id) {
        foreach ($this->docentes as $docente) {
            if ($docente['id'] == $id) {
                $docente['full_img_path'] = ASSETS_URL . '/imagenes/' . $docente['img'];
                return $docente;
            }
        }
        return null;
    }

    // Aquí podrías implementar la lógica de filtrado si la quieres en el backend
    // public function filtrar($filtros) { ... }
}
?>