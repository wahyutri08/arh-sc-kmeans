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

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {

    echo json_encode([
        'status' => false,
        'redirect' => true,
        'url' => '../logout'
    ]);

    exit;
}
ob_start();

try {

    // Cek centroid kosong
    $clusterKosong = query("
        SELECT c.id_cluster
        FROM cluster c
        LEFT JOIN (
            SELECT DISTINCT id_cluster
            FROM nilai_cluster
        ) nc ON c.id_cluster = nc.id_cluster
        WHERE nc.id_cluster IS NULL
    ");

    if ($clusterKosong) {
        echo json_encode([
            'status' => false,
            'message' => 'Masih Ada Cluster Yang Belum Memiliki Centroid.'
        ]);
        exit;
    }

    // Ambil data
    $nama_pc = query("SELECT * FROM nama_pc ORDER BY id_pc");
    $atribut = query("SELECT * FROM atribut ORDER BY id_atribut");
    $cluster = query("SELECT * FROM cluster ORDER BY id_cluster");

    if (!$nama_pc || !$atribut || !$cluster) {
        throw new Exception('Gagal mengambil data database.');
    }

    // Dataset
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
            $row[] = $nilai ? $nilai[0]['nilai'] : 0;
        }
        $data[] = $row;
    }

    // Centroid awal
    $initialCentroids = [];
    foreach ($cluster as $cls) {
        $row = [];
        foreach ($atribut as $attr) {
            $nilai = query("
                SELECT nilai
                FROM nilai_cluster
                WHERE id_cluster = {$cls['id_cluster']}
                AND id_atribut = {$attr['id_atribut']}
            ");
            $row[] = $nilai ? $nilai[0]['nilai'] : 0;
        }
        $initialCentroids[] = $row;
    }

    if (empty($data)) {
        throw new Exception('Data PC kosong.');
    }

    // Iterasi
    $maxIterations = isset($_POST['iterasi'])
        ? (int)$_POST['iterasi']
        : 1000;

    if ($maxIterations <= 0) {
        $maxIterations = 1000;
    }

    // Cluster awal
    $initialResult = getInitialClusters(
        $data,
        $initialCentroids
    );

    // K-Means
    $result = kmeans(
        $data,
        $initialCentroids,
        $maxIterations
    );

    $centroids = $result['centroids'];
    $clusters  = $result['clusters'];
    $history   = $result['history'];

    $actualIterations = $result['iteration'];

    // Simpan hasil
    date_default_timezone_set('Asia/Jakarta');

    $simpan = simpanhasilakhir(
        $centroids,
        $clusters,
        $history,
        $_SESSION['id'],
        date('Y-m-d'),
        $nama_pc,
        $data,
        $atribut,
        $actualIterations
    );

    if (!$simpan) {
        throw new Exception('Gagal Menyimpan Hasil Clustering.');
    }

?>
    <!-- SECTION: CENTROID AWAL -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <strong>Hasil Clustering Sebelum Iterasi Pertama</strong><br>
                            <span>Centroid Awal</span>
                        </div>
                        <!-- <div class="card-header">
                                        <h3 class="card-title">Centroid Awal</h3>
                                    </div> -->
                        <div class="card-body table-responsive">
                            <table id="exampleCentroidAwal" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Cluster</th>
                                        <?php foreach ($atribut as $atr) : ?>
                                            <th class="text-center"><?= $atr['nama_atribut']; ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($initialCentroids as $index => $centroid) : ?>
                                        <tr>
                                            <td>Cluster <?= $index + 1 ?></td>
                                            <?php foreach ($centroid as $value) : ?>
                                                <td class="text-center"><?= number_format($value, 3) ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card -->
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: CLUSTER AWAL -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <div class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">Cluster Awal</h3>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="exampleClusterAwal" class="table table-bordered table-hover text-nowarp">
                                <thead>
                                    <tr>
                                        <th class="text-center">Nama PC Editing</th>
                                        <?php foreach ($atribut as $atr) : ?>
                                            <th class="text-center"><?= $atr['nama_atribut']; ?></th>
                                        <?php endforeach; ?>
                                        <?php foreach ($cluster as $cls) : ?>
                                            <th class="text-center"><?= $cls['nama_cluster']; ?></th>
                                        <?php endforeach; ?>
                                        <th class="text-center">Jarak Terdekat</th>
                                        <th class="text-center">Cluster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($initialResult['clusters'] as $clusterId => $clusterData) : ?>
                                        <?php foreach ($clusterData as $dataIndex) : ?>
                                            <tr>
                                                <td><?= $nama_pc[$dataIndex]['nama_pc'] ?? 'N/A' ?></td>
                                                <?php foreach ($data[$dataIndex] as $value) : ?>
                                                    <td class="text-center"><?= number_format($value, 3) ?></td>
                                                <?php endforeach; ?>
                                                <?php
                                                $distances = $initialResult['distances'][$dataIndex];
                                                foreach ($distances as $distance) : ?>
                                                    <td class="text-center"><?= number_format($distance, 3) ?></td>
                                                <?php endforeach; ?>
                                                <td class="text-center"><?= number_format(min($distances), 3) ?></td>
                                                <td class="text-center">Cluster <?= array_search(min($distances), $distances) + 1 ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div> <!-- end card -->
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION: ITERASI -->
    <?php foreach ($history as $iteration) : ?>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col">
                        <!-- Centroid Iterasi -->
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <strong class="">Proses Iterasi <?= $iteration['iteration']; ?></strong><br>
                                <span>Centroid</span>
                            </div>
                            <div class="card-body table-responsive">
                                <table id="example<?= $iteration['iteration'] ?>" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama Cluster</th>
                                            <?php foreach ($atribut as $atr) : ?>
                                                <th class="text-center"><?= $atr['nama_atribut']; ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($iteration['centroids'] as $index => $centroid) : ?>
                                            <tr>
                                                <td class="text-center"><?= $cluster[$index]['nama_cluster'] ?></td>
                                                <?php foreach ($centroid as $value) : ?>
                                                    <td class="text-center"><?= number_format($value, 3) ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Hasil Iterasi -->
                        <div class="card card-outline card-warning">
                            <div class="card-header">
                                <h5 class="card-title mt-3">Hasil Proses Iterasi <?= $iteration['iteration']; ?></h5>
                            </div>
                            <div class="card-body table-responsive">
                                <table id="exampleIterasi<?= $iteration['iteration'] ?>" class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Nama PC Editing</th>
                                            <?php foreach ($atribut as $atr) : ?>
                                                <th class="text-center"><?= $atr['nama_atribut']; ?></th>
                                            <?php endforeach; ?>
                                            <?php foreach ($cluster as $cls) : ?>
                                                <th class="text-center"><?= $cls['nama_cluster']; ?></th>
                                            <?php endforeach; ?>
                                            <th class="text-center">Jarak Terdekat</th>
                                            <th class="text-center">Cluster</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($iteration['clusters'] as $clusterId => $clusterData) : ?>
                                            <?php foreach ($clusterData as $dataIndex) : ?>
                                                <tr>
                                                    <td><?= $nama_pc[$dataIndex]['nama_pc'] ?? 'N/A' ?></td>
                                                    <?php foreach ($data[$dataIndex] as $value) : ?>
                                                        <td class="text-center"><?= number_format($value, 3) ?></td>
                                                    <?php endforeach; ?>

                                                    <?php
                                                    $distances = [];
                                                    foreach ($iteration['centroids'] as $centroid) {
                                                        $distances[] = calculateDistance($data[$dataIndex], $centroid);
                                                    }
                                                    foreach ($distances as $distance) : ?>
                                                        <td class="text-center"><?= number_format($distance, 3) ?></td>
                                                    <?php endforeach; ?>

                                                    <td class="text-center"><?= number_format(min($distances), 3) ?></td>
                                                    <td class="text-center">Cluster <?= array_search(min($distances), $distances) + 1 ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div> <!-- end container-fluid -->
        </section>
    <?php endforeach; ?>
<?php

    $html = trim(ob_get_clean());

    echo json_encode([
        'status'    => true,
        'iteration' => $actualIterations,
        'html'      => $html
    ]);

    exit;
} catch (Exception $e) {

    if (ob_get_level()) {
        ob_end_clean();
    }

    echo json_encode([
        'status' => false,
        'message' => $e->getMessage()
    ]);
}

exit;
