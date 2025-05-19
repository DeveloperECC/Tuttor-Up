<?php
// TUTORUP/app/modelos/Database.php

class Database {
    private $host = "localhost";
    private $db_name = "Tuttor"; // Nombre de tu base de datos
    private $username = "root";
    private $password = "root"; // Reemplaza con tu contraseña de MAMP MySQL (a menudo 'root' o vacía)
    public $conn;

    // Obtener la conexión a la BD
    public function getConnection() {
        $this->conn = null; // Limpiar conexión previa

        try {
            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);
            if ($this->conn->connect_error) {
                throw new Exception("Error de conexión: " . $this->conn->connect_error);
            }
            $this->conn->set_charset("utf8");
        } catch (Exception $e) {
            // En un entorno de producción, loguearías este error en lugar de mostrarlo.
            echo "Error de conexión: " . $e->getMessage();
            // Podrías optar por die() o retornar null si la conexión falla críticamente.
            // Por ahora, permitiremos que el script continúe para que otros errores puedan ser visibles.
            return null;
        }
        return $this->conn;
    }
}
?>