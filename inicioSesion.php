<?php
session_start();
header('Content-Type: application/json');
require '../config/database.php'; 

$db = new Database();
$con = $db->conectar();

$correo = $_POST['correo'] ?? '';
$contrasena = $_POST['contrasena'] ?? '';

if (!$correo || !$contrasena) {
    echo json_encode(['exito' => false, 'mensaje' => 'Por favor completa todos los campos']);
    exit;
}

$sql = $con->prepare("SELECT u.*, r.id_rol 
                      FROM usuarios u
                      INNER JOIN rol r ON u.id_rol = r.id_rol
                      WHERE u.email_usu = ?");
$sql->execute([$correo]);
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($contrasena, $usuario['codigoSecreto_usu'])) {
    $_SESSION['usuario'] = [
        'id' => $usuario['id_usu'],
        'nombre' => $usuario['nombre_usu'],
        'id_rol' => $usuario['id_rol']
    ];

    echo json_encode([
        'exito' => true,
        'id_rol' => $usuario['id_rol']
    ]);
} else {
    echo json_encode(['exito' => false, 'mensaje' => 'Correo o contraseña incorrectos. Revisa y vuelve a intentarlo.']);
}
