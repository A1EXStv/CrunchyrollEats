<?php
include_once 'DAO/ProductoDAO.php';

class ProductoController {
    public function index() {
        $seriesDAO = new seriesDAO();
        $series = $seriesDAO->obtenerTodos(); 
        include 'views/home.php';
    }
}
?>
