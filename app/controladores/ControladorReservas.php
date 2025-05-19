<?php
// app/controladores/ControladorReservas.php

class ControladorReservas {

    private $db;
    private $conn;
    private $modeloReservas;

    public function __construct() {
        require_once ROOT_PATH . '/app/modelos/Database.php';
        require_once ROOT_PATH . '/app/modelos/ModeloReservas.php'; // Incluir el modelo de reservas

        $this->db = new Database();
        $this->conn = $this->db->getConnection();

        if ($this->conn === null) {
            if (php_sapi_name() !== 'cli') { 
                 header('Content-Type: application/json');
                 http_response_code(500); 
            }
            echo json_encode(['exito' => false, 'mensaje' => 'Error crítico: No se pudo conectar a la base de datos.']);
            exit; 
        }
        $this->modeloReservas = new ModeloReservas(); 
    }

    private function cargarLayout($vista_contenido_path, $datos_para_layout) {
        if (!isset($datos_para_layout['mostrar_buscador_header'])) {
            $datos_para_layout['mostrar_buscador_header'] = false; 
        }
        extract($datos_para_layout);
        $vista_contenido = $vista_contenido_path;
        require_once ROOT_PATH . '/app/vistas/layout_principal.php';
    }

    public function index() {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            $_SESSION['login_redirect'] = BASE_URL . '/reservas'; 
            header('Location: ' . BASE_URL . '/auth/loginForm');
            exit();
        }
        $idUsuarioActual = $_SESSION['user_id_db'] ?? null; 
        
        if (!$idUsuarioActual) {
            error_log("ControladorReservas::index - user_id_db no encontrado en sesión.");
            $_SESSION['mensaje_reserva'] = ['tipo' => 'error', 'texto' => 'Error al identificar al usuario. Por favor, inicie sesión nuevamente.'];
        }

        $datos_vista['reservas'] = [];
        if ($idUsuarioActual) { 
            $datos_vista['reservas'] = $this->modeloReservas->obtenerAgendamientosPorUsuario($idUsuarioActual);
        }
        $datos_vista['titulo_seccion'] = "Mis Agendamientos";

        if (isset($_SESSION['mensaje_reserva'])) {
            $datos_vista['mensaje_reserva'] = $_SESSION['mensaje_reserva'];
            unset($_SESSION['mensaje_reserva']);
        }

        $datos_layout['titulo_pagina'] = "Mis Agendamientos - TUTTOR-UP";
        $datos_layout['controlador_actual'] = 'ControladorReservas';
        $datos_layout['metodo_actual'] = 'index';
        $datos_layout['datos_vista'] = $datos_vista;
        // Asegúrate de que 'mis_agendamientos.js' se crea y contiene el código JS para cancelar
        $datos_layout['js_especifico'] = ['mis_agendamientos.js']; 

        $this->cargarLayout(ROOT_PATH . '/app/vistas/reservas/index.php', $datos_layout);
    }

    public function crear() {
        // error_log("RESERVAS CREAR - Sesión al inicio: " . print_r($_SESSION, true)); // Descomentar para depurar si es necesario
        header('Content-Type: application/json'); 

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            http_response_code(403); 
            echo json_encode(['exito' => false, 'mensaje' => 'Debes iniciar sesión para realizar una reserva.']);
            exit();
        }
        
        $idEstudiante = $_SESSION['user_id_db'] ?? null; 

        if (!$idEstudiante) {
             http_response_code(400); 
             echo json_encode(['exito' => false, 'mensaje' => 'No se pudo identificar al usuario para la reserva. Intente iniciar sesión de nuevo.']);
             exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idDocente = filter_input(INPUT_POST, 'id_docente', FILTER_VALIDATE_INT);
            
            $fechaTutoriaInput = $_POST['fecha_tutoria'] ?? null;
            $bloqueHorarioInput = $_POST['bloque_horario'] ?? null;
            $materiaInput = $_POST['materia'] ?? null;

            if ($fechaTutoriaInput === null || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fechaTutoriaInput)) {
                echo json_encode(['exito' => false, 'mensaje' => 'Formato de fecha inválido. Use YYYY-MM-DD.']);
                exit();
            }
            
            if (!$idDocente || empty($bloqueHorarioInput)) {
                echo json_encode(['exito' => false, 'mensaje' => 'Faltan datos para la reserva (docente, fecha u hora).']);
                exit();
            }

            $datosReserva = [
                'id_estudiante' => $idEstudiante,
                'id_docente' => $idDocente,
                'fecha_tutoria' => $fechaTutoriaInput,    
                'bloque_horario' => $bloqueHorarioInput, 
                'materia' => $materiaInput,              
            ];

            $resultado = $this->modeloReservas->crearReserva($datosReserva);

            if ($resultado['exito']) {
                $_SESSION['mensaje_reserva'] = ['tipo' => 'exito', 'texto' => $resultado['mensaje']];
            } else {
                $_SESSION['mensaje_reserva'] = ['tipo' => 'error', 'texto' => $resultado['mensaje']];
            }

            echo json_encode($resultado);
            exit();

        } else {
            http_response_code(405); 
            echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido.']);
            exit();
        }
    }

    public function horariosOcupados() {
        header('Content-Type: application/json'); 
        
        $idDocente = filter_input(INPUT_GET, 'idDocente', FILTER_VALIDATE_INT);
        $fechaTutoriaInput = $_GET['fechaTutoria'] ?? null;

        if ($fechaTutoriaInput === null || !$idDocente || !preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fechaTutoriaInput)) {
            echo json_encode(['exito' => false, 'horarios' => [], 'mensaje' => 'Datos inválidos: idDocente requerido y fechaTutoria debe ser YYYY-MM-DD.']);
            exit(); 
        }

        $horarios = $this->modeloReservas->obtenerHorariosOcupados($idDocente, $fechaTutoriaInput);
        
        if (!is_array($horarios)) {
            error_log("ModeloReservas::obtenerHorariosOcupados no devolvió un array para idDocente: $idDocente, fecha: $fechaTutoriaInput");
            $horarios = []; 
        }

        echo json_encode(['exito' => true, 'horarios' => $horarios]);
        exit(); 
    }

    /**
     * Endpoint AJAX para cancelar una reserva.
     * Espera el ID del agendamiento en la URL (ej. /reservas/cancelar/123).
     * El router debe pasar el ID como parámetro al método.
     */
    public function cancelar($id_agendamiento_a_cancelar = null) {
        // error_log("RESERVAS CANCELAR - Sesión al inicio: " . print_r($_SESSION, true)); // Descomentar para depurar
        // error_log("RESERVAS CANCELAR - ID Agendamiento recibido: " . $id_agendamiento_a_cancelar); // Descomentar para depurar
        
        header('Content-Type: application/json');

        // Verificar el método HTTP (el JS usa POST por simplicidad, podría ser DELETE)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
            http_response_code(405); // Method Not Allowed
            echo json_encode(['exito' => false, 'mensaje' => 'Método no permitido. Se esperaba POST.']);
            exit();
        }

        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            http_response_code(403); // Forbidden - No autenticado
            echo json_encode(['exito' => false, 'mensaje' => 'Debes iniciar sesión para cancelar una reserva.']);
            exit();
        }

        $idEstudiante = $_SESSION['user_id_db'] ?? null;
        if (!$idEstudiante) {
            http_response_code(401); // Unauthorized - Autenticado pero no se puede identificar
            echo json_encode(['exito' => false, 'mensaje' => 'No se pudo identificar al usuario.']);
            exit();
        }

        // Validar el ID del agendamiento (debe ser numérico y > 0)
        $id_agendamiento_a_cancelar = filter_var($id_agendamiento_a_cancelar, FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
        if ($id_agendamiento_a_cancelar === false || $id_agendamiento_a_cancelar === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['exito' => false, 'mensaje' => 'ID de agendamiento inválido o no proporcionado.']);
            exit();
        }

        $resultado = $this->modeloReservas->cancelarReserva($id_agendamiento_a_cancelar, $idEstudiante);

        // Establecer el código de estado HTTP basado en el resultado del modelo
        if ($resultado['exito']) {
            http_response_code(200); // OK
        } else {
            // Usar el código de error proporcionado por el modelo si existe, o 500 por defecto
            $http_status_code = $resultado['codigo_error'] ?? 500;
            http_response_code($http_status_code);
        }
        
        echo json_encode($resultado);
        exit();
    }
}
?>