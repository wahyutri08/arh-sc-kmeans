<?php
require_once("../auth_check.php");
if (!isset($_GET['id_cluster']) || !is_numeric($_GET['id_cluster'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID Cluster tidak valid']);
    exit;
}
if (!isset($_GET['id_pc']) || !is_numeric($_GET['id_pc'])) {
    echo json_encode(['status' => 'error', 'message' => 'ID PC tidak valid']);
    exit;
}
$id_cluster = $_GET['id_cluster'];
$id_pc = $_GET['id_pc'];

// Ambil semua nilai untuk id_cluster dan id_pc dari tabel nilai_pc
$nilai_list = query("SELECT * FROM nilai_pc WHERE id_cluster = $id_cluster AND id_pc = $id_pc");

// Siapkan array data [{id_atribut, nilai}, ...]
$data = [];
foreach ($nilai_list as $row) {
    $data[] = [
        'id_atribut' => $row['id_atribut'],
        'nilai' => $row['nilai']
    ];
}
echo json_encode([
    'status' => 'success',
    'data' => $data
]);
