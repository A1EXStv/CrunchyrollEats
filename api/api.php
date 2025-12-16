<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../config/db.php";

$database = DBConnection::connect();
$method = $_SERVER['REQUEST_METHOD'];

// Helper function to send error response
function sendError($message, $code = 400) {
    http_response_code($code);
    echo json_encode(["message" => $message, "status" => "error"]);
    exit();
}

function sendSuccess($data, $message = "Success", $code = 200) {
    http_response_code($code);
    echo json_encode(["status" => "success", "message" => $message, "data" => $data]);
    exit();
}

// Get input data
$input = json_decode(file_get_contents("php://input"), true);

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get single product
            $id = intval($_GET['id']);
            $stmt = $database->prepare("SELECT * FROM productos WHERE id_producto = ?");
            if (!$stmt) sendError("Error preparing statement: " . $database->error, 500);
            
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $product = $result->fetch_assoc();
                sendSuccess($product);
            } else {
                sendError("Producto no encontrado", 404);
            }
            $stmt->close();
        } else {
            // Get all products
            $sql = "SELECT * FROM productos";
            $result = $database->query($sql);
            
            if ($result) {
                $products = [];
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
                sendSuccess($products);
            } else {
                sendError("Error retrieving products: " . $database->error, 500);
            }
        }
        break;

    case 'POST':
        // Create product
        // Support both JSON input and form-data (for basic usage)
        $data = $input ? $input : $_POST;

        if (empty($data['nombre']) || empty($data['precio'])) {
            sendError("Datos incompletos. Se requiere nombre y precio.");
        }

        $nombre = $database->real_escape_string($data['nombre']);
        $precio = floatval($data['precio']);
        $descripcion = isset($data['descripcion']) ? $database->real_escape_string($data['descripcion']) : "";
        
        // Simple insert without image handling for simplicity as requested, or defaults
        $sql = "INSERT INTO productos (nombre, precio, descripcion, activo) VALUES (?, ?, ?, 1)";
        
        $stmt = $database->prepare($sql);
        if (!$stmt) sendError("Error preparing statement: " . $database->error, 500);

        $stmt->bind_param("sds", $nombre, $precio, $descripcion);
        
        if ($stmt->execute()) {
            $newId = $stmt->insert_id;
            sendSuccess(["id_producto" => $newId, "nombre" => $nombre, "precio" => $precio], "Producto creado correctamente", 201);
        } else {
            sendError("No se pudo crear el producto: " . $stmt->error, 503);
        }
        $stmt->close();
        break;

    case 'PUT':
        // Update product
        if (!$input) {
            sendError("Datos inválidos. Se requiere JSON.");
        }
        
        if (empty($input['id_producto'])) {
             // Try to get ID from query param if not in body
             if (isset($_GET['id'])) {
                 $id = intval($_GET['id']);
             } else {
                 sendError("Falta el ID del producto.");
             }
        } else {
            $id = intval($input['id_producto']);
        }
        
        // We need updates. Let's construct the query dynamically or just update main fields?
        // For simplicity let's require at least one field update.
        
        $updates = [];
        $types = "";
        $params = [];

        if (isset($input['nombre'])) {
            $updates[] = "nombre = ?";
            $types .= "s";
            $params[] = $input['nombre'];
        }
        if (isset($input['precio'])) {
            $updates[] = "precio = ?";
            $types .= "d";
            $params[] = floatval($input['precio']);
        }
        if (isset($input['descripcion'])) {
            $updates[] = "descripcion = ?";
            $types .= "s";
            $params[] = $input['descripcion'];
        }
         if (isset($input['activo'])) {
            $updates[] = "activo = ?";
            $types .= "i";
            $params[] = intval($input['activo']);
        }

        if (count($updates) === 0) {
            sendError("Nada que actualizar.");
        }

        $sql = "UPDATE productos SET " . implode(", ", $updates) . " WHERE id_producto = ?";
        $types .= "i";
        $params[] = $id;

        $stmt = $database->prepare($sql);
        if (!$stmt) sendError("Error preparing statement: " . $database->error, 500);
        
        $stmt->bind_param($types, ...$params);

        if ($stmt->execute()) {
             if ($stmt->affected_rows > 0) {
                sendSuccess(null, "Producto actualizado correctamente");
             } else {
                // Could act success even if no rows changed (same values), or warn.
                sendSuccess(null, "Producto actualizado (sin cambios detectados o id no encontrado)");
             }
        } else {
            sendError("No se pudo actualizar: " . $stmt->error, 503);
        }
        $stmt->close();
        break;

    case 'DELETE':
        // Delete product
        $id = 0;
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
        } elseif (isset($input['id_producto'])) {
            $id = intval($input['id_producto']);
        }

        if ($id <= 0) {
            sendError("ID de producto inválido.");
        }

        $stmt = $database->prepare("DELETE FROM productos WHERE id_producto = ?");
         if (!$stmt) sendError("Error preparing statement: " . $database->error, 500);
        
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                sendSuccess(null, "Producto eliminado.");
            } else {
                sendError("Producto no encontrado.", 404);
            }
        } else {
            sendError("No se pudo eliminar: " . $stmt->error, 503);
        }
        $stmt->close();
        break;

    default:
        sendError("Método no permitido", 405);
        break;
}

$database->close();
?>