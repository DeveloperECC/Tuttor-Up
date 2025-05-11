<?php
// app/modelos/ModeloReservas.php
class ModeloReservas {
    // Simulación de reservas
    private $reservas = [
        // ['id' => 1, 'id_docente' => 2, 'id_usuario' => 1, 'fecha' => '2024-08-15', 'hora' => '10AM - 11AM'],
    ];

    public function obtenerPorUsuarioId($usuarioId) {
        // Filtrar $this->reservas por $usuarioId
        return array_filter($this->reservas, function($reserva) use ($usuarioId) {
            return $reserva['id_usuario'] == $usuarioId;
        });
    }

    public function crearReserva($datos) {
        // Lógica para añadir una nueva reserva
        // $nuevaReserva = ['id' => count($this->reservas) + 1] + $datos;
        // $this->reservas[] = $nuevaReserva;
        // return $nuevaReserva['id'];
        return true; // Simulación
    }
}
?>