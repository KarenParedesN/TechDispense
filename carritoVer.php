<?php
require '../../config/database.php';
require '../../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = new Database();
$con = $db->conectar();

$carrito = $_SESSION['cart'] ?? [];

?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Tu carrito - TechDispense</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-5">
  <h2>Carrito de compras</h2>

  <?php if (empty($carrito)): ?>
    <p>Tu carrito está vacío.</p>
  <?php else: ?>
    <table class="table table-bordered table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>Producto</th>
          <th>Precio unitario</th>
          <th>Cantidad</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $total = 0;
        foreach ($carrito as $id_produ => $cantidad):
          // Consulta datos del producto
          $sql = $con->prepare("SELECT nombre_produ, precio_produ, descuento_produ FROM productos WHERE id_produ = ?");
          $sql->execute([$id_produ]);
          $producto = $sql->fetch(PDO::FETCH_ASSOC);
          if (!$producto) continue;

          $precioFinal = $producto['precio_produ'] - ($producto['precio_produ'] * $producto['descuento_produ'] / 100);
          $subtotal = $precioFinal * $cantidad;
          $total += $subtotal;
        ?>
        <tr>
          <td><?php echo htmlspecialchars($producto['nombre_produ']); ?></td>
          <td>$<?php echo number_format($precioFinal, 2); ?></td>
          <td><?php echo $cantidad; ?></td>
          <td>$<?php echo number_format($subtotal, 2); ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3" class="text-end">Total:</th>
          <th>$<?php echo number_format($total, 2); ?></th>
        </tr>
      </tfoot>
    </table>
  <?php endif; ?>

  <a href="../../index.html" class="btn btn-primary">Seguir comprando</a>
</div>
</body>
</html>
