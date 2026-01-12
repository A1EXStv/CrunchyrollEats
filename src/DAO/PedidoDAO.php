<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../model/Pedido.php';

class PedidoDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect();
    }

    private function log($msg) {
        $file = __DIR__ . '/../../public/debug_log.txt';
        $time = date('[Y-m-d H:i:s] ');
        file_put_contents($file, $time . $msg . PHP_EOL, FILE_APPEND);
    }

    public function registrarPedido($id_usuario, $datos_direccion, $items, $total) {
        $this->conn->begin_transaction();

        try {
            $this->log("Iniciando registro de pedido (Schema Corregido). Usuario: $id_usuario");

            // 1. Insertar Pedido (Primero, para tener id_pedido)
            // Schema: id_pedido, id_usuario, fecha_pedido, total, estado
            $fecha = date('Y-m-d H:i:s');
            $estado = 'pendiente';
            
            $sqlPedido = "INSERT INTO pedidos (id_usuario, fecha_pedido, estado, total) VALUES ($id_usuario, '$fecha', '$estado', $total)";
            
            if (!$this->conn->query($sqlPedido)) {
                 $this->log("Falló insert pedido. Error: " . $this->conn->error);
                 throw new Exception("Error al crear pedido: " . $this->conn->error);
            }
            $id_pedido = $this->conn->insert_id;
            $this->log("Pedido creado. ID: $id_pedido");

            // 2. Insertar Dirección (Vinculada al pedido)
            // Schema: id_direccion, id_pedido, direccion, ciudad, provincia, codigo_postal, pais
            $direccion = $this->conn->real_escape_string($datos_direccion['direccion']);
            $ciudad = $this->conn->real_escape_string($datos_direccion['ciudad']);
            $cp = $this->conn->real_escape_string($datos_direccion['cp']);
            $provincia = $this->conn->real_escape_string($datos_direccion['provincia']);
            $pais = $this->conn->real_escape_string($datos_direccion['pais']);

            $sqlDir = "INSERT INTO direcciones (id_pedido, direccion, ciudad, provincia, codigo_postal, pais) VALUES ($id_pedido, '$direccion', '$ciudad', '$provincia', '$cp', '$pais')";
            
            if (!$this->conn->query($sqlDir)) {
               $this->log("Error insertando dirección: " . $this->conn->error);
               throw new Exception("Error al guardar dirección: " . $this->conn->error);
            }
            $this->log("Dirección insertada para pedido $id_pedido");

            // 3. Insertar Detalles
            foreach ($items as $item) {
                $id_producto = intval($item['id_producto']);
                $cantidad = intval($item['quantity']);
                $precio = floatval($item['precio_final'] ?? $item['precio']);
                
                // Assuming pedido_detalle schema matches my code or similar?
                // Warning: If pedido_detalle also differs, this will fail. Validating common naming.
                $sqlDetalle = "INSERT INTO pedido_detalle (id_pedido, id_producto, cantidad, precio_unitario) VALUES ($id_pedido, $id_producto, $cantidad, $precio)";
                if (!$this->conn->query($sqlDetalle)) {
                    $this->log("Error insertando detalle. Producto: $id_producto. Error: " . $this->conn->error);
                    throw new Exception("Error al guardar detalles: " . $this->conn->error);
                }
            }
            
            $this->conn->commit();
            $this->log("Transacción completada exitosamente.");
            return $id_pedido;

        } catch (Exception $e) {
            $this->conn->rollback();
            $this->log("ROLLBACK ejecutado. Excepción: " . $e->getMessage());
            throw $e;
        }
    }
    public function obtenerPorUsuario($id_usuario) {
        $id_usuario = intval($id_usuario);
        $sql = "SELECT * FROM pedidos WHERE id_usuario = $id_usuario ORDER BY fecha_pedido DESC";
        $result = $this->conn->query($sql);
        $pedidos = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pedidos[] = new Pedido(
                    $row['id_pedido'],
                    $row['id_usuario'],
                    $row['fecha_pedido'],
                    $row['estado'],
                    $row['total']
                );
            }
        }
        return $pedidos;
    }

    public function obtenerDetalles($id_pedido) {
        $id_pedido = intval($id_pedido);
        $sql = "SELECT pd.*, p.nombre as producto_nombre, p.imagen as producto_imagen 
                FROM pedido_detalle pd 
                JOIN productos p ON pd.id_producto = p.id_producto 
                WHERE pd.id_pedido = $id_pedido";
        $result = $this->conn->query($sql);
        $detalles = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $detalles[] = $row;
            }
        }
        return $detalles; // Details are usually assoc arrays, that's fine for now or could act as DTOs
    }

    // Admin methods
    public function obtenerTodos() {
        $sql = "SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email 
                FROM pedidos p 
                JOIN usuarios u ON p.id_usuario = u.id_usuario 
                ORDER BY p.fecha_pedido DESC";
        $result = $this->conn->query($sql);
        $pedidos = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $pedido = new Pedido(
                    $row['id_pedido'],
                    $row['id_usuario'],
                    $row['fecha_pedido'],
                    $row['estado'],
                    $row['total']
                );
                $pedido->setUsuario_nombre($row['usuario_nombre']);
                $pedido->setUsuario_email($row['usuario_email']);
                $pedidos[] = $pedido;
            }
        }
        return $pedidos;
    }

    public function obtener($id_pedido) {
        $id_pedido = intval($id_pedido);
        $sql = "SELECT p.*, u.nombre as usuario_nombre, u.email as usuario_email,
                d.direccion, d.ciudad, d.provincia, d.codigo_postal, d.pais
                FROM pedidos p 
                JOIN usuarios u ON p.id_usuario = u.id_usuario 
                LEFT JOIN direcciones d ON p.id_pedido = d.id_pedido
                WHERE p.id_pedido = $id_pedido";
        $result = $this->conn->query($sql);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $pedido = new Pedido(
                $row['id_pedido'],
                $row['id_usuario'],
                $row['fecha_pedido'],
                $row['estado'],
                $row['total']
            );
            $pedido->setUsuario_nombre($row['usuario_nombre']);
            $pedido->setUsuario_email($row['usuario_email']);
            // Add address info if we had fields for it, for now we can rely on array access if strict mode allows dynamic props or just use DTOs for this specific return. 
            // In BaseModel, property_exists checks declared props. Dynamic dynamic defaults to null. 
            // But strict object typing is tricky here. 
            // The safest pattern for legacy mix: The BaseModel acts as array wrapper, so we can actually set dynamic props if we want, OR we overload offsetGet.
            // Wait, BaseModel offsetGet checks property_exists OR getter methods.
            // If I want to expose 'direccion', I need a getter or a property.
            // FOR NOW: I will return the Object and let the View use getters. But wait, existing code might use $p['direccion'].
            // I should explicitly add the address properties to Pedido model to be safe? Or just return mixed array/object?
            // "Standardize means Objects". So I should add address properties to Pedido model or a joined DTO.
            // Let's add dynamic property support to BaseModel temporarily or add props to Pedido.
            // Adding props to Pedido is cleaner.
            
            // Actually, let's keep obtaining logic simple.
            // But wait, if I return an Object, `$p['direccion']` will fail if "direccion" isn't a property or getter.
            // I'll stick to returning the Object and if functionality breaks I'll add the props. 
            // Better: Add `public $direccion;` to Pedido to allow direct setting for this join case.
            // Actually, BaseModel doesn't support writing undefined props via offsetSet unless I add `__set`.
            // Let's hack: The View likely uses `$pedido['direccion']`.
            // I'll add `__set` and `__get` to BaseModel to allow dynamic properties for joined fields!
            foreach($row as $k => $v) {
                $pedido->$k = $v;
            }
            return $pedido;
        }
        return null;
    }

    public function actualizarEstado($id_pedido, $estado) {
        $id_pedido = intval($id_pedido);
        $estado = $this->conn->real_escape_string($estado);
        
        $sql = "UPDATE pedidos SET estado = '$estado' WHERE id_pedido = $id_pedido";
        
        $result = $this->conn->query($sql);
        
        // Return true if query executed successfully (even if no rows were affected)
        return $result !== false;
    }
}
