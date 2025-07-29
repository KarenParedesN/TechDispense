<?php
session_start();
require '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: formulario_cita.php");
    exit;
}

if (!isset($_SESSION['usuario_id'], $_SESSION['pedido_id'], $_SESSION['pedido_detalle_id'])) {
    die("No hay usuario o pedido asignado en sesión.");
}

$id_usuario = $_SESSION['usuario_id'];
$pedido_id = $_SESSION['pedido_id'];
$pedido_detalle_id = $_SESSION['pedido_detalle_id'];



$telefono = trim($_POST['telefono']);
$direccion = trim($_POST['direccion']);
$fecha_hora_instalacion = trim($_POST['fecha_hora_instalacion']);
$nombre_instalador = trim($_POST['nombre_instalador']);

// Validar datos
if (!preg_match('/^\+?\d{7,15}$/', $telefono)) {
    die("Teléfono inválido.");
}
if (empty($direccion)) {
    die("Dirección es obligatoria.");
}
if (strtotime($fecha_hora_instalacion) === false) {
    die("Fecha y hora inválidas.");
}

// Ajustar formato fecha para MySQL DATETIME
$fecha_hora_instalacion = str_replace('T', ' ', $fecha_hora_instalacion) . ':00';

$db = new Database();
$con = $db->conectar();

$sql = "INSERT INTO CITAS (telefono, direccion, fecha_hora_instalacion, nombre_instalador, USUARIOS_id_usuario, pedido_id, pedido_detalles_detalle_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $con->prepare($sql);
if (!$stmt) {
    die("Error en la preparación de la consulta: " . $con->error);
}

$stmt->bind_param("ssssiii", $telefono, $direccion, $fecha_hora_instalacion, $nombre_instalador, $id_usuario, $pedido_id, $pedido_detalle_id);

if ($stmt->execute()) {
    echo "Cita guardada correctamente.";
} else {
    die("Error al guardar la cita: " . $stmt->error);
}
