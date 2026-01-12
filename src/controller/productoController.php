<?php
require_once 'src/DAO/ProductoDAO.php';

class ProductoController {
    public function index() {
        $productoDAO = new ProductoDAO();
        $productos = $productoDAO->obtenerTodos(); 
        $view = 'src/view/home.php';
        require 'src/view/main.php';
    }

    public function ver() {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $productoDAO = new ProductoDAO();
            $producto = $productoDAO->obtener($id);
            if ($producto) {
                $view = 'src/view/producto.php';
                require 'src/view/main.php';
            } else {
                $view = 'src/view/404.php';
                require 'src/view/main.php';
            }
        } else {
            header("Location: index.php");
        }
    }
}
?>
