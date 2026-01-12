require_once __DIR__ . "/BaseModel.php";

class Usuario extends BaseModel {
    private $id_usuario;
    private $nombre;
    private $email;
    private $contraseña;
    private $telefono;
    private $rol;

    public function __construct($id_usuario = null, $nombre = null, $email = null, $contraseña = null, $telefono=null, $rol = 'user') {
        $this->id_usuario = $id_usuario;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->contraseña = $contraseña;
        $this->telefono = $telefono;
        $this->rol = $rol;
    }

    public function getId_usuario() { return $this->id_usuario; }
    public function setId_usuario($id_usuario) { $this->id_usuario = $id_usuario; }

    public function getNombre() { return $this->nombre; }
    public function setNombre($nombre) { $this->nombre = $nombre; }

    public function getEmail() { return $this->email; }
    public function setEmail($email) { $this->email = $email; }

    public function getContraseña() { return $this->contraseña; }
    public function setContraseña($contraseña) { $this->contraseña = $contraseña; }

    public function getRol() { return $this->rol; }
    public function setRol($rol) { $this->rol = $rol; }

    public function getTelefono() { return $this->telefono; }
    public function setTelefono($telefono) { $this->telefono = $telefono; }
}

