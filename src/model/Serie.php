<?php
require_once "config/db.php";

class Serie {
    public function getAll() {
        $db = DBConnection::connect();
        $result = $db->query("SELECT * FROM series");
        $productos = [];

        if ($result) {
            while($row = $result->fetch_assoc()) {
                $series[] = $row;
            }
        }

        return $series;
    }
}