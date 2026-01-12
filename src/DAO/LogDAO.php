<?php
require_once __DIR__ . '/../../config/db.php';

class LogDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect();
    }

    public function registrar($id_usuario, $accion) {
        $id_usuario = $id_usuario ? (int)$id_usuario : 'NULL';
        $accion = $this->conn->real_escape_string($accion);
        
        $sql = "INSERT INTO logs (id_usuario, accion) VALUES ($id_usuario, '$accion')";
        return $this->conn->query($sql);
    }

    public function obtenerTodos() {
        // Join with usuarios to get user name
        $sql = "SELECT l.*, u.nombre as nombre_usuario, u.email as email_usuario 
                FROM logs l 
                LEFT JOIN usuarios u ON l.id_usuario = u.id_usuario 
                ORDER BY l.fecha DESC";
        
        $result = $this->conn->query($sql);
        $logs = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $logs[] = $row;
            }
        }
        return $logs;
    }
}
?>
