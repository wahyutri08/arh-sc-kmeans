<?php

// Hanya boleh via AJAX
if (
    !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    http_response_code(403);
    exit;
}

// Hanya boleh method POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

session_start();

include_once("../auth_check.php");

header('Content-Type: application/json');

try {

    if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
        echo json_encode([
            'status' => 'redirect'
        ]);
        exit;
    }

    $cluster = query("
        SELECT c.id_cluster, c.nama_cluster
        FROM cluster c
        LEFT JOIN (
            SELECT DISTINCT id_cluster
            FROM nilai_cluster
        ) nc ON c.id_cluster = nc.id_cluster
        WHERE nc.id_cluster IS NULL
        ORDER BY c.id_cluster ASC
        LIMIT 1
    ");

    if (empty($cluster)) {

        echo json_encode([
            'status' => 'error',
            'message' => 'Semua Cluster Sudah Terisi'
        ]);
        exit;
    }

    echo json_encode([
        'status'       => 'success',
        'id_cluster'   => $cluster[0]['id_cluster'],
        'nama_cluster' => $cluster[0]['nama_cluster']
    ]);
    exit;
} catch (Exception $e) {

    echo json_encode([
        'status'  => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}
