<?php
// src/controller/CheckoutController.php
require_once __DIR__ . '/../DAO/PedidoDAO.php';

class CheckoutController {
    public function index() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php?controller=login&action=index");
            return;
        }
        $view = 'src/view/checkout.php';
        require 'src/view/main.php';
    }

    private function log($msg) {
        $file = __DIR__ . '/../../public/debug_log.txt';
        $time = date('[Y-m-d H:i:s] ');
        file_put_contents($file, $time . "[Controller] " . $msg . PHP_EOL, FILE_APPEND);
    }

    public function process() {
        header('Content-Type: application/json');
        
        $this->log("Iniciando process()");

        if (!isset($_SESSION['usuario'])) {
            $this->log("Error: Usuario no logueado");
            echo json_encode(['success' => false, 'message' => 'Usuario no logueado']);
            return;
        }

        $rawInput = file_get_contents('php://input');
        $this->log("Payload recibido: " . $rawInput);

        $input = json_decode($rawInput, true);
        
        if (!$input || empty($input['cart'])) {
            $this->log("Error: Datos inválidos o carrito vacío");
            echo json_encode(['success' => false, 'message' => 'El carrito está vacío o los datos son inválidos']);
            return;
        }

        try {
            $pedidoDAO = new PedidoDAO();
            $id_usuario = $_SESSION['usuario']['id'];
            
            $this->log("Llamando a DAO->registrarPedido para usuario $id_usuario");
            
            $id_pedido = $pedidoDAO->registrarPedido(
                $id_usuario,
                $input['address'],
                $input['cart'],
                $input['total']
            );
            
            require_once "src/DAO/LogDAO.php";
            $logDAO = new LogDAO();
            $logDAO->registrar($id_usuario, "Realizó un pedido (ID: $id_pedido) - Total: $" . $input['total']);

            $this->log("Éxito. Pedido ID: $id_pedido");
            echo json_encode(['success' => true, 'id_pedido' => $id_pedido]);
        } catch (Exception $e) {
            $this->log("Excepción: " . $e->getMessage());
            error_log("Error Checkout: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Error al procesar el pedido: ' . $e->getMessage()]);
        }
    }
}
?>
