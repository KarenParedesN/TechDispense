<?php

require_once __DIR__ . "/BAD_REQUEST.php";
require_once __DIR__ . "/ProblemDetails.php";

function validaMarca(false|string $marca)
{

 if ($marca === false)
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Falta el marca.",
   type: "/error/faltamarca.html",
   detail: "La solicitud no tiene el valor de marca."
  );

 $trimMarca= trim($marca);

 if ($trimMarca=== "")
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Marca en blanco.",
   type: "/error/marcaenblanco.html",
   detail: "Pon texto en el campo marca...",
  );

 return $trimMarca;
}
