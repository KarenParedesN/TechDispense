<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_DISPENSADOR.php";

ejecutaServicio(function () {

 $lista = select(pdo: Bd::pdo(),  from: DISPENSADOR,  orderBy: DIS_PRODUCTO);

 $render = "";
 foreach ($lista as $modelo) {
  $encodeId = urlencode($modelo[DIS_ID]);
  $id = htmlentities($encodeId);
  $producto = htmlentities($modelo[DIS_PRODUCTO]);
  $marca = htmlentities($modelo[DIS_MARCA]);
  $cantidad = htmlentities($modelo[DIS_CANTIDAD]);
  $render .=
   "<dl>
      <dt><a href='modifica.html?id=$id'>$producto</a></dt>
      <dd>Marca: $marca</dd>
      <dd>Cantidad: $cantidad</dd>
    </dl>
";
 }

 devuelveJson(["lista" => ["innerHTML" => $render]]);
});
