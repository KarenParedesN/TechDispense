<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/recuperaEntero.php";
require_once __DIR__ . "/../lib/php/validaProducto.php";
require_once __DIR__ . "/../lib/php/validaMarca.php";
require_once __DIR__ . "/../lib/php/update.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_DISPENSADOR.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");
 $producto = recuperaTexto("producto");
 $producto = validaProducto($producto);

 $marca = recuperaTexto("marca");
 $marca = validaMarca($marca);

 $cantidad = recuperaEntero("cantidad");
 update(
  pdo: Bd::pdo(),
  table: DISPENSADOR,
  set: [DIS_PRODUCTO => $producto, DIS_MARCA => $marca, DIS_CANTIDAD => $cantidad ],
  where: [DIS_ID => $id]
 );

 devuelveJson([
  "id" => ["value" => $id],
   "producto" => ["value" => $producto],
   "marca" => ["value" => $marca],
   "cantidad" => ["value" => $cantidad],
 ]);
});
