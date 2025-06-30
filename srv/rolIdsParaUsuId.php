<?php

require_once __DIR__ . "/../lib/php/fetchAll.php";
require_once __DIR__ . "/Bd.php";

function rolIdsParaUsuId(int $id) {
 $pdo = Bd::pdo();
 return fetchAll(
  $pdo->query(
   "SELECT ROL_ID
     FROM USU_ROL
     WHERE USU_ID = :USU_ID
     ORDER BY ROL_ID"
  ),
  [":USU_ID" => $id],
  PDO::FETCH_COLUMN
 );
}