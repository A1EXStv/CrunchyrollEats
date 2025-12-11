<?php
require_once "src/model/Producto.php";
require_once "src/model/Serie.php";
require_once "src/controller/loginController.php";

class homeController {
    public function index() {
        $view = 'home.php';
        $productoModel = new Producto();
        $productos = $productoModel->getAll();

        $serieModel = new Serie();
        $series = $serieModel->getAll();

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
