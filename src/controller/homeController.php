<?php
require_once "src/models/Producto.php";
require_once "src/models/Serie.php";
require_once "src/controllers/loginController.php";

class homeController {
    public function index() {
        $view = 'views/home.php';
        $productoModel = new Producto();
        $productos = $productoModel->getAll();

        $serieModel = new Serie();
        $series = $serieModel->getAll();

        require "src/views/home.php";
    }
}
