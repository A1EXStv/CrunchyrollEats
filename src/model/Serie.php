<?php
require_once __DIR__ . "/BaseModel.php";

class Serie extends BaseModel {
    private $id_serie;
    private $nombre;
    private $descripcion; // Assuming generic structure
    private $imagen;

    public function __construct($id_serie = null, $nombre = null, $descripcion = null, $imagen = null) {
        $this->id_serie = $id_serie;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->imagen = $imagen;
    }

    public function getId_serie() { return $this->id_serie; }
    public function setId_serie($id) { $this->id_serie = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($desc) { $this->descripcion = $desc; }

    public function getImagen() { return $this->imagen; }
    public function setImagen($img) { $this->imagen = $img; }
}