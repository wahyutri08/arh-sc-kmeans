<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    echo json_encode([
        'status' => 'redirect'
    ]);
    exit;
}

$id_pc      = (int)$_POST['id_pc'];
$id_cluster = (int)$_POST['id_cluster'];


// =======================
// Cek apakah PC sudah dipakai
// =======================

$cekPc = query("
    SELECT *
    FROM nilai_cluster
    WHERE id_pc = $id_pc
    LIMIT 1
");

if ($cekPc) {

    echo json_encode([
        'status' => 'error',
        'message' => 'PC ini sudah digunakan sebagai centroid cluster.'
    ]);
    exit;
}


// =======================
// Ambil data nilai PC
// =======================

$nilaiPc = query("
    SELECT *
    FROM nilai_pc
    WHERE id_pc = $id_pc
");

if (!$nilaiPc) {

    echo json_encode([
        'status' => 'error',
        'message' => 'Data PC tidak ditemukan.'
    ]);
    exit;
}


// =======================
// Insert ke nilai_cluster
// =======================

foreach ($nilaiPc as $row) {

    query("
        INSERT INTO nilai_cluster (
            id_cluster,
            id_pc,
            id_atribut,
            nilai
        ) VALUES (
            '$id_cluster',
            '$id_pc',
            '{$row['id_atribut']}',
            '{$row['nilai']}'
        )
    ");
}

echo json_encode([
    'status' => 'success'
]);
