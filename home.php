<?php
session_start();
include_once("auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: " . base_url('auth/login'));
    exit;
}

$id = $_SESSION["id"];
$role = $_SESSION['role'];
$user = query("SELECT * FROM users WHERE id = $id")[0];

$query = query("
        SELECT 
            (SELECT COUNT(*) FROM nama_pc) AS total_pc,
            (SELECT COUNT(*) FROM atribut) AS total_atribut,
            (SELECT COUNT(*) FROM cluster) AS total_cluster,
            (SELECT COUNT(*) FROM users) AS total_users
    ");

$totalPc = $query[0]['total_pc'];
$totalAtribut = $query[0]['total_atribut'];
$totalCluster = $query[0]['total_cluster'];
$totalUsers = $query[0]['total_users'];

$title = "Dashboard";
require_once 'partials/header.php';
?>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <?php include 'partials/overlay.php'; ?>
    <div class="wrapper">

        <!-- Navbar -->
        <?php require_once 'partials/navbar.php'; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once 'partials/sidebar.php'; ?>
        <!-- /.sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
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
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?= $totalAtribut; ?></h3>

                                    <p>ATRIBUT</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="<?= base_url('master/atribut') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $totalPc; ?></h3>

                                    <p>PC EDITING</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-laptop"></i>
                                </div>
                                <a href="<?= base_url('master/pc_editing') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3><?= $totalCluster; ?></h3>

                                    <p>CLUSTER</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="<?= base_url('master/cluster') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $totalUsers; ?></h3>

                                    <p>User Registrations</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="<?= base_url('user_management/users') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                </div><!-- /.container-fluid -->
            </div>
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 py-2">
                                    <div class="card shadow-sm" style="height : 19rem; background-color: #FFFFFF; background-position: calc(100% + 1rem) bottom; background-size: 30% auto; background-repeat: no-repeat; background-image: url(assets/dist/img/rhone.svg);">
                                        <div class=" px-4 mt-4">
                                            <h4 class="text-primary"> <b>Selamat Datang, <?= htmlspecialchars($user["nama"]); ?></b> </h4>
                                            <h4 class="text-black-50 mb-0">IMPLEMENTASI DATA MINING DALAM PENGREKOMENDASIAN DESKTOP PC TERBAIK</h4>
                                            <h4 class="text-black-50 mb-0">UNTUK KEBUTUHAN EDITING DENGAN METODE ALGORITMA K-MEANS</h4>
                                            <h4 class="text-black-50 mb-0">(STUDI KASUS : BERSAUDARA PRINT)</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <!-- Main Footer -->
        <?php require_once 'partials/footer.php';  ?>
        <!-- Control Sidebar -->
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- REQUIRED SCRIPTS -->
    <?php require_once 'partials/scripts.php'; ?>
</body>

</html>