<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: " . base_url('auth/login'));
    exit;
}
if (isset($_GET["id_atribut"]) && is_numeric($_GET["id_atribut"])) {
    $id_atribut = $_GET["id_atribut"];
} else {
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);
    exit;
}

if (deleteAtribut($id_atribut) > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
exit;
