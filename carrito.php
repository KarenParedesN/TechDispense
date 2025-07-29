<?php
session_start();

require '../config/database.php';
require '../config/config.php';

if (!defined('KEY_TOKEN')) {
    define('KEY_TOKEN', 'TecNI.ecc-3534*');
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$token = isset($_POST['token']) ? $_POST['token'] : '';

if ($id <= 0 || empty($token)) {
    echo json_encode(['ok' => false, 'error' => 'ID o token inválido']);
    exit;
}

$token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);

if ($token == $token_tmp) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    } else {
        $_SESSION['cart'][$id] = 1;
    }

    $numero = array_sum($_SESSION['cart']);

    echo json_encode(['ok' => true, 'numero' => $numero]);
} else {
    echo json_encode(['ok' => false, 'error' => 'Token incorrecto']);
}
