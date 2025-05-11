<?php
// controladores/ControladorMaterias.php

class ControladorMaterias {
    /**
     * Muestra la vista principal con la cuadrícula de materias.
     */
    public function mostrar() {
        // Incluir el modelo de Materias
        require_once ROOT_PATH . '/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        // Obtener todas las materias (aunque la vista index solo muestre algunas prominentemente)
        $materias = $modelo->obtenerTodasLasMaterias();

        // Incluir la vista principal de materias
        // La vista index.php se encarga de presentar la información
        require_once ROOT_PATH . '/vistas/materias/index.php';
    }

    /**
     * Maneja la búsqueda de materias (funcionalidad básica no completamente implementada en vistas).
     */
    public function buscar() {
        require_once ROOT_PATH . '/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        $termino = $_GET['q'] ?? ''; // Obtener el término de búsqueda
        $materias = $modelo->buscarMaterias($termino); // Realizar la búsqueda

        // Aquí podrías cargar una vista específica para resultados de búsqueda
        // Por ahora, simplemente volvemos a cargar la vista index (que no muestra resultados de búsqueda)
        require_once ROOT_PATH . '/vistas/materias/index.php';
    }

    /**
     * Muestra los detalles de una materia específica.
     * Requiere una vista 'detalle.php' para mostrar la información.
     * @param string $materia Nombre de la materia a mostrar.
     */
    private function mostrarMateriaEspecifica($materia) {
        require_once ROOT_PATH . '/modelos/ModeloMaterias.php';
        $modelo = new ModeloMaterias();
        $datosMateria = $modelo->obtenerMateriaPorNombre($materia); // Obtener datos de la materia por nombre

        // Debes crear el archivo vistas/materias/detalle.php para mostrar estos datos.
        // Por ahora, solo imprimimos un mensaje simple.
        if ($datosMateria) {
             // require_once ROOT_PATH . '/vistas/materias/detalle.php'; // Descomentar cuando tengas detalle.php
             echo "<h2>Detalle de: " . htmlspecialchars($datosMateria['nombre']) . "</h2>";
             echo "<p>Código: " . htmlspecialchars($datosMateria['codigo']) . "</p>";
             echo "<p>Color: " . htmlspecialchars($datosMateria['color']) . "</p>";
             // Añadir más detalles según la estructura de detalle.php
        } else {
            echo "Materia no encontrada.";
        }
    }

    // Métodos públicos para materias específicas, llamados por el router (publico/index.php)
    public function calculo() {
        $this->mostrarMateriaEspecifica('Cálculo');
    }

    public function fisica() {
        $this->mostrarMateriaEspecifica('Física');
    }

    public function matematicas() {
        $this->mostrarMateriaEspecifica('Matemáticas');
    }

    public function quimica() {
        $this->mostrarMateriaEspecifica('Química');
    }

    // Añade métodos para otras materias si quieres páginas específicas para ellas
     public function universitario() {
         $this->mostrarMateriaEspecifica('Curso Universitario');
     }

     public function logica() {
         $this->mostrarMateriaEspecifica('Lógica');
     }

     public function fisicaAvanzada() {
         $this->mostrarMateriaEspecifica('Física Avanzada');
     }

     public function calculoAvanzado() {
         $this->mostrarMateriaEspecifica('Cálculo Avanzado');
     }

      public function matematicasIndustriales() {
         $this->mostrarMateriaEspecifica('Matemáticas Industriales');
     }

      public function cienciasAmbientales() {
         $this->mostrarMateriaEspecifica('Ciencias Ambientales');
     }
}