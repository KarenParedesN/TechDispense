<?php

class Bd
{
 private static ?PDO $pdo = null;

 static function pdo(): PDO
 {
  if (self::$pdo === null) {

   self::$pdo = new PDO(
    // cadena de conexión
    "sqlite:bytelink.db",
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
  }

  return self::$pdo;
 }
}
