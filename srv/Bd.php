<?php

require_once __DIR__ . "/../lib/php/selectFirst.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/../lib/php/insertBridges.php";
require_once __DIR__ . "/../lib/php/insert.php";
require_once __DIR__ . "/TABLA_USUARIO.php";
require_once __DIR__ . "/TABLA_ROL.php";
require_once __DIR__ . "/TABLA_USU_ROL.php";
require_once __DIR__ . "/ROL_ID_CLIENTE.php";
require_once __DIR__ . "/ROL_ID_ADMINISTRADOR.php";

class Bd
{

 private static ?PDO $pdo = null;

 static function pdo(): PDO
 {
  if (self::$pdo === null) {

   self::$pdo = new PDO(
    // cadena de conexión
    "sqlite:techdispense.db",
    // usuario
    null,
    // contraseña
    null,
    // Opciones: pdos no persistentes y lanza excepciones.
    [PDO::ATTR_PERSISTENT => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
   );

   self::$pdo->exec(
    "CREATE TABLE IF NOT EXISTS DISPENSADOR (
      DIS_ID INTEGER,
      DIS_PRODUCTO TEXT NOT NULL,
      DIS_MARCA TEXT NOT NULL,
      DIS_CANTIDAD INTEGER NOT NULL,
      CONSTRAINT DIS_PK
        PRIMARY KEY(DIS_ID),
      CONSTRAINT DIS_UNQ_PROD
        UNIQUE(DIS_PRODUCTO),
      CONSTRAINT DIS_CK_PROD
        CHECK(LENGTH(DIS_PRODUCTO) > 0),
      CONSTRAINT DIS_CK_MARCA
        CHECK(LENGTH(DIS_MARCA) > 0),
      CONSTRAINT DIS_CK_CANT
        CHECK(DIS_CANTIDAD >= 0)
    )"
   );
   self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS USUARIO (
      USU_ID INTEGER,
      USU_CUE TEXT NOT NULL,
      USU_MATCH TEXT NOT NULL,
      USU_NOM TEXT NOT NULL,
      USU_PA TEXT NOT NULL,
      USU_MA TEXT NOT NULL,
      CONSTRAINT USU_PK
       PRIMARY KEY(USU_ID),
      CONSTRAINT USU_CUE_UNQ
       UNIQUE(USU_CUE),
      CONSTRAINT USU_CUE_NV
       CHECK(LENGTH(USU_CUE) > 0)
      CONSTRAINT USU_NOM_NV
       CHECK(LENGTH(USU_NOM) > 0)
      CONSTRAINT USU_PA_NV
       CHECK(LENGTH(USU_PA) > 0)
      CONSTRAINT USU_MA_NV
       CHECK(LENGTH(USU_MA) > 0)
     )'
   );
   self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS ROL (
      ROL_ID TEXT NOT NULL,
      ROL_DESCRIPCION TEXT NOT NULL,
      CONSTRAINT ROL_PK
       PRIMARY KEY(ROL_ID),
      CONSTRAINT ROL_ID_NV
       CHECK(LENGTH(ROL_ID) > 0),
      CONSTRAINT ROL_DESCR_UNQ
       UNIQUE(ROL_DESCRIPCION),
      CONSTRAINT ROL_DESCR_NV
       CHECK(LENGTH(ROL_DESCRIPCION) > 0)
     )'
   );
   self::$pdo->exec(
    'CREATE TABLE IF NOT EXISTS USU_ROL (
       USU_ID INTEGER NOT NULL,
       ROL_ID TEXT NOT NULL,
       CONSTRAINT USU_ROL_PK
        PRIMARY KEY(USU_ID, ROL_ID),
       CONSTRAINT USU_ROL_USU_FK
        FOREIGN KEY (USU_ID) REFERENCES USUARIO(USU_ID),
       CONSTRAINT USU_ROL_ROL_FK
        FOREIGN KEY (ROL_ID) REFERENCES ROL(ROL_ID)
      )'
   );

   if (selectFirst(
    pdo: self::$pdo,
    from: ROL,
    where: [ROL_ID => ROL_ID_ADMINISTRADOR]
   ) === false) {
    insert(
     pdo: self::$pdo,
     into: ROL,
     values: [
      ROL_ID => ROL_ID_ADMINISTRADOR,
      ROL_DESCRIPCION => "Administra el sistema."
     ]
    );
   }

   if (selectFirst(self::$pdo, ROL, [ROL_ID => ROL_ID_CLIENTE]) === false) {
    insert(
     pdo: self::$pdo,
     into: ROL,
     values: [
      ROL_ID => ROL_ID_CLIENTE,
      ROL_DESCRIPCION => "Puede revisar los dispensadores"
     ]
    );
   }
  }

  if (selectFirst(self::$pdo, USUARIO, [USU_CUE => "Karen"]) === false) {
   insert(
    pdo: self::$pdo,
    into: USUARIO,
    values: [
     USU_CUE => "Karen",
     USU_MATCH => password_hash("karen12", PASSWORD_DEFAULT),
     USU_NOM => "Karen de Jesus",
     USU_PA => "Paredes",
     USU_MA => "Nieto"
    ]
   );
   $usuId = self::$pdo->lastInsertId();
   insertBridges(
    pdo: self::$pdo,
    into: USU_ROL,
    valuesDePadre: [USU_ID => $usuId],
    valueDeHijos: [ROL_ID => [ROL_ID_CLIENTE]]
   );
  }

  if (selectFirst(self::$pdo, USUARIO, [USU_CUE => "Esther"]) === false) {
   insert(
    pdo: self::$pdo,
    into: USUARIO,
    values: [
     USU_CUE => "Esther",
     USU_MATCH => password_hash("Esther", PASSWORD_DEFAULT),
     USU_NOM => "Esther",
     USU_PA => "Alejo",
     USU_MA => "Cedillo"
    ]
   );
   $usuId = self::$pdo->lastInsertId();
   insertBridges(
    pdo: self::$pdo,
    into: USU_ROL,
    valuesDePadre: [USU_ID => $usuId],
    valueDeHijos: [ROL_ID => [ROL_ID_ADMINISTRADOR]]
   );
  }

  if (selectFirst(self::$pdo, USUARIO, [USU_CUE => "Leo"]) === false) {
   insert(
    pdo: self::$pdo,
    into: USUARIO,
    values: [
     USU_CUE => "Leo",
     USU_MATCH => password_hash("saurio", PASSWORD_DEFAULT),
     USU_NOM => "Leonardo",
     USU_PA => "Elias",
     USU_MA => "Lopez"
    ]
   );
   $usuId = self::$pdo->lastInsertId();
   insertBridges(
    pdo: self::$pdo,
    into: USU_ROL,
    valuesDePadre: [USU_ID => $usuId],
    valueDeHijos: [ROL_ID => [ROL_ID_ADMINISTRADOR, ROL_ID_CLIENTE]]
   );
  }

  return self::$pdo;
 }
}
