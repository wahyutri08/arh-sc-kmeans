<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... proses simpanhasilakhir atau perhitungan lainnya ...

    // Panggil fungsi untuk menyimpan hasil
    $result = simpanhasilakhir($centroids, $clusters, $history, $_SESSION['id'], date('Y-m-d H:i:s'), $kelurahan, $data, $atribut, $actualIterations);

    // Mulai output buffering
    ob_start();

    // Tampilkan tabel atau hasil proses yang diinginkan
?>
    <!-- Tabel Hasil Clustering Sebelum Iterasi Pertama -->
    <?php if (isset($_POST['submit'])) : ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Hasil Clustering Sebelum Iterasi Pertama</h4>
                    <h6 class="card-subtitle">Centroid Awal</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Cluster</th>
                                    <?php foreach ($atribut as $atr) : ?>
                                        <th><?= $atr['nama_atribut']; ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($initialCentroids as $index => $centroid) : ?>
                                    <tr>
                                        <td>Cluster <?= $index + 1 ?></td>
                                        <?php foreach ($centroid as $value) : ?>
                                            <td><?= number_format($value) ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <h6 class="card-subtitle mt-3">Cluster Awal</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Kelurahan</th>
                                    <?php foreach ($atribut as $atr) : ?>
                                        <th><?= $atr['nama_atribut']; ?></th>
                                    <?php endforeach; ?>
                                    <?php foreach ($cluster as $cls) : ?>
                                        <th><?= $cls['nama_cluster']; ?></th>
                                    <?php endforeach; ?>
                                    <th>Jarak Terdekat</th>
                                    <th>Cluster</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($initialResult['clusters'] as $clusterId => $clusterData) : ?>
                                    <?php foreach ($clusterData as $dataIndex) : ?>
                                        <tr>
                                            <td><?= $kelurahan[$dataIndex]['nama_kelurahan'] ?></td>
                                            <?php foreach ($data[$dataIndex] as $value) : ?>
                                                <td><?= number_format($value) ?></td>
                                            <?php endforeach; ?>
                                            <?php
                                            $distances = $initialResult['distances'][$dataIndex];
                                            foreach ($distances as $distance) : ?>
                                                <td><?= number_format($distance, 3) ?></td>
                                            <?php endforeach; ?>
                                            <td><?= number_format(min($distances), 3) ?></td>
                                            <td>Cluster <?= array_search(min($distances), $distances) + 1 ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabel Hasil Iterasi -->
        <?php foreach ($history as $iteration) : ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Proses Iterasi <?= $iteration['iteration']; ?></h4>
                        <h6 class="card-subtitle">Centroid</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Cluster</th>
                                        <?php foreach ($atribut as $atr) : ?>
                                            <th><?= $atr['nama_atribut']; ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $centroids = $iteration['centroids'];
                                    ksort($centroids);
                                    foreach ($iteration['centroids'] as $index => $centroid) : ?>
                                        <tr>
                                            <td><?= $cluster[$index]['nama_cluster'] ?></td>
                                            <?php foreach ($centroid as $value) : ?>
                                                <td><?= number_format($value, 3) ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <h6 class="card-subtitle mt-3">Hasil Proses Iterasi <?= $iteration['iteration']; ?></h6>
                        <div class="table-responsive" style="overflow-y: hidden">
                            <table class="table table-bordered table-striped" data-bs-toggle="table" data-height="250" data-mobile-responsive="true">
                                <thead>
                                    <tr>
                                        <th>Nama Kelurahan</th>
                                        <?php foreach ($atribut as $atr) : ?>
                                            <th><?= $atr['nama_atribut']; ?></th>
                                        <?php endforeach; ?>
                                        <?php foreach ($cluster as $cls) : ?>
                                            <th><?= $cls['nama_cluster']; ?></th>
                                        <?php endforeach; ?>
                                        <th>Jarak Terdekat</th>
                                        <th>Cluster</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($iteration['clusters'] as $clusterId => $clusterData) : ?>
                                        <?php foreach ($clusterData as $dataIndex) : ?>
                                            <tr>
                                                <td><?= $kelurahan[$dataIndex]['nama_kelurahan'] ?></td>
                                                <?php foreach ($data[$dataIndex] as $value) : ?>
                                                    <td><?= number_format($value) ?></td>
                                                <?php endforeach; ?>
                                                <?php
                                                $distances = [];
                                                foreach ($iteration['centroids'] as $centroid) {
                                                    $distances[] = calculateDistance($data[$dataIndex], $centroid);
                                                }
                                                ksort($distances);
                                                foreach ($distances as $distance) : ?>
                                                    <td><?= number_format($distance, 3) ?></td>
                                                <?php endforeach; ?>
                                                <td><?= number_format(min($distances), 3) ?></td>
                                                <td>Cluster <?= array_search(min($distances), $distances) + 1 ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
<?php
    $html_output = ob_get_clean(); // Ambil output yang di-buffer

    // Kembalikan hasil dalam bentuk JSON
    echo json_encode([
        "status" => $result > 0 ? "success" : "error",
        "message" => $result > 0 ? "Proses Perhitungan Berhasil" : "Perhitungan Gagal Diproses",
        "html_output" => $html_output
    ]);
    exit;
}
?>