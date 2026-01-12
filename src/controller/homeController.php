<?php
require_once "src/DAO/ProductoDAO.php";
require_once "src/DAO/SerieDAO.php";
require_once "src/controller/LoginController.php";

class HomeController {
    public function index() {
        $view = 'src/view/home.php';
        
        $productoDAO = new ProductoDAO();
        $productos = $productoDAO->obtenerTodos();

        $serieDAO = new SerieDAO();
        $series = $serieDAO->obtenerTodos();

        require "src/view/main.php";
    }

    public function logout() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION = [];
    session_destroy();
    header("Location: index.php?controller=login&action=index");
    exit;
}
}
