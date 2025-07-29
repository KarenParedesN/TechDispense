<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaTexto.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/../lib/php/devuelveCreated.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_ENCUESTA.php";
require_once __DIR__ . "/TABLA_DISPENSADOR.php";

ejecutaServicio(function () {

 $calificacion = recuperaTexto("calificacion");
 $comentario = recuperaTexto("comentarios");
 $disId = recuperaIdEntero("disId");

 $pdo = Bd::pdo();
 insert(
  pdo: $pdo,
  into: ENCUESTA,
  values: [ENC_CALIFICACION => $calificacion, ENC_COMENTARIO => $comentario,  ENC_FECHA => date('Y-m-d'), DIS_ID => $disId ]
 );
 $id = $pdo->lastInsertId();

 $update = $pdo->prepare(
        "UPDATE " . DISPENSADOR . "
        SET DIS_CANTIDAD = DIS_CANTIDAD - 1
        WHERE DIS_ID = :disId AND DIS_CANTIDAD > 0" 
    );
    $update->bindValue(":disId", $disId, PDO::PARAM_INT);
    $update->execute();

 $encodeId = urlencode($id);
 devuelveCreated("/srv/encuesta.php?id=$encodeId", [
   "id" => ["value" => $id],
   "calificacion" => ["value" => $calificacion],
   "comentario" => ["value" => $comentario],
   "disId" => ["value" => $disId]
   
 ]);
});
