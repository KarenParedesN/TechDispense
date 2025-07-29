<?php
session_start();

require '../../config/database.php';
require '../../config/config.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $carritoVacio = true;
} else {
    $carritoVacio = false;
    $cart = $_SESSION['cart'];
    $ids = array_keys($cart);
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $sql = $con->prepare("SELECT dis_id, dis_nombre, dis_precio, dis_descuento FROM dispensadores WHERE dis_id IN ($placeholders)");
    $sql->execute($ids);
    $productos = $sql->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Carrito - TechDispense</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-4">
    <?php if ($carritoVacio): ?>
        <div class="alert alert-info text-center mt-5">
            Tu carrito está vacío.<br>
            <a href="../../View/modulos/productosCompra.php" class="btn btn-primary mt-3">Ir a comprar</a>
        </div>
    <?php else: ?>
        <h1 class="mb-4 text-center">Tu carrito</h1>

        <form method="POST" action="../../Controller/actualizar_carrito.php">
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white align-middle">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>Producto</th>
                            <th>Precio unitario</th>
                            <th style="width: 120px;">Cantidad</th>
                            <th>Subtotal</th>
                            <th style="width: 100px;">Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($productos as $producto):
                        $id = $producto['dis_id'];
                        $cantidad = $cart[$id];
                        $precioUnit = $producto['dis_precio'];
                        $descuento = $producto['dis_descuento'];

                        if ($descuento > 0) {
                            $precioUnit -= ($precioUnit * $descuento / 100);
                        }

                        $subtotal = $precioUnit * $cantidad;
                        $total += $subtotal;
                    ?>
                        <tr class="text-center align-middle">
                            <td><?php echo htmlspecialchars($producto['dis_nombre']); ?></td>
                            <td>$<?php echo number_format($precioUnit, 2); ?></td>
                            <td>
                                <input type="number" name="cantidades[<?php echo $id; ?>]" value="<?php echo $cantidad; ?>" min="1" class="form-control text-center" required>
                            </td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                            <td>
                                <button class="btn btn-danger btn-sm" type="submit" name="eliminar" value="<?php echo $id; ?>" onclick="return confirm('¿Eliminar este producto del carrito?');">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr class="text-center fw-bold">
                            <td colspan="3" class="text-end">Total:</td>
                            <td colspan="2">$<?php echo number_format($total, 2); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <button class="btn btn-success btn-lg w-100 mb-3" type="submit" name="actualizar">Actualizar carrito</button>
        </form>

        <a href="../../View/modulos/proceder_pago.php" class="btn btn-primary btn-lg w-100">Proceder a pagar</a>
    <?php endif; ?>
</div>

<!-- Bootstrap Bundle JS (Popper + Bootstrap JS) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Optional: Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</body>
</html>
