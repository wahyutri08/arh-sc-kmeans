<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id_laporan = $_GET["id"];
} else {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

// Ambil Data Lpaoran
$laporan = query("SELECT 
                laporan.id, 
                users.nama, 
                users.role, 
                laporan.tanggal_laporan,
                laporan.jumlah_iterasi 
                FROM laporan 
                JOIN users 
                ON laporan.user_id = users.id WHERE laporan.id = $id_laporan");
if (empty($laporan)) {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

$hasil_akhir = query("SELECT 
                laporan_hasil_akhir.id, 
                laporan_hasil_akhir.nama_pc, 
                laporan_hasil_akhir.nama_cluster, 
                laporan_hasil_akhir_atribut.nama_atribut, 
                laporan_hasil_akhir_atribut.nilai
                FROM laporan_hasil_akhir 
                JOIN laporan_hasil_akhir_atribut 
                ON laporan_hasil_akhir.id = laporan_hasil_akhir_atribut.id_laporan_hasil_akhir
                WHERE laporan_hasil_akhir.id_laporan = '$id_laporan'
                 ");

// Organize data by PC
$data_by_pc = [];
foreach ($hasil_akhir as $data) {
    $data_by_pc[$data['nama_pc']]['cluster'] = $data['nama_cluster'];
    $data_by_pc[$data['nama_pc']]['atribut'][$data['nama_atribut']] = $data['nilai'];
}


// Hitung jumlah total PC per cluster
$jumlah_per_cluster = [];
foreach ($data_by_pc as $pc => $data) {
    $cluster = $data['cluster'];
    if (!isset($jumlah_per_cluster[$cluster])) {
        $jumlah_per_cluster[$cluster] = 0;
    }
    $jumlah_per_cluster[$cluster]++;
}
// Untuk Chart.js
$js_cluster_labels = json_encode(array_keys($jumlah_per_cluster));
$js_cluster_values = json_encode(array_values($jumlah_per_cluster));


// --- PASTIKAN label harga SAMA DENGAN YANG DI DATABASE ---
$label_harga = 'Harga (Juta Rupiah)';

// Membagi data berdasarkan cluster
$range_harga_cluster = [];
foreach ($data_by_pc as $pc => $data) {
    $cluster = $data['cluster'];
    if (isset($data['atribut'][$label_harga])) {
        $harga = $data['atribut'][$label_harga];
        $range_harga_cluster[$cluster][] = $harga;
    }
}

// Hitung min & max harga per cluster
foreach ($range_harga_cluster as $cluster => $list_harga) {
    $range_harga_cluster[$cluster] = [
        'min' => min($list_harga),
        'max' => max($list_harga),
        'count' => count($list_harga)
    ];
}

function formatNilai($nilai)
{
    return rtrim(
        rtrim(
            number_format((float)$nilai, 3, '.', ''),
            '0'
        ),
        '.'
    );
}

$title = "Detail Hasil Proses Perhitungan";
require_once '../partials/header.php';
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <?php include '../partials/overlay.php'; ?>
    <div class="wrapper">

        <!-- Navbar -->
        <?php require_once '../partials/navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once '../partials/sidebar.php'; ?>
        <!-- /.sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Detail Hasil Proses Perhitungan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
                                <li class="breadcrumb-item">Laporan</li>
                                <li class="breadcrumb-item">Laporan Hasil Proses Perhitungan</li>
                                <li class="breadcrumb-item active">Detail Hasil Proses Perhitungan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">

                            <!-- Informasi Pengguna -->
                            <div class="card card-danger">
                                <div class="card-header">
                                    <span class="fas fa-laptop"></span> &nbsp;INFORMASI
                                    <div class="card-tools">
                                        &nbsp;
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <td>ID Laporan</td>
                                                <td><?= $id_laporan ?></td>
                                            </tr>
                                            <tr>
                                                <td>Nama User</td>
                                                <td><?= htmlspecialchars($laporan[0]['nama']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Role</td>
                                                <td><?= htmlspecialchars($laporan[0]['role']); ?></td>
                                            </tr>
                                            <tr>
                                                <td>Jumlah Proses</td>
                                                <td><?= htmlspecialchars($laporan[0]['jumlah_iterasi']); ?> Iterasi</td>
                                            </tr>
                                            <tr>
                                                <td>Tanggal Laporan</td>
                                                <td><?= htmlspecialchars($laporan[0]['tanggal_laporan']); ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- SELECT2 EXAMPLE -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <span class="nav-icon fas fa-table"></span> &nbsp;DATA CLUSTER ITERASI TERAKHIR

                                    <div class="card-tools">
                                        &nbsp;
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive">
                                    <table id="example1" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Cluster</th>
                                                <th class="text-center">Nama PC</th>
                                                <?php
                                                // Ambil atribut unik
                                                $atribut_unik = [];
                                                foreach ($hasil_akhir as $atribut) {
                                                    if (!in_array($atribut['nama_atribut'], $atribut_unik)) {
                                                        $atribut_unik[] = $atribut['nama_atribut'];
                                                    }
                                                }
                                                foreach ($atribut_unik as $nama_atribut) : ?>
                                                    <th class="text-center"><?= $nama_atribut; ?></th>
                                                <?php endforeach; ?>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Organize data by PC
                                            $data_by_pc = [];
                                            foreach ($hasil_akhir as $data) {
                                                $data_by_pc[$data['nama_pc']]['cluster'] = $data['nama_cluster'];
                                                $data_by_pc[$data['nama_pc']]['atribut'][$data['nama_atribut']] = $data['nilai'];
                                            }

                                            // Display data
                                            foreach ($data_by_pc as $pc => $data) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $data['cluster']; ?></td>
                                                    <td><?= $pc; ?></td>
                                                    <?php foreach ($atribut_unik as $atribut) : ?>
                                                        <td class="text-center">
                                                            <?= isset($data['atribut'][$atribut])
                                                                ? formatNilai($data['atribut'][$atribut])
                                                                : '-' ?>
                                                        </td>
                                                    <?php endforeach; ?>

                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                            <!-- Diagram Hasil -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><span class="far fa-chart-bar"></span> &nbsp;DIAGRAM HASIL</h3>
                                    <div class="card-tools">
                                        &nbsp;
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <canvas id="donutChart" style="height: 230px; min-height: 230px;"></canvas>
                                </div>
                                <hr>
                                <!-- Knob Hasil -->
                                <div class="card-body">
                                    <div class="row">
                                        <?php
                                        // Warna default (bisa ditambah lagi jika cluster > 6)
                                        $warna_fg = [
                                            '#3c8dbc',
                                            '#f56954',
                                            '#f39c12',
                                            '#00a65a',
                                            '#605ca8',
                                            '#d81b60',
                                            '#17a2b8',
                                            '#6610f2',
                                            '#20c997',
                                            '#fd7e14',
                                            '#6f42c1',
                                            '#e83e8c',
                                            '#28a745',
                                            '#dc3545',
                                            '#007bff',
                                            '#ffc107',
                                        ];
                                        $width = [90, 120, 90, 90, 90, 90]; // Urutan ukuran

                                        $i = 0;
                                        foreach ($jumlah_per_cluster as $cluster => $total) :
                                        ?>
                                            <div class="col-12 col-md-4 text-center mb-4">
                                                <input type="text" class="knob"
                                                    value="<?= $total ?>"
                                                    data-skin="tron" data-thickness="0.2"
                                                    data-width="<?= $width[$i % count($width)] ?>"
                                                    data-height="<?= $width[$i % count($width)] ?>"
                                                    data-fgColor="<?= $warna_fg[$i % count($warna_fg)] ?>"
                                                    data-readonly="true">
                                                <div class="knob-label"><?= htmlspecialchars($cluster) ?></div>
                                            </div>
                                        <?php $i++;
                                        endforeach; ?>
                                    </div>
                                </div>
                                <!-- <div class="card">

                                </div> -->
                            </div>
                            <!-- Penjelasan Jurusan -->
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title"><span class="fas fa-info"></span> &nbsp;PENJELASAN HASIL CLUSTER</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="callout callout-danger">
                                                <h4 class="text-danger font-weight-bold text-center">Keterangan</h4>
                                                <p> Setiap cluster pada hasil proses perhitungan K-Means merepresentasikan kelompok Desktop PC yang memiliki kemiripan karakteristik berdasarkan atribut yang telah dianalisis, seperti harga, RAM, penyimpanan, dan spesifikasi lainnya.
                                                    <br>
                                                    Pengelompokan ini bertujuan untuk membantu pengguna dalam memilih Desktop PC yang paling sesuai dengan kebutuhan dan anggaran, serta memberikan gambaran tentang perbedaan karakteristik setiap cluster.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tabs Range Harga Berdasarkan Cluster -->
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="card card-primary card-outline card-outline-tabs">
                                        <div class="card-header p-0 border-bottom-0">
                                            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                                <?php $tab_active = true;
                                                foreach ($range_harga_cluster as $cluster => $info): ?>
                                                    <li class="nav-item">
                                                        <a class="nav-link <?= $tab_active ? 'active' : '' ?>"
                                                            id="tab-<?= md5($cluster) ?>"
                                                            data-toggle="pill"
                                                            href="#range-<?= md5($cluster) ?>"
                                                            role="tab"
                                                            aria-selected="<?= $tab_active ? 'true' : 'false' ?>">
                                                            <?= htmlspecialchars($cluster) ?>
                                                        </a>
                                                    </li>
                                                <?php $tab_active = false;
                                                endforeach; ?>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content" id="custom-tabs-four-tabContent">
                                                <?php $tab_active = true;
                                                foreach ($range_harga_cluster as $cluster => $info): ?>
                                                    <div class="tab-pane fade <?= $tab_active ? 'show active' : '' ?>"
                                                        id="range-<?= md5($cluster) ?>"
                                                        role="tabpanel">
                                                        <p><b>Range Harga (Juta Rupiah):</b></p>
                                                        <ul>
                                                            <li>Minimal: <b><?= number_format($info['min'], 2, ',', '.') ?> Juta</b></li>
                                                            <li>Maksimal: <b><?= number_format($info['max'], 2, ',', '.') ?> Juta</b></li>
                                                            <li>Total PC di cluster ini: <b><?= $info['count'] ?></b></li>
                                                        </ul>
                                                    </div>
                                                <?php $tab_active = false;
                                                endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- /.col-md-12 -->
                    </div> <!-- /.row -->
                </div> <!-- /.container-fluid -->
                <div style="padding-bottom: 80px;"></div>
            </section>
        </div> <!-- /.content-wrapper -->

        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?php require_once '../partials/footer.php';  ?>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <?php require_once '../partials/scripts.php'; ?>

    <!-- jQuery Validation + AJAX Submit -->
    <script>
        $(function() {
            $("#example1").DataTable({
                "paging": true,
                "lengthChange": true,
                "pageLength": 10,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": false,
                "buttons": ["excel", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        // === DONUT CHART JUMLAH CLUSTER ===
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('donutChart').getContext('2d');
            var clusterLabels = <?= $js_cluster_labels ?>;
            var clusterValues = <?= $js_cluster_values ?>;

            var donutChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: clusterLabels,
                    datasets: [{
                        data: clusterValues,
                        backgroundColor: [
                            '#3c8dbc', '#f56954', '#f39c12', '#00a65a', '#605ca8', '#d81b60'
                        ]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'bottom'
                        }
                    }
                }
            });
        });
    </script>
    <script>
        $(function() {
            $(".knob").knob();
        });
    </script>
    <script>
        $(window).on('load', function() {
            $('#pageLoader').fadeOut(250);
        });
    </script>
</body>

</html>