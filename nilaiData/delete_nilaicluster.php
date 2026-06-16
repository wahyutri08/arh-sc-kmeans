<?php
session_start();
include_once("../auth_check.php");
header('Content-Type: application/json');

// cek login
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    echo json_encode([
        "status" => "redirect"
    ]);
    exit;
}

// hanya izinkan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    // echo json_encode([
    //     "status" => "error",
    //     "message" => "Method not allowed"
    // ]);
    exit;
}

// validasi id
if (!isset($_POST['id_cluster']) || !is_numeric($_POST['id_cluster'])) {
    http_response_code(400);
    // echo json_encode([
    //     "status" => "error",
    //     "message" => "Invalid ID"
    // ]);
    exit;
}

$id_cluster = (int) $_POST['id_cluster'];

// jalankan function delete
if (deletenilaiCluster($id_cluster) > 0) {

    echo json_encode([
        "status" => "success",
        "message" => "Data successfully deleted"
    ]);
} else {

    echo json_encode([
        "status" => "error",
        "message" => "Gagal Menghapus Data"
    ]);
}
exit;
