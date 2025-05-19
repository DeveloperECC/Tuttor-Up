<?php
// TUTORUP/app/controladores/ControladorAuth.php

class ControladorAuth {

    private $db;
    private $conn;

    public function __construct() {
        require_once ROOT_PATH . '/app/modelos/Database.php';
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
        if ($this->conn === null) {
            die("Error fatal: No se pudo conectar a la base de datos en ControladorAuth.");
        }
    }

    // ... (otros métodos como index, loginForm, registroForm no cambian) ...
    public function index($mostrarFormulario = 'login') { 
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            header('Location: ' . BASE_URL . '/'); 
            exit();
        }
        $datos_vista['showLogin'] = ($mostrarFormulario === 'login');
        $datos_vista['showRegister'] = ($mostrarFormulario === 'registro');
        if (isset($_SESSION['login_error'])) {
            $datos_vista['login_error'] = $_SESSION['login_error'];
            unset($_SESSION['login_error']);
        }
        if (isset($_SESSION['register_error'])) {
            $datos_vista['register_error'] = $_SESSION['register_error'];
            unset($_SESSION['register_error']);
        }
        if (isset($_SESSION['register_success'])) {
            $datos_vista['register_success'] = $_SESSION['register_success'];
            unset($_SESSION['register_success']);
        }
        $datos_layout['titulo_pagina'] = "Acceso / Registro";
        $datos_layout['controlador_actual'] = 'ControladorAuth';
        $datos_layout['metodo_actual'] = 'index';
        $datos_layout['css_especifico'] = ['estilos_auth.css']; 
        $datos_layout['js_especifico'] = ['auth_toggle.js'];   
        $datos_layout['datos_vista'] = $datos_vista;
        $datos_layout['mostrar_buscador_header'] = false; 
        require_once ROOT_PATH . '/app/vistas/auth/index.php';
    }

    public function loginForm() {
        $this->index('login');
    }

    public function registroForm() {
        $this->index('registro');
    }


    public function procesarLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['boton_ingresar'])) {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($email) || empty($password)) {
                $_SESSION['login_error'] = 'Email y contraseña son requeridos.';
                header('Location: ' . BASE_URL . '/auth/loginForm');
                exit();
            }
            
            // ***** CORRECCIÓN CRÍTICA AQUÍ *****
            // Incluir 'id' en la consulta SELECT
            $query = "SELECT id, DNI, nombre, password_hash FROM usuarios WHERE email = ? LIMIT 1";
            $stmt = $this->conn->prepare($query);
            
            if ($stmt) {
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $stmt->store_result(); 
                
                if ($stmt->num_rows == 1) {
                    // ***** CORRECCIÓN CRÍTICA AQUÍ *****
                    // Añadir $id_usuario_db al bind_result
                    $stmt->bind_result($id_usuario_db, $dni, $nombre, $password_hash_db);
                    $stmt->fetch();
                    
                    if (password_verify($password, $password_hash_db)) {
                        // ***** CORRECCIÓN CRÍTICA AQUÍ *****
                        // Ahora $id_usuario_db SÍ tendrá un valor
                        $_SESSION['user_id_db'] = $id_usuario_db; 
                        $_SESSION['user_dni'] = $dni;
                        $_SESSION['user_email'] = $email;
                        $_SESSION['user_nombre'] = $nombre; 
                        $_SESSION['logged_in'] = true;
                        
                        // ---- DEBUGGING ----
                        // Ahora $id_usuario_db debería tener un valor
                        error_log("AUTH/PROCESARLOGIN - User ID DB establecido: " . $id_usuario_db);
                        error_log("AUTH/PROCESARLOGIN - Contenido de SESSION: " . print_r($_SESSION, true));
                        // ---- FIN DEBUGGING ----
                        
                        header('Location: ' . BASE_URL . '/'); 
                        exit();
                    }
                }
                $stmt->close();
            }
            $_SESSION['login_error'] = 'Credenciales inválidas.';
            header('Location: ' . BASE_URL . '/auth/loginForm');
            exit();
        } else {
            header('Location: ' . BASE_URL . '/auth/loginForm');
            exit();
        }
    }

    // ... (procesarRegistro y logout no cambian respecto a tu versión) ...
    public function procesarRegistro() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registro'])) {
            $name = trim($_POST['name'] ?? '');
            $cedula = trim($_POST['cedula'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            
            if (empty($name) || empty($cedula) || empty($email) || empty($password)) {
                $_SESSION['register_error'] = 'Todos los campos son obligatorios.';
                header('Location: ' . BASE_URL . '/auth/registroForm');
                exit();
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['register_error'] = 'Formato de email inválido.';
                header('Location: ' . BASE_URL . '/auth/registroForm');
                exit();
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            try {
                $stmt = $this->conn->prepare("INSERT INTO usuarios (nombre, DNI, email, password_hash) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('ssss', $name, $cedula, $email, $hash);
                
                if ($stmt->execute()) {
                    $_SESSION['register_success'] = true; 
                    header('Location: ' . BASE_URL . '/auth/loginForm?success=1'); 
                    exit();
                } else {
                    throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
                }
            } catch (mysqli_sql_exception $e) {
                if ($e->getCode() == 1062) { 
                     $_SESSION['register_error'] = 'El email o DNI ya están registrados.';
                } else {
                    $_SESSION['register_error'] = 'Error en la base de datos: ' . $e->getMessage();
                }
                header('Location: ' . BASE_URL . '/auth/registroForm');
                exit();
            } catch (Exception $e) {
                $_SESSION['register_error'] = 'Error al registrar: ' . $e->getMessage();
                header('Location: ' . BASE_URL . '/auth/registroForm');
                exit();
            }
        } else {
            header('Location: ' . BASE_URL . '/auth/registroForm');
            exit();
        }
    }

    public function logout() {
        session_unset(); 
        session_destroy(); 
        header('Location: ' . BASE_URL . '/'); 
        exit();
    }
}
?>