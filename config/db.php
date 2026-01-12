<?php
require_once __DIR__ . "/config.php";

class DBConnection {
    public static function connect() {
        $connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

        if ($connection->connect_error) {
            die("Error de conexión: " . $connection->connect_error);
        }

        $connection->set_charset("utf8");
        $connection->query("SET time_zone = '+01:00'"); // Standardize to match provided time
        return $connection;
    }
}
