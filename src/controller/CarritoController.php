<?php

class CarritoController {
    
    public function index() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=login&action=index");
            exit;
        }
        $view = 'src/view/carrito.php';
        require 'src/view/main.php';
    }

    public function ver() {
        $this->index();
    }
}
