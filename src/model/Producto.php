<?php
require_once "config/db.php";

class Producto {
    public function getAll() {
        $db = DBConnection::connect();
        $result = $db->query("SELECT * FROM productos");
        $productos = [];

        if ($result) {
            while($row = $result->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        return $productos;
    }
}