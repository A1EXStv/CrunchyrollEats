<?php
require_once __DIR__ . '/../../config/db.php';

class ProductoDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect();
    }

    public function obtenerTodos($sort = null) {
        $sql = "SELECT p.*, d.nombre as descuento_nombre, d.tipo as descuento_tipo, d.valor as descuento_valor,
                (CASE 
                    WHEN d.id_descuento IS NOT NULL AND d.tipo = 'porcentaje' THEN p.precio * (1 - d.valor / 100)
                    WHEN d.id_descuento IS NOT NULL AND d.tipo = 'fijo' THEN p.precio - d.valor
                    ELSE p.precio 
                END) as precio_final
                FROM productos p
                LEFT JOIN (
                    SELECT dp2.id_producto, MAX(d2.id_descuento) as id_descuento
                    FROM descuento_producto dp2
                    JOIN descuentos d2 ON dp2.id_descuento = d2.id_descuento
                    WHERE d2.activo = 1 
                      AND (d2.fecha_inicio IS NULL OR d2.fecha_inicio <= NOW())
                      AND (d2.fecha_fin IS NULL OR d2.fecha_fin >= NOW())
                    GROUP BY dp2.id_producto
                ) active_dp ON p.id_producto = active_dp.id_producto
                LEFT JOIN descuentos d ON active_dp.id_descuento = d.id_descuento";
        $sql .= $this->getSortSql($sort, "p.");
        
        $result = $this->conn->query($sql);
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $p = new Producto(
                    $row['id_producto'],
                    $row['nombre'],
                    $row['descripcion'],
                    $row['precio'],
                    $row['id_categoria'],
                    $row['id_serie'],
                    $row['imagen']
                );
                // Set extra fields for discounts
                $p->setPrecio_final($row['precio_final']);
                $p->setDescuento_valor($row['descuento_valor']);
                $p->setDescuento_type($row['descuento_tipo']);
                $productos[] = $p;
            }
        }
        return $productos;
    }

    public function obtenerPorSeriesArray($ids, $sort = null) {
        if (empty($ids)) return [];
        
        // $ids = array_map('intval', $ids);
        // Simplificación: usar foreach en lugar de array_map
        $cleanIds = [];
        foreach ($ids as $id) {
            $cleanIds[] = (int)$id;
        }
        $idsStr = implode(',', $cleanIds);
        
        $sql = "SELECT p.*, d.nombre as descuento_nombre, d.tipo as descuento_tipo, d.valor as descuento_valor,
                (CASE 
                    WHEN d.id_descuento IS NOT NULL AND d.tipo = 'porcentaje' THEN p.precio * (1 - d.valor / 100)
                    WHEN d.id_descuento IS NOT NULL AND d.tipo = 'fijo' THEN p.precio - d.valor
                    ELSE p.precio 
                END) as precio_final
                FROM productos p
                LEFT JOIN (
                    SELECT dp2.id_producto, MAX(d2.id_descuento) as id_descuento
                    FROM descuento_producto dp2
                    JOIN descuentos d2 ON dp2.id_descuento = d2.id_descuento
                    WHERE d2.activo = 1 
                      AND (d2.fecha_inicio IS NULL OR d2.fecha_inicio <= NOW())
                      AND (d2.fecha_fin IS NULL OR d2.fecha_fin >= NOW())
                    GROUP BY dp2.id_producto
                ) active_dp ON p.id_producto = active_dp.id_producto
                LEFT JOIN descuentos d ON active_dp.id_descuento = d.id_descuento
                WHERE p.id_serie IN ($idsStr)";
        $sql .= $this->getSortSql($sort, "p.");
        
        $result = $this->conn->query($sql);
        $productos = [];
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $p = new Producto(
                    $row['id_producto'],
                    $row['nombre'],
                    $row['descripcion'],
                    $row['precio'],
                    $row['id_categoria'],
                    $row['id_serie'],
                    $row['imagen']
                );
                $p->setPrecio_final($row['precio_final']);
                $p->setDescuento_valor($row['descuento_valor']);
                $p->setDescuento_type($row['descuento_tipo']);
                $productos[] = $p;
            }
        }
        return $productos;
    }

    private function getSortSql($sort, $prefix = "") {
        switch ($sort) {
            case 'price_asc':
                return " ORDER BY precio_final ASC";
            case 'price_desc':
                return " ORDER BY precio_final DESC";
            case 'best_sellers':
                // For now, just order by ID or something consistent
                return " ORDER BY id_producto DESC";
            default:
                return "";
        }
    }

    public function obtener($id) {
        $id = (int)$id;
        $sql = "SELECT p.*, d.nombre as descuento_nombre, d.tipo as descuento_tipo, d.valor as descuento_valor,
                (CASE 
                    WHEN d.id_descuento IS NOT NULL AND d.tipo = 'porcentaje' THEN p.precio * (1 - d.valor / 100)
                    WHEN d.id_descuento IS NOT NULL AND d.tipo = 'fijo' THEN p.precio - d.valor
                    ELSE p.precio 
                END) as precio_final
                FROM productos p
                LEFT JOIN (
                    SELECT dp2.id_producto, MAX(d2.id_descuento) as id_descuento
                    FROM descuento_producto dp2
                    JOIN descuentos d2 ON dp2.id_descuento = d2.id_descuento
                    WHERE d2.activo = 1 
                      AND (d2.fecha_inicio IS NULL OR d2.fecha_inicio <= NOW())
                      AND (d2.fecha_fin IS NULL OR d2.fecha_fin >= NOW())
                    GROUP BY dp2.id_producto
                ) active_dp ON p.id_producto = active_dp.id_producto
                LEFT JOIN descuentos d ON active_dp.id_descuento = d.id_descuento
                WHERE p.id_producto = $id";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $p = new Producto(
                $row['id_producto'],
                $row['nombre'],
                $row['descripcion'],
                $row['precio'],
                $row['id_categoria'],
                $row['id_serie'],
                $row['imagen']
            );
            $p->setPrecio_final($row['precio_final']);
            $p->setDescuento_valor($row['descuento_valor']);
            $p->setDescuento_type($row['descuento_tipo']);
            return $p;
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
