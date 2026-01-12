<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect();
    }

    public function obtenerPorEmail($email) {
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT * FROM usuarios WHERE email = '$email' LIMIT 1";
        $result = $this->conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return new Usuario(
                $row['id_usuario'],
                $row['nombre'],
                $row['email'],
                $row['contraseña'],
                $row['telefono'] ?? '',
                $row['rol'] ?? 'user'
            );
        }
        return null;
    }

    public function emailExiste($email) {
        $email = $this->conn->real_escape_string($email);
        $sql = "SELECT id_usuario FROM usuarios WHERE email = '$email' LIMIT 1";
        $result = $this->conn->query($sql);
        return $result && $result->num_rows > 0;
    }

    public function crear(Usuario $usuario) {
        $nombre = $this->conn->real_escape_string($usuario->getNombre());
        $telefono = $this->conn->real_escape_string($usuario->getTelefono() ?? '');
        $email = $this->conn->real_escape_string($usuario->getEmail());
        $contraseña = $usuario->getContraseña();
        $rol = $this->conn->real_escape_string($usuario->getRol() ?? 'user');

        $sql = "INSERT INTO usuarios (nombre, email, telefono, contraseña, rol)
                VALUES ('$nombre', '$email', '$telefono', '$contraseña', '$rol')";
        return $this->conn->query($sql);
    }

    public function actualizar($id, $datos) {
        $id = intval($id);
        $updates = [];
        
        if (isset($datos['nombre'])) {
            $nombre = $this->conn->real_escape_string($datos['nombre']);
            $updates[] = "nombre = '$nombre'";
        }
        if (isset($datos['email'])) {
            $email = $this->conn->real_escape_string($datos['email']);
            $updates[] = "email = '$email'";
        }
        if (isset($datos['rol'])) {
            $rol = $this->conn->real_escape_string($datos['rol']);
            $updates[] = "rol = '$rol'";
        }
        if (isset($datos['telefono'])) {
            $telefono = $this->conn->real_escape_string($datos['telefono']);
            $updates[] = "telefono = '$telefono'";
        }
        if (isset($datos['contraseña'])) {
            $contraseña = $this->conn->real_escape_string($datos['contraseña']);
            $updates[] = "contraseña = '$contraseña'";
        }
        // Add more fields if needed

        if (empty($updates)) return true; // Nothing to update

        $sql = "UPDATE usuarios SET " . implode(', ', $updates) . " WHERE id_usuario = $id";
        return $this->conn->query($sql);
    }

    public function verificarLogin($email, $contraseña) {
        $usuario = $this->obtenerPorEmail($email);
        if ($usuario && password_verify($contraseña, $usuario->getContraseña())) {
            return $usuario;
        }
        return null;
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM usuarios";
        $result = $this->conn->query($sql);
        $usuarios = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = new Usuario(
                    $row['id_usuario'],
                    $row['nombre'],
                    $row['email'],
                    $row['contraseña'],
                    $row['telefono'] ?? '',
                    $row['rol'] ?? 'user'
                );
            }
        }

        return $usuarios;
    }
}
