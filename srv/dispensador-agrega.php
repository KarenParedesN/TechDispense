<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/recuperaEntero.php";
require_once __DIR__ . "/../lib/php/validaProducto.php";
require_once __DIR__ . "/../lib/php/validaMarca.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/../lib/php/devuelveCreated.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_DISPENSADOR.php";

ejecutaServicio(function () {

 $producto = recuperaTexto("producto");
 $producto = validaProducto($producto);

 $marca = recuperaTexto("marca");
 $marca = validaMarca($marca);

 $cantidad = recuperaEntero("cantidad");

 $pdo = Bd::pdo();
 insert(pdo: $pdo, into: DISPENSADOR, values: ["DIS_PRODUCTO" => $producto, "DIS_MARCA" => $marca, "DIS_CANTIDAD" => $cantidad]);
 $id = $pdo->lastInsertId();

 $encodeId = urlencode($id);
 devuelveCreated("/srv/dispensador.php?id=$encodeId", [
   "id" => ["value" => $id],
   "producto" => ["value" => $producto],
   "marca" => ["value" => $marca],
   "cantidad" => ["value" => $cantidad],
 ]);
});
