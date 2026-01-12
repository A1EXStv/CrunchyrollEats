<?php
require_once "src/DAO/UsuarioDAO.php";
require_once "src/DAO/ProductoDAO.php";
require_once "src/DAO/DescuentoDAO.php";

class ApiController {
    
    public function __construct() {
        // Allow CORS for development if needed, strictly speaking for same-origin it's not needed but good for testing ??
        // Since it's 100% js, likely same origin.
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
    }

    private function logActivity($accion) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $id_usuario = $_SESSION['usuario']['id'] ?? null;
        if ($id_usuario) {
            require_once __DIR__ . '/../DAO/LogDAO.php';
            $logDAO = new LogDAO();
            $logDAO->registrar($id_usuario, $accion);
        }
    }

    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        $json = json_encode($data);
        if ($json === false) {
             echo json_encode(['error' => 'Encoding error', 'msg' => json_last_error_msg()]);
        } else {
             echo $json;
        }
        exit;
    }

    private function getInput() {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }

    public function login() {
        $input = $this->getInput();
        if (!isset($input['email'], $input['password'])) {
            $this->jsonResponse(['error' => 'Missing credentials'], 400);
        }

        $usuarioDAO = new UsuarioDAO();
        $user = $usuarioDAO->verificarLogin($input['email'], $input['password']);

        if ($user) {
            // Start session to persist login if needed, or just return role for basic implementation
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_id'] = $user->getId_usuario();
            $_SESSION['role'] = $user->getRol();
            
            $this->jsonResponse([
                'status' => 'success',
                'user' => [
                    'id' => $user->getId_usuario(),
                    'nombre' => $user->getNombre(),
                    'email' => $user->getEmail(),
                    'telefono' => $user->getTelefono(),
                    'role' => $user->getRol()
                ]
            ]);
        } else {
            $this->jsonResponse(['error' => 'Invalid credentials'], 401);
        }
    }

    public function checkAuth() {
         if (session_status() === PHP_SESSION_NONE) session_start();
         if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['id'])) {
             $this->jsonResponse([
                 'authenticated' => true,
                 'role' => $_SESSION['usuario']['rol'] ?? 'user'
             ]);
         } else {
             $this->jsonResponse(['authenticated' => false], 401);
         }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        $this->jsonResponse(['status' => 'logged out']);
    }

    // --- Products ---

    public function getProductos() {
        $dao = new ProductoDAO();
        $productos = $dao->obtenerTodos();
        $this->jsonResponse($productos);
    }

    public function getProducto() {
        $id = $_GET['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);
        
        $dao = new ProductoDAO();
        $producto = $dao->obtener($id);
        if ($producto) {
            $this->jsonResponse($producto);
        } else {
            $this->jsonResponse(['error' => 'Not found'], 404);
        }
    }

    public function saveProducto() {
        // Check admin role
        $this->requireAdmin();

        $input = $this->getInput();
        // Basic validation
        if (empty($input['nombre']) || empty($input['precio'])) {
            $this->jsonResponse(['error' => 'Invalid data'], 400);
        }

        $dao = new ProductoDAO();
        if (isset($input['id_producto']) && $input['id_producto']) {
            if ($dao->actualizar($input['id_producto'], $input)) {
                $this->logActivity("Producto actualizado: " . $input['nombre'] . " (ID: " . $input['id_producto'] . ")");
                $this->jsonResponse(['status' => 'updated']);
            } else {
                $this->jsonResponse(['error' => 'Update failed'], 500);
            }
        } else {
            if ($dao->crear($input)) {
                $this->logActivity("Nuevo producto creado: " . $input['nombre']);
                $this->jsonResponse(['status' => 'created']);
            } else {
                $this->jsonResponse(['error' => 'Creation failed'], 500);
            }
        }
    }

    public function deleteProducto() {
        $this->requireAdmin();
        $input = $this->getInput();
        $id = $input['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);

        $dao = new ProductoDAO();
        if ($dao->eliminar($id)) {
            $this->logActivity("Producto eliminado (ID: $id)");
            $this->jsonResponse(['status' => 'deleted']);
        } else {
            $this->jsonResponse(['error' => 'Delete failed'], 500);
        }
    }

    // --- Users ---

    public function getUsers() {
        $this->requireAdmin();
        $dao = new UsuarioDAO();
        $usersObjects = $dao->obtenerTodos();
        $users = [];
        foreach($usersObjects as $u) {
            $users[] = [
                'id' => $u->getId_usuario(),
                'nombre' => $u->getNombre(),
                'email' => $u->getEmail(),
                'telefono' => $u->getTelefono(),
                'role' => $u->getRol()
            ];
        }
        $this->jsonResponse($users);
    }

    public function deleteUser() {
        $this->requireAdmin();
        // Implement delete in UsuarioDAO if not exists? 
        // UsuarioDAO didn't seem to have delete. Let's check logic.
        // For now, assume we might need to add it or fail.
        // Checking task: I didn't add deleteUser to UsuarioDAO plan.
        // I will return not implemented for now or skip.
        $this->jsonResponse(['error' => 'Not implemented'], 501);
    }

    // --- User Edit ---
    
    public function saveUser() {
        $this->requireAdmin();
        $input = $this->getInput();
        if (empty($input['id_usuario'])) {
            $this->jsonResponse(['error' => 'Missing User ID'], 400);
        }

        $dao = new UsuarioDAO();
        if ($dao->actualizar($input['id_usuario'], $input)) {
            $this->logActivity("Usuario actualizado (ID: " . $input['id_usuario'] . ")");
            $this->jsonResponse(['status' => 'updated']);
        } else {
            $this->jsonResponse(['error' => 'Update failed'], 500);
        }
    }

    // --- Series ---

    public function getSeries() {
        require_once "src/DAO/SerieDAO.php";
        $dao = new SerieDAO();
        $series = $dao->obtenerTodos();
        $this->jsonResponse($series);
    }

    public function getSerie() {
        require_once "src/DAO/SerieDAO.php";
        $id = $_GET['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);

        $dao = new SerieDAO();
        $serie = $dao->obtener($id);
        if ($serie) {
            $this->jsonResponse($serie);
        } else {
            $this->jsonResponse(['error' => 'Not found'], 404);
        }
    }

    public function saveSerie() {
        $this->requireAdmin();
        require_once "src/DAO/SerieDAO.php";
        $input = $this->getInput();
        if (empty($input['nombre'])) {
            $this->jsonResponse(['error' => 'Invalid data'], 400);
        }

        $dao = new SerieDAO();
        if (isset($input['id_serie']) && $input['id_serie']) {
            if ($dao->actualizar($input['id_serie'], $input)) {
                $this->logActivity("Serie actualizada: " . $input['nombre'] . " (ID: " . $input['id_serie'] . ")");
                $this->jsonResponse(['status' => 'updated']);
            } else {
                $this->jsonResponse(['error' => 'Update failed'], 500);
            }
        } else {
            if ($dao->crear($input)) {
                $this->logActivity("Nueva serie creada: " . $input['nombre']);
                $this->jsonResponse(['status' => 'created']);
            } else {
                $this->jsonResponse(['error' => 'Creation failed'], 500);
            }
        }
    }

    public function deleteSerie() {
        $this->requireAdmin();
        require_once "src/DAO/SerieDAO.php";
        $input = $this->getInput();
        $id = $input['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);

        $dao = new SerieDAO();
        if ($dao->eliminar($id)) {
            $this->logActivity("Serie eliminada (ID: $id)");
            $this->jsonResponse(['status' => 'deleted']);
        } else {
            $this->jsonResponse(['error' => 'Delete failed'], 500);
        }
    }

    // --- Discounts ---

    public function getDescuentos() {
        $this->requireAdmin();
        $dao = new DescuentoDAO();
        $this->jsonResponse($dao->obtenerTodos());
    }

    public function getDescuento() {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);
        $dao = new DescuentoDAO();
        $descuento = $dao->obtener($id);
        if ($descuento) {
            $this->jsonResponse($descuento);
        } else {
            $this->jsonResponse(['error' => 'Not found'], 404);
        }
    }

    public function saveDescuento() {
        $this->requireAdmin();
        $input = $this->getInput();
        if (empty($input['nombre']) || empty($input['valor'])) {
            $this->jsonResponse(['error' => 'Invalid data'], 400);
        }

        $dao = new DescuentoDAO();
        if (isset($input['id_descuento']) && $input['id_descuento']) {
            if ($dao->actualizar($input['id_descuento'], $input)) {
                $this->logActivity("Descuento actualizado: " . $input['nombre']);
                $this->jsonResponse(['status' => 'updated']);
            } else {
                $this->jsonResponse(['error' => 'Update failed'], 500);
            }
        } else {
            $id = $dao->crear($input);
            if ($id) {
                $this->logActivity("Nuevo descuento creado: " . $input['nombre']);
                $this->jsonResponse(['status' => 'created', 'id' => $id]);
            } else {
                $this->jsonResponse(['error' => 'Creation failed'], 500);
            }
        }
    }

    public function deleteDescuento() {
        $this->requireAdmin();
        $input = $this->getInput();
        $id = $input['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);

        $dao = new DescuentoDAO();
        if ($dao->eliminar($id)) {
            $this->logActivity("Descuento eliminado (ID: $id)");
            $this->jsonResponse(['status' => 'deleted']);
        } else {
            $this->jsonResponse(['error' => 'Delete failed'], 500);
        }
    }

    public function toggleDescuento() {
        $this->requireAdmin();
        $input = $this->getInput();
        if (!isset($input['id'], $input['activo'])) {
            $this->jsonResponse(['error' => 'Missing data'], 400);
        }
        $dao = new DescuentoDAO();
        if ($dao->toggleActivo($input['id'], $input['activo'])) {
            $estado = $input['activo'] ? 'activado' : 'desactivado';
            $this->logActivity("Descuento $estado (ID: " . $input['id'] . ")");
            $this->jsonResponse(['status' => 'toggled']);
        } else {
            $this->jsonResponse(['error' => 'Toggle failed'], 500);
        }
    }

    // --- Logs ---

    public function getLogs() {
        $this->requireAdmin();
        require_once __DIR__ . "/../DAO/LogDAO.php";
        $dao = new LogDAO();
        $this->jsonResponse($dao->obtenerTodos());
    }

    // --- Orders ---

    public function getPedidos() {
        $this->requireAdmin();
        require_once __DIR__ . "/../DAO/PedidoDAO.php";
        $dao = new PedidoDAO();
        $this->jsonResponse($dao->obtenerTodos());
    }

    public function getPedido() {
        $this->requireAdmin();
        $id = $_GET['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);
        
        require_once __DIR__ . "/../DAO/PedidoDAO.php";
        $dao = new PedidoDAO();
        $pedido = $dao->obtener($id);
        if ($pedido) {
            $pedido['detalles'] = $dao->obtenerDetalles($id);
            $this->jsonResponse($pedido);
        } else {
            $this->jsonResponse(['error' => 'Not found'], 404);
        }
    }

    public function updatePedidoEstado() {
        $this->requireAdmin();
        
        // Read input once and parse
        $rawInput = file_get_contents('php://input');
        $input = json_decode($rawInput, true) ?? [];
        
        if (!isset($input['id_pedido'], $input['estado'])) {
            $this->jsonResponse(['error' => 'Missing data'], 400);
        }

        require_once __DIR__ . "/../DAO/PedidoDAO.php";
        $dao = new PedidoDAO();
        
        if ($dao->actualizarEstado($input['id_pedido'], $input['estado'])) {
            $this->logActivity("Estado de pedido actualizado (ID: " . $input['id_pedido'] . ") a: " . $input['estado']);
            $this->jsonResponse(['status' => 'updated']);
        } else {
            $this->jsonResponse(['error' => 'Update failed'], 500);
        }
    }

    private function requireAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['rol']) || $_SESSION['usuario']['rol'] !== 'admin') {
            $this->jsonResponse(['error' => 'Forbidden'], 403);
        }
    }
}
