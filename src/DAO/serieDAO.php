<?php
require_once __DIR__ . '/../../config/db.php';

class SerieDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM series";
        $result = $this->conn->query($sql);
        $series = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $series[] = new Serie(
                    $row['id_serie'],
                    $row['nombre'],
                    $row['descripcion'] ?? null,
                    $row['imagen'] ?? null
                );
            }
        }
        return $series;
    }

    public function obtener($id) {
        $id = intval($id);
        $sql = "SELECT * FROM series WHERE id_serie = $id";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Serie(
                $row['id_serie'],
                $row['nombre'],
                $row['descripcion'] ?? null,
                $row['imagen'] ?? null
            );
        }
        return null;
    }

    public function crear($datos) {
        $nombre = $this->conn->real_escape_string($datos['nombre']);
        $sql = "INSERT INTO series (nombre) VALUES ('$nombre')";
        return $this->conn->query($sql);
    }

    public function actualizar($id, $datos) {
        $id = intval($id);
        $updates = [];
        if (isset($datos['nombre'])) {
            $nombre = $this->conn->real_escape_string($datos['nombre']);
            $updates[] = "nombre = '$nombre'";
        }
        if (empty($updates)) return true;
        
        $sql = "UPDATE series SET " . implode(', ', $updates) . " WHERE id_serie = $id";
        return $this->conn->query($sql);
    }

    public function eliminar($id) {
        $id = intval($id);
        $sql = "DELETE FROM series WHERE id_serie = $id";
        return $this->conn->query($sql);
    }
}
?>
