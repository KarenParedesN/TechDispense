<?php

require_once __DIR__ . "/../lib/php/ejecutaServicio.php";
require_once __DIR__ . "/../lib/php/select.php";
require_once __DIR__ . "/../lib/php/devuelveJson.php";
require_once __DIR__ . "/Bd.php";
require_once __DIR__ . "/TABLA_ENCUESTA.php";
require_once __DIR__ . "/TABLA_DISPENSADOR.php";
require_once __DIR__ . "/ROL_ID_CLIENTE.php";
require_once __DIR__ . "/ROL_ID_ADMINISTRADOR.php"; 
require_once __DIR__ . "/ROL_IDS.php"; 
require_once __DIR__ . "/protege.php";

ejecutaServicio(function () {

    $sesion = protege([ROL_ID_CLIENTE, ROL_ID_ADMINISTRADOR]);
    $idEmpresaCliente = $sesion->empId;
    
    $userRoles = property_exists($sesion, ROL_IDS) ? $sesion->{ROL_IDS} : [];

    $sql = "
        SELECT
            E.ENC_ID,
            E.ENC_CALIFICACION,
            E.ENC_COMENTARIO,
            E.ENC_FECHA,
            D.DIS_ID,
            D.DIS_PRODUCTO
        FROM ENCUESTA E
        LEFT JOIN DISPENSADOR D
        ON E.DIS_ID = D.DIS_ID
    ";

    $showAllEncuestas = false;
    
    if (is_array($userRoles) && in_array(ROL_ID_ADMINISTRADOR, $userRoles)) {
        $showAllEncuestas = true;
    }

    if (!$showAllEncuestas && is_array($userRoles) && in_array(ROL_ID_CLIENTE, $userRoles)) {
        $idEmpresaClienteFiltrado = Bd::pdo()->quote($idEmpresaCliente, PDO::PARAM_INT);
        $sql .= " WHERE D.EMP_ID = " . $idEmpresaClienteFiltrado;
    }

    $sql .= " ORDER BY E.ENC_FECHA DESC";

    $lista = fetchAll(Bd::pdo()->query($sql));

    $render = "
        <table>
            <thead>
                <tr>
                    <th>Calificación</th>
                    <th>Comentario</th>
                    <th>Fecha</th>
                    <th>N° de Serie</th>
                    <th>Producto Dispensado</th>
                </tr>
            </thead>
            <tbody>
    ";

    if (empty($lista)) {
        $render .= "
            <tr>
                <td colspan='5' style='text-align: center;'>No hay encuestas registradas para tu empresa.</td>
            </tr>
        ";
    } else {
        foreach ($lista as $modelo) {
        $encId = htmlentities(urlencode($modelo[ENC_ID]));
        $encCalificacion = htmlentities($modelo[ENC_CALIFICACION]);
        $encComentario = htmlentities($modelo[ENC_COMENTARIO]);
        $encFecha = htmlentities($modelo[ENC_FECHA]);
        $disId = htmlentities($modelo[DIS_ID]);
        $disProducto = htmlentities($modelo[DIS_PRODUCTO]);

        $render .= "
            <tr>
                <td data-titulo='Calificación'>$encCalificacion</td>
                <td data-titulo='Comentario'>$encComentario</td>
                <td data-titulo='Fecha'>$encFecha</td>
                <td data-titulo='N° de Serie'>$disId</td>
                <td data-titulo='Producto Dispensado'>$disProducto</td>
            </tr>
        ";
    }

    $render .= "
            </tbody>
        </table>
    ";
    }

    devuelveJson(["listaEncuesta" => ["innerHTML" => $render]]);
});
