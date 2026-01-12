<?php
require_once __DIR__ . '/../../config/db.php';

class DescuentoDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect();
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM descuentos";
        $result = $this->conn->query($sql);
        $descuentos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $descuentos[] = $row;
            }
        }
        return $descuentos;
    }

    public function obtener($id) {
        $id = (int)$id;
        $sql = "SELECT * FROM descuentos WHERE id_descuento = $id";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $descuento = $result->fetch_assoc();
            $descuento['productos'] = $this->obtenerProductosPorDescuento($id);
            return $descuento;
        }
        return null;
    }

    public function obtenerProductosPorDescuento($id_descuento) {
        $id_descuento = (int)$id_descuento;
        $sql = "SELECT id_producto FROM descuento_producto WHERE id_descuento = $id_descuento";
        $result = $this->conn->query($sql);
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $productos[] = (int)$row['id_producto'];
            }
        }
        return $productos;
    }

    public function crear($data) {
        $nombre = $this->conn->real_escape_string($data['nombre']);
        $tipo = $this->conn->real_escape_string($data['tipo']);
        $valor = (float)$data['valor'];
        // Start date is always NOW() as requested
        $fecha_inicio = "NOW()";
        $fecha_fin = !empty($data['fecha_fin']) ? "'" . $this->conn->real_escape_string($data['fecha_fin']) . "'" : "NULL";
        $activo = isset($data['activo']) ? (int)$data['activo'] : 1;

        $sql = "INSERT INTO descuentos (nombre, tipo, valor, fecha_inicio, fecha_fin, activo) 
                VALUES ('$nombre', '$tipo', $valor, $fecha_inicio, $fecha_fin, $activo)";
        
        if ($this->conn->query($sql)) {
            $id_descuento = $this->conn->insert_id;
            if (!empty($data['productos'])) {
                $this->asignarProductos($id_descuento, $data['productos']);
            }
            return $id_descuento;
        }
        return false;
    }

    public function actualizar($id, $data) {
        $id = (int)$id;
        $nombre = $this->conn->real_escape_string($data['nombre']);
        $tipo = $this->conn->real_escape_string($data['tipo']);
        $valor = (float)$data['valor'];
        $fecha_inicio = !empty($data['fecha_inicio']) ? "'" . $this->conn->real_escape_string($data['fecha_inicio']) . "'" : "NULL";
        $fecha_fin = !empty($data['fecha_fin']) ? "'" . $this->conn->real_escape_string($data['fecha_fin']) . "'" : "NULL";
        $activo = isset($data['activo']) ? (int)$data['activo'] : 1;

        $sql = "UPDATE descuentos SET 
                nombre = '$nombre', 
                tipo = '$tipo', 
                valor = $valor, 
                fecha_fin = $fecha_fin, 
                activo = $activo 
                WHERE id_descuento = $id";
        
        $res = $this->conn->query($sql);
        if ($res) {
            $this->quitarTodosProductos($id);
            if (!empty($data['productos'])) {
                $this->asignarProductos($id, $data['productos']);
            }
        }
        return $res;
    }

    public function eliminar($id) {
        $id = (int)$id;
        $sql = "DELETE FROM descuentos WHERE id_descuento = $id";
        return $this->conn->query($sql);
    }

    public function asignarProductos($id_descuento, $ids_productos) {
        $id_descuento = (int)$id_descuento;
        foreach ($ids_productos as $id_producto) {
            $id_producto = (int)$id_producto;
            $sql = "INSERT IGNORE INTO descuento_producto (id_descuento, id_producto) VALUES ($id_descuento, $id_producto)";
            $this->conn->query($sql);
        }
    }

    public function quitarTodosProductos($id_descuento) {
        $id_descuento = (int)$id_descuento;
        $sql = "DELETE FROM descuento_producto WHERE id_descuento = $id_descuento";
        return $this->conn->query($sql);
    }

    public function toggleActivo($id, $activo) {
        $id = (int)$id;
        $activo = (int)$activo;
        $sql = "UPDATE descuentos SET activo = $activo WHERE id_descuento = $id";
        return $this->conn->query($sql);
    }
}
