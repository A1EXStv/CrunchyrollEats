<?php
include_once 'db.php';

class serieDAO {
    private $conn;

    public function __construct() {
        $this->conn = DBConnection::connect(); // Usas tu clase DBConnection
    }

    public function obtenerTodos() {
        $sql = "SELECT * FROM series";
        $result = $this->conn->query($sql);
        $series = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $series[] = $row;
            }
        }
        return $series;
    }
}
?>
