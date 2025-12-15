<?php
require_once "src/DAO/usuarioDAO.php";
require_once "src/DAO/productoDAO.php";

class ApiController {
    
    public function __construct() {
        // Allow CORS for development if needed, strictly speaking for same-origin it's not needed but good for testing ??
        // Since it's 100% js, likely same origin.
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
    }

    private function jsonResponse($data, $status = 200) {
        http_response_code($status);
        echo json_encode($data);
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
                $this->jsonResponse(['status' => 'updated']);
            } else {
                $this->jsonResponse(['error' => 'Update failed'], 500);
            }
        } else {
            if ($dao->crear($input)) {
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
            $this->jsonResponse(['status' => 'updated']);
        } else {
            $this->jsonResponse(['error' => 'Update failed'], 500);
        }
    }

    // --- Series ---

    public function getSeries() {
        require_once "src/DAO/serieDAO.php";
        $dao = new serieDAO();
        $series = $dao->obtenerTodos();
        $this->jsonResponse($series);
    }

    public function getSerie() {
        require_once "src/DAO/serieDAO.php";
        $id = $_GET['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);

        $dao = new serieDAO();
        $serie = $dao->obtener($id);
        if ($serie) {
            $this->jsonResponse($serie);
        } else {
            $this->jsonResponse(['error' => 'Not found'], 404);
        }
    }

    public function saveSerie() {
        $this->requireAdmin();
        require_once "src/DAO/serieDAO.php";
        $input = $this->getInput();
        if (empty($input['nombre'])) {
            $this->jsonResponse(['error' => 'Invalid data'], 400);
        }

        $dao = new serieDAO();
        if (isset($input['id_serie']) && $input['id_serie']) {
            if ($dao->actualizar($input['id_serie'], $input)) {
                $this->jsonResponse(['status' => 'updated']);
            } else {
                $this->jsonResponse(['error' => 'Update failed'], 500);
            }
        } else {
            if ($dao->crear($input)) {
                $this->jsonResponse(['status' => 'created']);
            } else {
                $this->jsonResponse(['error' => 'Creation failed'], 500);
            }
        }
    }

    public function deleteSerie() {
        $this->requireAdmin();
        require_once "src/DAO/serieDAO.php";
        $input = $this->getInput();
        $id = $input['id'] ?? null;
        if (!$id) $this->jsonResponse(['error' => 'Missing ID'], 400);

        $dao = new serieDAO();
        if ($dao->eliminar($id)) {
            $this->jsonResponse(['status' => 'deleted']);
        } else {
            $this->jsonResponse(['error' => 'Delete failed'], 500);
        }
    }

    private function requireAdmin() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['rol']) || $_SESSION['usuario']['rol'] !== 'admin') {
            $this->jsonResponse(['error' => 'Forbidden'], 403);
        }
    }
}