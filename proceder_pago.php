<?php
session_start();
require '../../config/database.php';
require '../../config/config.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: productosCompra.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$cart = $_SESSION['cart'];
$ids = array_keys($cart);
$total = 0;
$productos = [];

if (count($ids) > 0) {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $con->prepare("SELECT dis_id, dis_nombre, dis_precio, dis_descuento FROM dispensadores WHERE dis_id IN ($placeholders)");
    $stmt->execute($ids);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

foreach ($productos as $producto) {
    $id = $producto['dis_id'];
    $precio = $producto['dis_precio'];
    $descuento = $producto['dis_descuento'];
    $precio_desc = $precio - ($precio * $descuento / 100);
$cantidad = $cart[$id]; 

    $subtotal = $precio_desc * $cantidad;
    $total += $subtotal;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen del Pedido</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://www.paypal.com/sdk/js?client-id=AZe2LD2RUd3zS2yQYwM7O7Izbg9RIXipKYcvSNCEN2kVO2czFTPcVADxK4fisZGuU8sdrPO3GxT6g793&currency=MXN"></script>
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4">Resumen del Pedido</h2>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Descuento</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto):
                    $id = $producto['dis_id'];
                    $nombre = $producto['dis_nombre'];
                    $precio = $producto['dis_precio'];
                    $descuento = $producto['dis_descuento'];
                    $precio_desc = $precio - ($precio * $descuento / 100);
                   $cantidad = $cart[$id];  // si es entero

                    $subtotal = $precio_desc * $cantidad;
                ?>
                <tr>
                    <td><?= htmlspecialchars($nombre); ?></td>
                    <td><?= $cantidad; ?></td>
                    <td>$<?= number_format($precio, 2); ?></td>
                    <td><?= $descuento; ?>%</td>
                    <td>$<?= number_format($subtotal, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Total:</th>
                    <th>$<?= number_format($total, 2); ?></th>
                </tr>
            </tfoot>
        </table>
    </div>

    <div id="paypal-button-container" class="mt-4"></div>
</div>

<script>
paypal.Buttons({
    style: {
        color: 'blue',
        shape: 'pill',
        label: 'pay'
    },
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?= number_format($total, 2, '.', '') ?>'
                }
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            console.log('Pago aprobado:', details);

            fetch("../../Controller/guardar_pedido.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    paypal_order_id: details.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = "../../View/modulos/gracias.php";
                } else {
                    alert("Error al guardar el pedido: " + data.message);
                }
            })
            .catch(error => {
                console.error("Error en la solicitud:", error);
                alert("Ocurrió un error al procesar el pedido.");
            });
        });
    },
    onCancel: function(data) {
        alert("Pago cancelado.");
        console.log("Cancelado:", data);
    }
}).render('#paypal-button-container');
</script>

<!-- Bootstrap JS Bundle (Popper + JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
