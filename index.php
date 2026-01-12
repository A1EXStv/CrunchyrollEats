<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/config/config.php';
if (defined('DEVELOPER_MODE') && DEVELOPER_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
include_once __DIR__ . '/src/controller/HomeController.php';
include_once __DIR__ . '/src/controller/LoginController.php';
include_once __DIR__ . '/src/controller/RegistroController.php';
include_once __DIR__ . '/src/controller/ApiController.php';
include_once __DIR__ . '/src/controller/CartaController.php';
include_once __DIR__ . '/src/controller/CarritoController.php';
include_once __DIR__ . '/src/controller/ProductoController.php';
include_once __DIR__ . '/src/controller/CheckoutController.php';
include_once __DIR__ . '/src/controller/SerieController.php';
include_once __DIR__ . '/src/controller/PerfilController.php';

if (isset($_GET['controller'])) {
    file_put_contents('routing_log.txt', date('[Y-m-d H:i:s] ') . "Request: " . print_r($_GET, true) . PHP_EOL, FILE_APPEND);
    $nombre_controller = ucfirst(trim($_GET['controller'])) . 'Controller';
    if (class_exists($nombre_controller)) {
        $controller = new $nombre_controller();
        $action = isset($_GET['action']) ? trim($_GET['action']) : 'index';
        
        if (method_exists($controller, $action)) {
            $controller->$action();
        } else {
            http_response_code(404);
            echo "<h1>404 Not Found</h1><p>The action '$action' was not found in '$nombre_controller'.</p>";
        }
    } else {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>Controller '$nombre_controller' not found.</p>";
    }
} else {
    $controller = new HomeController();
    $controller->index();
}