<?php
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $producto_id = $_POST['producto_id'] ?? '';
    $usuario = $_POST['usuario'] ?? '';
    $comentario = $_POST['comentario'] ?? '';
    $puntuacion = $_POST['puntuacion'] ?? '';

    if ($producto_id && $usuario && $comentario && $puntuacion) {
        try {
            $db = new Database();
            $con = $db->conectar();

            $sql = $con->prepare("INSERT INTO comentarios (producto_id, usuario, comentario, puntuacion, fecha) 
                                  VALUES (?, ?, ?, ?, NOW())");
            $resultado = $sql->execute([$producto_id, $usuario, $comentario, $puntuacion]);

            if ($resultado) {
                header("Location: ../View/modulos/details.php?id=$producto_id&token=" . hash_hmac('sha1', $producto_id, 'TecNI.ecc-3534*') );
                exit;
            } else {
                header("Location: ../View/modulos/details.php?id=$producto_id&token=" . hash_hmac('sha1', $producto_id, 'TecNI.ecc-3534*') );
                exit;
            }
        } catch (PDOException $e) {
            echo "Error en la base de datos: " . $e->getMessage();
        }
    } else {
        echo "Todos los campos son obligatorios.";
    }
}
?>
