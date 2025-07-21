<?php
session_start();
include_once("../auth_check.php");

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

// Pastikan fungsi deletenilaiKelurahan sudah didefinisikan dan bekerja dengan benar
$id_cluster = $_GET["id_cluster"];

if (deletenilaiCluster($id_cluster) > 0) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
exit;
