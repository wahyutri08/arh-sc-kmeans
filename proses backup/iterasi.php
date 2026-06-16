<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

// Ambil data nama pC dan atribut
$nama_pc = query("SELECT * FROM nama_pc ORDER BY id_pc");
$atribut = query("SELECT * FROM atribut ORDER BY id_atribut");
$cluster = query("SELECT * FROM cluster ORDER BY id_cluster");

if (!$nama_pc || !$atribut || !$cluster) {
    die("Error fetching data from database.");
}

// Ambil nilai nama$nama_pc untuk setiap atribut
$data = [];
foreach ($nama_pc as $pc) {
    $row = [];
    foreach ($atribut as $attr) {
        $nilai = query("SELECT nilai FROM nilai_pc WHERE id_pc = " . $pc['id_pc'] . " AND id_atribut = " . $attr['id_atribut']);
        if ($nilai) {
            $row[] = $nilai[0]['nilai'];
        } else {
            $row[] = 0;
        }
    }
    $data[] = $row;
}

// Ambil nilai Cluster untuk setiap atribut dan iterasi
$initialCentroids = [];
foreach ($cluster as $cls) {
    $row = [];
    foreach ($atribut as $attr) {
        $nilai = query("SELECT nilai FROM nilai_cluster WHERE id_cluster = " . $cls['id_cluster'] . " AND id_atribut = " . $attr['id_atribut']);
        if ($nilai) {
            $row[] = $nilai[0]['nilai'];
        } else {
            $row[] = 0;
        }
    }
    $initialCentroids[] = $row;
}

// Default nilai K dan iterasi
$defaultIterations = 1000;

// Cek apakah semua cluster sudah memiliki centroid
if (isset($_POST['submit'])) {

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

        $_SESSION['error_cluster'] =
            "Masih ada cluster yang belum memiliki centroid.";

        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}


if (isset($_POST['iterasi'])) {
    $maxIterations = intval($_POST['iterasi']);
    if ($maxIterations <= 0) {
        $maxIterations = $defaultIterations;
    }
} else {
    $maxIterations = $defaultIterations;
}


// Dapatkan hasil clustering awal sebelum iterasi pertama
$initialResult = getInitialClusters($data, $initialCentroids);

// Jalankan algoritma K-Means
$result = kmeans($data, $initialCentroids, $maxIterations);
$centroids = $result['centroids'];
$clusters = $result['clusters'];
$history = $result['history'];
$actualIterations = $result['iteration'];

// Mengatur zona waktu
date_default_timezone_set('Asia/Jakarta');
if (isset($_POST['submit'])) {
    simpanhasilakhir(
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
}

$title = "Iterasi";
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
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Proses Perhitungan</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
                                <li class="breadcrumb-item">Proses Perhitungan</li>
                                <li class="breadcrumb-item active">Iterasi</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->

            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- jquery validation -->
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title">Proses Perhitungan</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="quickForm">
                                    <div class="card-body">
                                        <div class="form-group col-md-2">
                                            <label for="iterasi">Masukkan Iterasi:</label>
                                            <input type="number"
                                                name="iterasi"
                                                class="form-control"
                                                id="iterasi"
                                                min="1"
                                                required
                                                placeholder="Jumlah Iterasi">
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-success"><i class="fas fa-solid fa-cog"></i> Processing</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!--/.col (left) -->
                        <!-- right column -->
                        <div class="col-md-6">

                        </div>
                        <!--/.col (right) -->
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- Hasil Clustering -->
            <?php if (isset($_POST['submit'])) : ?>

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
            <?php endif; ?>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?php require_once '../partials/footer.php';  ?>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->
    <?php require_once '../partials/scripts.php'; ?>
    <?php if (isset($_SESSION['error_cluster'])) : ?>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: '<?= $_SESSION['error_cluster']; ?>'
            });
        </script>
        <?php unset($_SESSION['error_cluster']); ?>
    <?php endif; ?>
    <!-- jQuery Validation + AJAX Submit -->
    <script>
        $(function() {
            // ✅ Sembunyikan overlay saat halaman pertama kali dimuat
            $('.overlay-wrapper .overlay').hide();

            // ✅ Inisialisasi DataTables
            $("table#exampleCentroidAwal, table#exampleClusterAwal, table[id^=example], table[id^=exampleIterasi]").each(function() {
                const table = $(this).DataTable({
                    paging: true,
                    lengthChange: true,
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    searching: true,
                    ordering: true,
                    info: true,
                    autoWidth: true,
                    responsive: false,
                    buttons: ["excel", "print", "colvis"]
                });

                table.buttons().container()
                    .appendTo($(this).closest('.dataTables_wrapper').find('.col-md-6:eq(0)'));
            });

            // ✅ Validasi form + tampilkan overlay jika valid
            $('#quickForm').validate({
                rules: {
                    iterasi: {
                        required: true,
                        min: 1
                    },
                },
                messages: {
                    iterasi: {
                        required: "Masukkan jumlah iterasi",
                        min: "Iterasi minimal 1"
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function(form) {

                    Swal.fire({
                        title: 'Processing...',
                        html: 'Sedang melakukan proses clustering K-Means',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $('.overlay-wrapper .overlay').show();

                    form.submit();
                }
            });
        });
    </script>

</body>

</html>