<?php
include_once 'db.php';

class ProductoDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect(); // Usas tu clase DBConnection
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM productos";
        $result = $this->conn->query($sql);
        $productos = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }
        return $productos;
    }
}
?>
