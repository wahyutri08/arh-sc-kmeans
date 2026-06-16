<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    echo json_encode([
        'status' => 'redirect'
    ]);
    exit;
}

$cluster = query("
    SELECT c.*
    FROM cluster c
    LEFT JOIN (
        SELECT DISTINCT id_cluster
        FROM nilai_cluster
    ) nc ON c.id_cluster = nc.id_cluster
    WHERE nc.id_cluster IS NULL
    ORDER BY c.id_cluster ASC
    LIMIT 1
");

if (!$cluster) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Semua Cluster Sudah Terisi'
    ]);

    exit;
}

echo json_encode([
    'status' => 'success',
    'id_cluster' => $cluster[0]['id_cluster'],
    'nama_cluster' => $cluster[0]['nama_cluster']
]);
