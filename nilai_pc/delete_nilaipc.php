<?php
session_start();
include_once("../auth_check.php");

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

// Pastikan fungsi deletenilaiKelurahan sudah didefinisikan dan bekerja dengan benar
$id_pc = $_GET["id_pc"];

if (deletenilaiPC($id_pc) > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
exit;
