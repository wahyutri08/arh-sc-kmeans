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

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    echo json_encode([
        'status' => 'redirect'
    ]);
    exit;
}

if (!isset($_POST['id_pc']) || !isset($_POST['id_cluster'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Parameter Tidak Lengkap.'
    ]);
    exit;
}

$id_pc      = (int) $_POST['id_pc'];
$id_cluster = (int) $_POST['id_cluster'];

try {

    // =======================
    // Cek apakah PC sudah dipakai
    // =======================

    $cekPc = query("
        SELECT id_pc
        FROM nilai_cluster
        WHERE id_pc = $id_pc
        LIMIT 1
    ");

    if ($cekPc) {
        throw new Exception('PC Ini Sudah Digunakan Sebagai Centroid Cluster.');
    }

    // =======================
    // Ambil nilai PC
    // =======================

    $nilaiPc = query("
        SELECT *
        FROM nilai_pc
        WHERE id_pc = $id_pc
    ");

    if (!$nilaiPc) {
        throw new Exception('Data PC Tidak Ditemukan.');
    }

    // =======================
    // Mulai Transaction
    // =======================

    mysqli_begin_transaction($db);

    foreach ($nilaiPc as $row) {

        $id_atribut = (int) $row['id_atribut'];
        $nilai      = mysqli_real_escape_string($db, $row['nilai']);

        $sql = "
            INSERT INTO nilai_cluster (
                id_cluster,
                id_pc,
                id_atribut,
                nilai
            ) VALUES (
                '$id_cluster',
                '$id_pc',
                '$id_atribut',
                '$nilai'
            )
        ";

        if (!mysqli_query($db, $sql)) {
            throw new Exception(mysqli_error($db));
        }
    }

    mysqli_commit($db);

    echo json_encode([
        'status' => 'success'
    ]);
} catch (Exception $e) {

    mysqli_rollback($db);

    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}
