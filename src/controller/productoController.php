<?php
include_once 'DAO/ProductoDAO.php';

class ProductoController {
    public function index() {
        $productoDAO = new ProductoDAO();
        $productos = $productoDAO->obtenerTodos(); 
        include 'view/home.php';
    }
}
?>
