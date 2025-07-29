<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['usuario_id'], $_SESSION['pedido_id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$pedido_id = $_SESSION['pedido_id'];
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_detalle_id'])) {
    $pedido_detalle_id = intval($_POST['pedido_detalle_id']);

    $sql = "SELECT detalle_id FROM pedido_detalles WHERE detalle_id = ? AND pedido_id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $pedido_detalle_id, $pedido_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $_SESSION['pedido_detalle_id'] = $pedido_detalle_id;
        header("Location: ../../../View/modulos/cita.php.php");
        exit;
    } else {
        echo "Detalle no válido para el pedido seleccionado.";
    }
}

$sql = "SELECT detalle_id, dispensador_id, cantidad FROM pedido_detalles WHERE pedido_id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $pedido_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<form method="POST">
    <label for="pedido_detalle_id">Selecciona el producto de tu pedido:</label>
    <select name="pedido_detalle_id" id="pedido_detalle_id" required>
        <option value="">--Seleccionar--</option>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <option value="<?= $row['detalle_id'] ?>">
                Producto ID: <?= $row['dispensador_id'] ?> - Cantidad: <?= $row['cantidad'] ?>
            </option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Seleccionar</button>
</form>
