<?php
require_once 'src/DAO/SerieDAO.php';

class SerieController {
    public function index() {
        $seriesDAO = new SerieDAO();
        $series = $seriesDAO->obtenerTodos(); 
        require 'src/view/home.php';
    }
}
?>
