<?php
session_start();
require '../config/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_usu'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Carrito vacío']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$paypalOrderId = $input['paypal_order_id'] ?? null;

if (!$paypalOrderId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID de orden PayPal faltante']);
    exit;
}

$usuarioId = $_SESSION['id_usu'];
$cart = $_SESSION['cart'];

try {
    $db = new Database();
    $con = $db->conectar();

    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $stmt = $con->prepare("SELECT dis_id, dis_nombre, dis_precio, dis_descuento FROM dispensadores WHERE dis_id IN ($placeholders)");
    $stmt->execute($ids);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    $productoInfo = [];

    foreach ($productos as $producto) {
        $id = $producto['dis_id'];
$cantidad = $cart[$id]; 

        $precio = $producto['dis_precio'];
        $descuento = $producto['dis_descuento'];
        $precioFinal = $precio - ($precio * $descuento / 100);
        $subtotal = $precioFinal * $cantidad;
        $total += $subtotal;

        $productoInfo[] = [
            'id' => $id,
            'cantidad' => $cantidad,
            'precio_unitario' => $precioFinal,
            'subtotal' => $subtotal
        ];
    }

    $sqlPedido = $con->prepare("INSERT INTO pedidos (usuario_id, total, paypal_order_id) VALUES (?, ?, ?)");
    $sqlPedido->execute([$usuarioId, $total, $paypalOrderId]);
    $pedidoId = $con->lastInsertId();

    $sqlDetalle = $con->prepare("INSERT INTO pedido_detalles (pedido_id, dispensador_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");

    foreach ($productoInfo as $prod) {
        $sqlDetalle->execute([
            $pedidoId,
            $prod['id'],
            $prod['cantidad'],
            $prod['precio_unitario'],
            $prod['subtotal']
        ]);

        $sqlStock = $con->prepare("UPDATE dispensadores SET dis_stock = dis_stock - ? WHERE dis_id = ?");
        $sqlStock->execute([$prod['cantidad'], $prod['id']]);
    }

    unset($_SESSION['cart']);

    echo json_encode([
        'success' => true,
        'message' => 'Pedido guardado correctamente',
        'pedido_id' => $pedidoId
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
