<?php
session_start();
include_once("../auth_check.php");

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: " . base_url('auth/login'));
    exit;
}

if (isset($_GET["id_pc"]) && is_numeric($_GET["id_pc"])) {
    $id_pc = (int)$_GET["id_pc"];
} else {
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);
    exit;
}

if (deletenilaiPC($id_pc) > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
exit;
