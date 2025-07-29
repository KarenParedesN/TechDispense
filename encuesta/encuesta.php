<?php

require_once __DIR__ . "/../lib/php/NOT_FOUND.php";
require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/recuperaIdEntero.php";
require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/ProblemDetails.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_ENCUESTA.php";

ejecutaServicio(function () {

 $id = recuperaIdEntero("id");

 $modelo =
  selectFirst(pdo: Bd::pdo(),  from: ENCUESTA,  where: [ENC_ID => $id]);

 if ($modelo === false) {
  $idHtml = htmlentities($id);
  throw new ProblemDetails(
   status: NOT_FOUND,
   title: "Encuesta no encontrado.",
   type: "/error/dispensadornoencontrado.html",
   detail: "No se encontró ninguna encuesta con el id $idHtml.",
  );
 }

 devuelveJson([
   "id" => ["value" => $id],
   "calificacion" => ["value" => $modelo[ENC_CALIFICACION]],
   "comentario" => ["value" => $modelo[ENC_COMENTARIO]],
   "timestamp" => ["value" => $modelo[ENC_FECHA]]
 ]);
});
