<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

$jumlahDataPerHalaman = 10;
$jumlahData = count(query("SELECT * FROM atribut"));
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

if (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0 && $_GET["page"] <= $jumlahHalaman) {
    $halamanAktif = (int)$_GET["page"];
} else {
    $halamanAktif = 1;
}

$jumlahNavigasi = 5; // Jumlah halaman navigasi yang ingin ditampilkan
$startPage = max(1, $halamanAktif - floor($jumlahNavigasi / 2)); // Halaman awal navigasi
$endPage = min($jumlahHalaman, $halamanAktif + floor($jumlahNavigasi / 2)); // Halaman akhir navigasi

// Jika halaman awal terlalu kecil, sesuaikan halaman akhir
if ($startPage == 1) {
    $endPage = min($jumlahHalaman, $startPage + $jumlahNavigasi - 1);
}

// Jika halaman akhir terlalu besar, sesuaikan halaman awal
if ($endPage == $jumlahHalaman) {
    $startPage = max(1, $jumlahHalaman - $jumlahNavigasi + 1);
}

$startData = ($halamanAktif - 1) * $jumlahDataPerHalaman;
$atr = query("SELECT * FROM atribut LIMIT $startData, $jumlahDataPerHalaman");

if (isset($_POST["search"])) {
    $atr = searchAtribut($_POST["keyword"]);
}


?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Atribut</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
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
                            <h1 class="m-0">Data Atribut</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item">Master Data</li>
                                <li class="breadcrumb-item active">Data Atribut</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->

            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card card-outline card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><a href="add_atribut.php" class="btn btn-sm btn-block bg-gradient-primary"><i class="fas fa-plus"></i> Tambah Data</a></h3>
                                    <div class="card-tools mt-1">
                                        <div class="input-group input-group-sm" style="width: 150px;">
                                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-default">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-head-fixed text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Nama Atribut</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <?php if ($atr): ?>
                                            <?php foreach ($atr as $atribut) : ?>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-center"><?= $atribut["id_atribut"]; ?></td>
                                                        <td class="text-center"><?= $atribut["nama_atribut"]; ?></td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                                                    Action
                                                                </button>
                                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                    <li><a class="dropdown-item" href="edit_siswa.php?id_siswa=<?= $siswa["id_siswa"]; ?>">Edit</a></li>
                                                                    <li><a class="dropdown-item tombol-hapus" href="delete_siswa.php?id_siswa=<?= $siswa["id_siswa"]; ?>">Delete</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td class="text-center" colspan="6">No data found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <div class="showing-entries">
                                        <span id="showing-entries">Showing <?= ($startData + 1); ?> to <?= min($startData + $jumlahDataPerHalaman, $jumlahData); ?> of <?= $jumlahData; ?> entries</span>
                                        <ul class="pagination pagination-sm m-0 float-right">
                                            <!-- Tombol Previous -->
                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= max(1, $halamanAktif - 1); ?>">Previous</a>
                                            </li>

                                            <?php
                                            $startPage = max(1, $halamanAktif - 2);
                                            $endPage = min($jumlahHalaman, $halamanAktif + 2);

                                            if ($halamanAktif <= 3) {
                                                $endPage = min($jumlahHalaman, 5);
                                            }
                                            if ($halamanAktif > $jumlahHalaman - 3) {
                                                $startPage = max(1, $jumlahHalaman - 4);
                                            }

                                            for ($i = $startPage; $i <= $endPage; $i++) : ?>
                                                <li class="page-item <?= $i == $halamanAktif ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <li class="page-item">
                                                <a class="page-link" href="?page=<?= min($jumlahHalaman, $halamanAktif + 1); ?>">Next</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
            <div class="p-3">
                <h5>Title</h5>
                <p>Sidebar content</p>
            </div>
        </aside>
        <!-- /.control-sidebar -->

        <!-- Main Footer -->
        <?php require_once '../partials/footer.php';  ?>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
</body>

</html>