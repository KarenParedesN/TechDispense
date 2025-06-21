<?php

require_once __DIR__ . "/BAD_REQUEST.php";
require_once __DIR__ . "/ProblemDetails.php";

function validaProducto(false|string $producto)
{

 if ($producto === false)
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Falta el producto.",
   type: "/error/faltaproducto.html",
   detail: "La solicitud no tiene el valor de producto."
  );

 $trimProducto = trim($producto);

 if ($trimProducto === "")
  throw new ProblemDetails(
   status: BAD_REQUEST,
   title: "Producto en blanco.",
   type: "/error/productoenblanco.html",
   detail: "Pon texto en el campo producto...",
  );

 return $trimProducto;
}
