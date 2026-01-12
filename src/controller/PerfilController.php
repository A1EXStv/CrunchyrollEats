<?php

require_once __DIR__ . '/../DAO/UsuarioDAO.php';
require_once __DIR__ . '/../DAO/PedidoDAO.php';

class PerfilController {
    private $usuarioDAO;
    private $pedidoDAO;

    public function __construct() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=login&action=index");
            exit;
        }
        $this->usuarioDAO = new UsuarioDAO();
        $this->pedidoDAO = new PedidoDAO();
    }

    public function index() {
        $id_usuario = $_SESSION['usuario']['id'];
        $pedidos = $this->pedidoDAO->obtenerPorUsuario($id_usuario);
        
        // Prepare orders with details
        $historial = [];
        foreach ($pedidos as $pedido) {
            $detalles = $this->pedidoDAO->obtenerDetalles($pedido->getId());
            $historial[] = [
                'pedido' => $pedido,
                'detalles' => $detalles
            ];
        }

        $usuario = $this->usuarioDAO->obtenerPorEmail($_SESSION['usuario']['email']);
        // Sync session info
        if ($usuario) {
            $_SESSION['usuario'] = [
                'id' => $usuario->getId_usuario(),
                'nombre' => $usuario->getNombre(),
                'email' => $usuario->getEmail(),
                'telefono' => $usuario->getTelefono(),
                'rol' => $usuario->getRol()
            ];
        }

        $view = 'src/view/perfil.php';
        require 'src/view/main.php';
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['usuario']['id'];
            $datos = [
                'nombre' => $_POST['nombre'] ?? '',
                'telefono' => $_POST['telefono'] ?? ''
            ];

            if ($this->usuarioDAO->actualizar($id_usuario, $datos)) {
                $_SESSION['success_perfil'] = "Datos actualizados correctamente.";
                // Refresh session user data
                $usuario = $this->usuarioDAO->obtenerPorEmail($_SESSION['usuario']['email']);
                if ($usuario) {
                    $_SESSION['usuario'] = [
                        'id' => $usuario->getId_usuario(),
                        'nombre' => $usuario->getNombre(),
                        'email' => $usuario->getEmail(),
                        'telefono' => $usuario->getTelefono(),
                        'rol' => $usuario->getRol()
                    ];
                }
            } else {
                $_SESSION['error_perfil'] = "Error al actualizar los datos.";
            }
        }
        header("Location: index.php?controller=perfil&action=index");
    }

    public function cambiarPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_usuario = $_SESSION['usuario']['id'];
            $passActual = $_POST['pass_actual'] ?? '';
            $passNueva = $_POST['pass_nueva'] ?? '';

            // Get current user to verify password
            $usuarioObj = $this->usuarioDAO->obtenerPorEmail($_SESSION['usuario']['email']);
            
            if ($usuarioObj && password_verify($passActual, $usuarioObj->getContraseña())) {
                $nuevaHash = password_hash($passNueva, PASSWORD_DEFAULT);
                if ($this->usuarioDAO->actualizar($id_usuario, ['contraseña' => $nuevaHash])) {
                    $_SESSION['success_perfil'] = "Contraseña actualizada correctamente.";
                } else {
                    $_SESSION['error_perfil'] = "Error al actualizar la contraseña.";
                }
            } else {
                $_SESSION['error_perfil'] = "La contraseña actual es incorrecta.";
            }
        }
        header("Location: index.php?controller=perfil&action=index");
    }
}
