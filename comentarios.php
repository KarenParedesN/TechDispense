<?php
$host = 'localhost';
$usuario = 'u470346911_root';
$contrasena = ']9pk&JBgJ';
$basedatos = 'u470346911_tecnodispense';

$mysqli = new mysqli($host, $usuario, $contrasena, $basedatos);
if ($mysqli->connect_error) {
    die("Error de conexión: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");


$sql = "
    SELECT c.*, p.dis_nombre
    FROM comentarios_disp c
    LEFT JOIN dispensadores p ON c.producto_id = p.dis_id
    WHERE c.puntuacion = 5
    ORDER BY c.fecha DESC
";

$result = $mysqli->query($sql);
if (!$result) {
    die("Error en la consulta SQL: " . $mysqli->error);
}?>
