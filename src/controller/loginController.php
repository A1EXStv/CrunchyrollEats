<?php
require_once "src/DAO/UsuarioDAO.php";
require_once "src/model/Usuario.php";

class LoginController {

    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $view = 'src/view/login.php';
        require 'src/view/main.php';
    }

    public function auth() {
        if (isset($_POST['email'], $_POST['contraseña'])) {
            $email = $_POST['email'];
            $contraseña = $_POST['contraseña'];

            $usuario = $this->usuarioDAO->verificarLogin($email, $contraseña);

            if ($usuario) {
                $_SESSION['usuario'] = [
                    'id' => $usuario->getId_usuario(),
                    'nombre' => $usuario->getNombre(),
                    'email' => $usuario->getEmail(),
                    'rol' => $usuario->getRol()
                ];

                require_once "src/DAO/LogDAO.php";
                $logDAO = new LogDAO();
                $logDAO->registrar($usuario->getId_usuario(), "Inicio de sesión exitoso");

                header("Location: index.php?controller=home&action=index");
                exit;
            } else {
                $error = "Email o contraseña incorrectos";
                $view = 'src/view/login.php';
                require 'src/view/main.php';
            }
        } else {
            $error = "Debe completar todos los campos";
            $view = 'src/view/login.php';
            require 'src/view/main.php';
        }
    }

    public function store() {
        if (isset($_POST['nombre'], $_POST['email'], $_POST['contraseña'])) {
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT);

            $usuario = new Usuario(null, $nombre, $email, $contraseña);

            if ($this->usuarioDAO->crear($usuario)) { // <- corregido
                header("Location: index.php?controller=login&action=index");
                exit;
            } else {
                $error = "Error al registrar usuario";
                require 'src/view/registro.php';
            }
        } else {
            $error = "Debe completar todos los campos";
            require 'src/view/registro.php';
        }
    }

    public function logout() {
        if (isset($_SESSION['usuario'])) {
            require_once "src/DAO/LogDAO.php";
            $logDAO = new LogDAO();
            $logDAO->registrar($_SESSION['usuario']['id'], "Cierre de sesión");
        }
        session_destroy();
        header("Location: index.php?controller=login&action=index");
        exit;
    }
}
