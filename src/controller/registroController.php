<?php 

require_once "src/DAO/UsuarioDAO.php";
require_once "src/model/Usuario.php";

class RegistroController {

    private $usuarioDAO;

    public function __construct() {
        $this->usuarioDAO = new UsuarioDAO();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index() {
        $view = 'src/view/registro.php';
        require 'src/view/main.php';
    }

    public function registrar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $nombre     = trim($_POST['nombre'] ?? '');
            $telefono   = trim($_POST['telefono'] ?? '');
            $email      = trim($_POST['email'] ?? '');
            $contraseña = $_POST['contraseña'] ?? '';
            $rol        = $_POST['rol'] ?? 'user';

            if (empty($nombre) || empty($telefono) || empty($email) || empty($contraseña)) {
                $error = "Todos los campos son obligatorios.";
                $view = 'src/view/registro.php';
                require 'src/view/main.php';
                exit; // Use exit to stop execution, but don't redirect if we want to show error in place.
                      // Wait, original code set SESSION error and redirected. 
                      // Redirecting is actually cleaner for PRG pattern. 
                      // The user asked for View standardization. 
                      // If I conform to "Controllers define $view and include main", I should render NOT redirect on error?
                      // Usually Form Error = Re-render with error.
                      // Original code: $_SESSION['error'] = ... header(Location...).
                      // Let's stick to consistent pattern: Render with Error variable if possible.
                      // But the View checks `isset($error)`.
                      // So render is better.
            }

            if ($this->usuarioDAO->emailExiste($email)) {
                $error = "El email ya está registrado.";
                $view = 'src/view/registro.php';
                require 'src/view/main.php';
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
                $error = "Error al crear cuenta.";
                $view = 'src/view/registro.php';
                require 'src/view/main.php';
                exit;
            }
        }
    }
}