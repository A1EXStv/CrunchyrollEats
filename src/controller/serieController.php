<?php
include_once 'DAO/ProductoDAO.php';

class ProductoController {
    public function index() {
        $seriesDAO = new seriesDAO();
        $series = $seriesDAO->obtenerTodos(); 
        include 'view/home.php';
    }
}
?>
