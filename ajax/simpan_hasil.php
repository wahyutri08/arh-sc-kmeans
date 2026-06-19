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
require_once("../auth_check.php");

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {

    echo json_encode([
        'status'   => false,
        'redirect' => true,
        'url'      => '../logout'
    ]);
    exit;
}

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 0);

ob_start();

try {

    // ======================
    // Validasi data hasil
    // ======================
    if (empty($_POST['hasil'])) {
        throw new Exception('Data Hasil Tidak Ditemukan');
    }

    $hasil = json_decode($_POST['hasil'], true);

    if (!is_array($hasil)) {
        throw new Exception('Format Data Tidak Valid');
    }

    // ======================
    // Validasi struktur data
    // ======================
    $requiredKeys = [
        'centroids',
        'clusters',
        'history',
        'actualIterations'
    ];

    foreach ($requiredKeys as $key) {
        if (!isset($hasil[$key])) {
            throw new Exception("Data {$key} Tidak Ditemukan");
        }
    }

    // ======================
    // Ambil data master
    // ======================
    $nama_pc = query("
        SELECT *
        FROM nama_pc
        ORDER BY id_pc
    ");

    $atribut = query("
        SELECT *
        FROM atribut
        ORDER BY id_atribut
    ");

    if (!$nama_pc || !$atribut) {
        throw new Exception('Gagal Mengambil Data Master');
    }

    // ======================
    // Dataset ulang
    // ======================
    $data = [];

    foreach ($nama_pc as $pc) {

        $row = [];

        foreach ($atribut as $attr) {

            $nilai = query("
                SELECT nilai
                FROM nilai_pc
                WHERE id_pc = {$pc['id_pc']}
                AND id_atribut = {$attr['id_atribut']}
            ");

            $row[] = $nilai
                ? (float)$nilai[0]['nilai']
                : 0;
        }

        $data[] = $row;
    }

    // ======================
    // Simpan hasil clustering
    // ======================
    $simpan = simpanhasilakhir(
        $hasil['centroids'],
        $hasil['clusters'],
        $hasil['history'],
        $_SESSION['id'],
        date('Y-m-d H:i:s'),
        $nama_pc,
        $data,
        $atribut,
        $hasil['actualIterations']
    );

    if (!$simpan) {
        throw new Exception('Gagal Menyimpan Hasil Clustering');
    }

    ob_clean();

    echo json_encode([
        'status'  => true,
        'message' => 'Hasil Clustering Berhasil Disimpan'
    ]);

    exit;
} catch (Exception $e) {

    ob_clean();

    echo json_encode([
        'status'  => false,
        'message' => $e->getMessage()
    ]);

    exit;
}
