<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

   
    if (isset($_POST['eliminar'])) {
        $producto_id = intval($_POST['eliminar']);
        if (isset($_SESSION['cart'][$producto_id])) {
            unset($_SESSION['cart'][$producto_id]);
        }
    }

    if (isset($_POST['actualizar']) && isset($_POST['cantidades']) && is_array($_POST['cantidades'])) {
        foreach ($_POST['cantidades'] as $id => $cantidad) {
            $id = intval($id);
            $cantidad = intval($cantidad);
            if ($cantidad > 0) {
                $_SESSION['cart'][$id] = $cantidad;
            } else {
           
                unset($_SESSION['cart'][$id]);
            }
        }
    }
}


header("Location: ../View/modulos/compras.php");
exit;
