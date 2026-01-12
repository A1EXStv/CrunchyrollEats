require_once __DIR__ . "/BaseModel.php";

class Pedido extends BaseModel {
    private $id_pedido;
    private $id_usuario;
    private $fecha_pedido; // Changed to match DB column 'fecha_pedido'
    private $estado;
    private $total;
    
    // Extra fields often joined
    private $usuario_nombre;
    private $usuario_email;

    public function __construct($id_pedido = null, $id_usuario = null, $fecha_pedido = null, $estado = null, $total = null) {
        $this->id_pedido = $id_pedido;
        $this->id_usuario = $id_usuario;
        $this->fecha_pedido = $fecha_pedido;
        $this->estado = $estado;
        $this->total = $total;
    }

    public function getId_pedido() { return $this->id_pedido; }
    public function setId_pedido($id) { $this->id_pedido = $id; }

    public function getId_usuario() { return $this->id_usuario; }
    public function setId_usuario($id) { $this->id_usuario = $id; }

    public function getFecha_pedido() { return $this->fecha_pedido; }
    public function setFecha_pedido($date) { $this->fecha_pedido = $date; }

    public function getEstado() { return $this->estado; }
    public function setEstado($est) { $this->estado = $est; }

    public function getTotal() { return $this->total; }
    public function setTotal($total) { $this->total = $total; }

    // Hydration helpers
    public function getUsuario_nombre() { return $this->usuario_nombre; }
    public function setUsuario_nombre($val) { $this->usuario_nombre = $val; }

    public function getUsuario_email() { return $this->usuario_email; }
    public function setUsuario_email($val) { $this->usuario_email = $val; }
}
