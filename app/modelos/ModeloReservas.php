<?php
// app/modelos/ModeloReservas.php

class ModeloReservas {
    private $conn;
    private $tabla_nombre = "agendamientos";

    public function __construct() {
        require_once __DIR__ . '/Database.php';
        $database = new Database();
        $this->conn = $database->getConnection();

        if ($this->conn === null) {
            // Manejo de error de conexión
            // Podrías lanzar una excepción aquí o loguear y permitir que los métodos individuales fallen.
            error_log("ModeloReservas: No se pudo establecer la conexión a la base de datos en el constructor.");
        }
    }

    public function obtenerAgendamientosPorUsuario($idEstudiante) {
        if ($this->conn === null || !is_numeric($idEstudiante)) {
            return [];
        }

        $query = "SELECT 
                    a.id_agendamiento, 
                    a.id_docente, 
                    d.nombre_completo as nombre_docente, 
                    a.fecha_tutoria, 
                    a.bloque_horario, 
                    a.materia,
                    a.estado,
                    a.fecha_creacion
                  FROM " . $this->tabla_nombre . " a
                  JOIN docentes d ON a.id_docente = d.id_docente
                  WHERE a.id_estudiante = ?
                  ORDER BY a.fecha_tutoria DESC, a.bloque_horario ASC";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            error_log("Error en prepare obtenerAgendamientosPorUsuario: " . $this->conn->error);
            return [];
        }
        
        $stmt->bind_param("i", $idEstudiante);
        if (!$stmt->execute()) {
            error_log("Error al ejecutar obtenerAgendamientosPorUsuario: " . $stmt->error);
            $stmt->close();
            return [];
        }
        $resultado = $stmt->get_result();
        $agendamientos = [];

        if ($resultado === false) {
             error_log("Error al obtener resultado en obtenerAgendamientosPorUsuario: " . $stmt->error);
             $stmt->close();
             return [];
        }

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $agendamientos[] = $fila;
            }
        }
        $stmt->close();
        return $agendamientos;
    }

    public function verificarDisponibilidad($idDocente, $fechaTutoria, $bloqueHorario) {
        if ($this->conn === null) return true; // Asumir no disponible si no hay conexión

        $query = "SELECT id_agendamiento FROM " . $this->tabla_nombre . " 
                  WHERE id_docente = ? AND fecha_tutoria = ? AND bloque_horario = ? 
                  AND estado IN ('pendiente', 'confirmada')"; 
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) { 
            error_log("Error en prepare verificarDisponibilidad: " . $this->conn->error);
            return true; // Asumir no disponible en error
        }

        $stmt->bind_param("iss", $idDocente, $fechaTutoria, $bloqueHorario);
        if (!$stmt->execute()) {
            error_log("Error al ejecutar verificarDisponibilidad: " . $stmt->error);
            $stmt->close();
            return true; // Asumir no disponible en error
        }
        $stmt->store_result();
        $num_filas = $stmt->num_rows;
        $stmt->close();

        return $num_filas > 0; 
    }

    public function crearReserva($datosReserva) {
        if ($this->conn === null) {
            return ['exito' => false, 'mensaje' => 'Error de conexión a la base de datos.', 'id_agendamiento' => null];
        }

        if (empty($datosReserva['id_estudiante']) || empty($datosReserva['id_docente']) || 
            empty($datosReserva['fecha_tutoria']) || empty($datosReserva['bloque_horario'])) {
            return ['exito' => false, 'mensaje' => 'Faltan datos esenciales para la reserva.', 'id_agendamiento' => null];
        }

        if ($this->verificarDisponibilidad(
            $datosReserva['id_docente'],
            $datosReserva['fecha_tutoria'],
            $datosReserva['bloque_horario']
        )) {
            return ['exito' => false, 'mensaje' => 'Lo sentimos, este horario ya no está disponible o acaba de ser reservado.', 'id_agendamiento' => null];
        }

        $query = "INSERT INTO " . $this->tabla_nombre . " 
                  (id_estudiante, id_docente, fecha_tutoria, bloque_horario, materia, notas_estudiante, estado) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            error_log("Error al preparar la reserva INSERT: " . $this->conn->error);
            return ['exito' => false, 'mensaje' => 'Error al preparar la reserva.', 'id_agendamiento' => null];
        }

        $materia = $datosReserva['materia'] ?? null; 
        $notas = $datosReserva['notas_estudiante'] ?? null; 
        $estadoPorDefecto = 'confirmada'; 

        $stmt->bind_param("iisssss", 
            $datosReserva['id_estudiante'], 
            $datosReserva['id_docente'], 
            $datosReserva['fecha_tutoria'], 
            $datosReserva['bloque_horario'],
            $materia,
            $notas,
            $estadoPorDefecto
        );

        if ($stmt->execute()) {
            $nuevoId = $stmt->insert_id;
            $stmt->close();
            return ['exito' => true, 'mensaje' => '¡Reserva creada con éxito!', 'id_agendamiento' => $nuevoId];
        } else {
            if ($this->conn->errno == 1062) { 
                $stmt->close();
                return ['exito' => false, 'mensaje' => 'Este horario acaba de ser reservado por otra persona. Por favor, intenta con otro horario.', 'id_agendamiento' => null];
            }
            $error = $stmt->error;
            error_log("Error al guardar la reserva: " . $error . " (Errno: " . $this->conn->errno . ")");
            $stmt->close();
            return ['exito' => false, 'mensaje' => 'Error al guardar la reserva en la base de datos. Inténtalo de nuevo.', 'id_agendamiento' => null];
        }
    }

    public function obtenerHorariosOcupados($idDocente, $fechaTutoria) {
        if ($this->conn === null) return [];

        $query = "SELECT bloque_horario FROM " . $this->tabla_nombre . " 
                  WHERE id_docente = ? AND fecha_tutoria = ? AND estado IN ('pendiente', 'confirmada')";
        
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) { 
            error_log("Error en prepare obtenerHorariosOcupados: " . $this->conn->error);
            return []; 
        }

        $stmt->bind_param("is", $idDocente, $fechaTutoria);
        if (!$stmt->execute()) {
            error_log("Error al ejecutar obtenerHorariosOcupados: " . $stmt->error);
            $stmt->close();
            return [];
        }
        $resultado = $stmt->get_result();
        $horariosOcupados = [];

        if ($resultado === false) {
             error_log("Error al obtener resultado en obtenerHorariosOcupados: " . $stmt->error);
             $stmt->close();
             return [];
        }

        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $horariosOcupados[] = $fila['bloque_horario'];
            }
        }
        $stmt->close();
        return $horariosOcupados;
    }

    /**
     * Cambia el estado de una reserva a 'cancelada'.
     * Verifica que la reserva pertenezca al estudiante que la solicita.
     * @param int $idAgendamiento
     * @param int $idEstudiante
     * @return array ['exito' => bool, 'mensaje' => string, 'codigo_error' => int (opcional)]
     */
    public function cancelarReserva($idAgendamiento, $idEstudiante) {
        if ($this->conn === null) {
            return ['exito' => false, 'mensaje' => 'Error de conexión a la base de datos.', 'codigo_error' => 503]; // Service Unavailable
        }

        // Iniciar transacción para asegurar atomicidad entre la verificación y la actualización
        $this->conn->begin_transaction();

        try {
            // Primero, verificar si la reserva existe y pertenece al usuario y está en un estado cancelable
            $query_check = "SELECT estado FROM " . $this->tabla_nombre . " 
                            WHERE id_agendamiento = ? AND id_estudiante = ? FOR UPDATE"; // FOR UPDATE para bloquear la fila
            
            $stmt_check = $this->conn->prepare($query_check);
            if ($stmt_check === false) {
                error_log("Error al preparar la verificación de reserva para cancelar: " . $this->conn->error);
                $this->conn->rollback();
                return ['exito' => false, 'mensaje' => 'Error al verificar la reserva.'];
            }
            $stmt_check->bind_param("ii", $idAgendamiento, $idEstudiante);
            
            if (!$stmt_check->execute()) {
                error_log("Error al ejecutar la verificación de reserva para cancelar: " . $stmt_check->error);
                $stmt_check->close();
                $this->conn->rollback();
                return ['exito' => false, 'mensaje' => 'Error al ejecutar la verificación de la reserva.'];
            }
            $result_check = $stmt_check->get_result();

            if ($result_check === false) {
                error_log("Error al obtener resultado en verificación de reserva para cancelar: " . $stmt_check->error);
                $stmt_check->close();
                $this->conn->rollback();
                return ['exito' => false, 'mensaje' => 'Error al obtener datos de la reserva.'];
            }

            if ($result_check->num_rows == 0) {
                $stmt_check->close();
                $this->conn->rollback();
                return ['exito' => false, 'mensaje' => 'Reserva no encontrada o no tienes permiso para cancelarla.', 'codigo_error' => 404]; // Not Found
            }

            $reserva_actual = $result_check->fetch_assoc();
            $stmt_check->close();

            // Verificar si la reserva ya está cancelada o completada
            if (!in_array($reserva_actual['estado'], ['pendiente', 'confirmada'])) {
                $this->conn->rollback();
                return ['exito' => false, 'mensaje' => 'Esta reserva ya no puede ser cancelada (estado actual: ' . htmlspecialchars($reserva_actual['estado']) . ').', 'codigo_error' => 409]; // Conflict
            }

            // Si todo está bien, proceder a cancelar
            $query_cancel = "UPDATE " . $this->tabla_nombre . " SET estado = 'cancelada' 
                             WHERE id_agendamiento = ? AND id_estudiante = ? 
                             AND estado IN ('pendiente', 'confirmada')"; // Condición redundante pero segura
            
            $stmt_cancel = $this->conn->prepare($query_cancel);
            if ($stmt_cancel === false) {
                error_log("Error al preparar la cancelación de reserva: " . $this->conn->error);
                $this->conn->rollback();
                return ['exito' => false, 'mensaje' => 'Error al preparar la cancelación.'];
            }

            $stmt_cancel->bind_param("ii", $idAgendamiento, $idEstudiante);

            if (!$stmt_cancel->execute()) {
                $error = $stmt_cancel->error;
                $stmt_cancel->close();
                $this->conn->rollback();
                error_log("Error al ejecutar la cancelación de reserva: " . $error);
                return ['exito' => false, 'mensaje' => 'Error al cancelar la reserva en la base de datos.'];
            }

            if ($stmt_cancel->affected_rows > 0) {
                $stmt_cancel->close();
                $this->conn->commit(); // Confirmar la transacción
                return ['exito' => true, 'mensaje' => '¡Reserva cancelada con éxito!'];
            } else {
                // Esto podría pasar si la reserva fue modificada por otro proceso justo entre el check y el update,
                // o si el estado ya no era 'pendiente' o 'confirmada' (aunque el check anterior debería haberlo capturado).
                $stmt_cancel->close();
                $this->conn->rollback();
                return ['exito' => false, 'mensaje' => 'No se pudo cancelar la reserva. Es posible que su estado haya cambiado recientemente.', 'codigo_error' => 409]; // Conflict
            }

        } catch (Exception $e) {
            // Capturar cualquier otra excepción y hacer rollback
            error_log("Excepción en cancelarReserva: " . $e->getMessage());
            $this->conn->rollback();
            return ['exito' => false, 'mensaje' => 'Ocurrió un error inesperado durante la cancelación.'];
        }
    }
}
?>