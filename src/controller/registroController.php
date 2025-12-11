<?php 

require_once "src/DAO/usuarioDAO.php";
require_once "src/model/Usuario.php";

class registroController {

    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        require 'src/view/registro.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre     = trim($_POST['nombre'] ?? '');
            $telefono   = trim($_POST['telefono'] ?? '');
            $email      = trim($_POST['email'] ?? '');
            $contraseña = $_POST['contraseña'] ?? '';
            $rol        = $_POST['rol'] ?? 'user';

            if (empty($nombre) || empty($telefono) || empty($email) || empty($contraseña)) {
                $_SESSION['error'] = "Todos los campos son obligatorios.";
                header("Location: index.php?controller=registro&action=index");
                exit;
            }

            if ($this->usuarioDAO->emailExiste($email)) {
                $_SESSION['error'] = "El email ya está registrado.";
                header("Location: index.php?controller=registro&action=index");
                exit;
            }
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);

            $usuario = new Usuario(null, $nombre, $email, $hash, $telefono, $rol);

            $registrado = $this->usuarioDAO->crear($usuario);

            if ($registrado) {
                $_SESSION['success'] = "Cuenta creada correctamente. Ya puedes iniciar sesión.";
                header("Location: index.php?controller=login&action=index");
                exit;
            } else {
                $_SESSION['error'] = "Error al crear cuenta.";
                header("Location: index.php?controller=registro&action=index");
                exit;
            }
        }
    }
}