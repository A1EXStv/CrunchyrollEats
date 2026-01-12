<?php
require_once __DIR__ . "/BaseModel.php";

class Producto extends BaseModel {
    private $id_producto;
    private $nombre;
    private $descripcion;
    private $precio;
    private $id_categoria;
    private $id_serie;
    private $imagen;
    
    // Additional properties for discounts (optional, but good for display)
    private $precio_final;
    private $descuento_valor;
    private $descuento_tipo;

    public function __construct($id_producto = null, $nombre = null, $descripcion = null, $precio = null, $id_categoria = null, $id_serie = null, $imagen = null) {
        $this->id_producto = $id_producto;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->id_categoria = $id_categoria;
        $this->id_serie = $id_serie;
        $this->imagen = $imagen;
    }

    // Getters and Setters
    public function getId_producto() { return $this->id_producto; }
    public function setId_producto($id) { $this->id_producto = $id; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }

    public function getPrecio() { return $this->precio; }
    public function setPrecio($precio) { $this->precio = $precio; }

    public function getId_categoria() { return $this->id_categoria; }
    public function setId_categoria($id) { $this->id_categoria = $id; }

    public function getId_serie() { return $this->id_serie; }
    public function setId_serie($id) { $this->id_serie = $id; }

    public function getImagen() { return $this->imagen; }
    public function setImagen($imagen) { $this->imagen = $imagen; }

    // Discount helpers
    public function getPrecio_final() { return $this->precio_final ?? $this->precio; }
    public function setPrecio_final($val) { $this->precio_final = $val; }

    public function getDescuento_valor() { return $this->descuento_valor; }
    public function setDescuento_valor($val) { $this->descuento_valor = $val; }

    public function getDescuento_type() { return $this->descuento_tipo; }
    public function setDescuento_type($val) { $this->descuento_tipo = $val; }
}