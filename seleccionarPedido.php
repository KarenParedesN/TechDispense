<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$id_usuario = $_SESSION['usuario_id'];
$db = new Database();
$con = $db->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $pedido_id = intval($_POST['pedido_id']);

    $sql = "SELECT pedido_id FROM PEDIDOS WHERE pedido_id = ? AND USUARIOS_id_usuario = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ii", $pedido_id, $id_usuario);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $_SESSION['pedido_id'] = $pedido_id;
        header("Location: seleccionar_detalle.php");
        exit;
    } else {
        echo "Pedido no válido para este usuario.";
    }
}

$sql = "SELECT pedido_id FROM PEDIDOS WHERE USUARIOS_id_usuario = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<form method="POST">
    <label for="pedido_id">Selecciona tu pedido:</label>
    <select name="pedido_id" id="pedido_id" required>
        <option value="">--Seleccionar--</option>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <option value="<?= $row['pedido_id'] ?>"><?= $row['pedido_id'] ?></option>
        <?php endwhile; ?>
    </select>
    <button type="submit">Seleccionar</button>
</form>
