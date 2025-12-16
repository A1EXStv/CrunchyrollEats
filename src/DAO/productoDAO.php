<?php
include_once 'C:\xampp\htdocs\CrunchyrollEats\config\db.php';

class ProductoDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM productos";
        $result = $this->conn->query($sql);
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }
        return $productos;
    }

    public function obtener($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM productos WHERE id_producto = $id";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function crear($data) {
        $nombre = $this->conn->real_escape_string($data['nombre']);
        $descripcion = $this->conn->real_escape_string($data['descripcion']);
        $precio = (float)$data['precio'];
        $id_categoria = (int)$data['id_categoria'];
        $id_serie = isset($data['id_serie']) ? (int)$data['id_serie'] : 'NULL';
        $imagen = isset($data['imagen']) ? $this->conn->real_escape_string($data['imagen']) : '';

        $sql = "INSERT INTO productos (nombre, descripcion, precio, id_categoria, id_serie, imagen) 
                VALUES ('$nombre', '$descripcion', $precio, $id_categoria, $id_serie, '$imagen')";
        
        return $this->conn->query($sql);
    }

    public function actualizar($id, $data) {
        $id = (int)$id;
        $nombre = $this->conn->real_escape_string($data['nombre']);
        $descripcion = $this->conn->real_escape_string($data['descripcion']);
        $precio = (float)$data['precio'];
        $id_categoria = (int)$data['id_categoria'];
        $id_serie = isset($data['id_serie']) ? (int)$data['id_serie'] : 'NULL';
        $imagenPart = "";
        if (isset($data['imagen'])) {
            $imagen = $this->conn->real_escape_string($data['imagen']);
            $imagenPart = ", imagen = '$imagen'";
        }

        $sql = "UPDATE productos SET 
                nombre = '$nombre', 
                descripcion = '$descripcion', 
                precio = $precio, 
                id_categoria = $id_categoria, 
                id_serie = $id_serie 
                $imagenPart
                WHERE id_producto = $id";
        
        return $this->conn->query($sql);
    }

    public function eliminar($id) {
        $id = (int)$id;
        $sql = "DELETE FROM productos WHERE id_producto = $id";
        return $this->conn->query($sql);
    }
}
?>
