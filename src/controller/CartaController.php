<?php

require_once __DIR__ . '/../DAO/ProductoDAO.php';
require_once __DIR__ . '/../DAO/SerieDAO.php';

class CartaController
{
    private $productoDAO;
    private $serieDAO;

    public function __construct()
    {
        $this->productoDAO = new ProductoDAO();
        $this->serieDAO = new SerieDAO();
    }

    public function index()
    {
        $series = $this->serieDAO->obtenerTodos();
        $productos = $this->filtrarProductos($_GET);

        $view = 'src/view/carta.php';
        require 'src/view/main.php';
    }

    private function filtrarProductos($params)
    {
        $id_serie = $params['serie'] ?? [];
        $sort = $params['sort'] ?? null;

        if (!empty($id_serie)) {
            $id_serie = array_map('intval', $id_serie);
            return $this->productoDAO->obtenerPorSeriesArray($id_serie, $sort);
        }

        return $this->productoDAO->obtenerTodos($sort);
    }
}