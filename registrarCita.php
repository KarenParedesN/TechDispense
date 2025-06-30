<?php
$pdo = new PDO("mysql:host=localhost;dbname=dispensador;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$nombre = trim($_POST['nombre']);
$apellido = trim($_POST['apellido']);
$correo = trim($_POST['correo']);
$telefono = trim($_POST['telefono']);
$direccion = trim($_POST['direccion']);
$fechaHora = $_POST['fecha_hora'];


if (!$nombre || !$apellido || !$correo || !$telefono || !$direccion || !$fechaHora) {
    die("❌ Faltan campos por llenar.");
}


$stmt = $pdo->prepare("INSERT INTO instalaciones(status_cita, telefono, direccion, fecha_hora_Instalacion, USUARIOS_id_usu, VENTA_id_venta, PRODUCTOS_id_produ)
                       VALUES ('Pendiente', ?, ?, ?, 1, NULL, NULL)");
$stmt->execute([$telefono, $direccion, $fechaHora]);

echo "<script>
    alert('✅ Cita registrada con éxito.');
    window.location.href = '../../index.html';
</script>";
?>
