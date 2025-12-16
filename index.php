<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/src/controller/homeController.php';
include_once __DIR__ . '/src/controller/loginController.php';
include_once __DIR__ . '/src/controller/registroController.php';
include_once __DIR__ . '/src/controller/apiController.php';

if (isset($_GET['controller'])) {
    $nombre_controller = $_GET['controller']. 'Controller';
    if (class_exists($nombre_controller)) {
        $controller = new $nombre_controller();
        $action = $_GET['action'];
        if (isset($action) && method_exists($controller, $action)) {
            $controller->$action();
        } else {
            header("Location:404.php");
        }
    } else{
        echo "controller no encontrado: ". $nombre_controller;
    }
} else{
    $controller = new homeController();
    $controller->index();
}