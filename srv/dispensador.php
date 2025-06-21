<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_DISPENSADOR.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");

 $modelo =
  selectFirst(pdo: Bd::pdo(),  from: DISPENSADOR,  where: [DIS_ID => $id]);

 if ($modelo === false) {
  $idHtml = htmlentities($id);
  throw new ProblemDetails(
   status: NOT_FOUND,
   title: "Dispensador no encontrado.",
   type: "/error/dispensadornoencontrado.html",
   detail: "No se encontró ningún dispensador con el id $idHtml.",
  );
 }

 devuelveJson([
   "id" => ["value" => $id],
   "producto" => ["value" => $modelo[DIS_PRODUCTO]],
   "marca" => ["value" => $modelo[DIS_MARCA]],
   "cantidad" => ["value" => $modelo[DIS_CANTIDAD]],
 ]);
});
