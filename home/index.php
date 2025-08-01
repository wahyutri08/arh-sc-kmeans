<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
  header("Location: ../login");
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
  <title>Dashboard</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="icon" type="image/png" sizes="16x16" href="../assets/dist/img/logo/logo2.png">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
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
              <h1 class="m-0">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
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
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $totalPc; ?></h3>

                  <p>PC EDITING</p>
                </div>
                <div class="icon">
                  <i class="ion ion-laptop"></i>
                </div>
                <a href="#" class="small-box-footer">&nbsp;</a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $totalAtribut; ?></h3>

                  <p>ATRIBUT</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="#" class="small-box-footer">&nbsp;</a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3><?= $totalCluster; ?></h3>

                  <p>CLUSTER</p>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">&nbsp;</a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?= $totalUsers; ?></h3>

                  <p>USERS</p>
                </div>
                <div class="icon">
                  <i class="ion ion-person-add"></i>
                </div>
                <a href="#" class="small-box-footer">&nbsp;</a>
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
                  <div class="card shadow-sm" style="height : 19rem; background-color: #FFFFFF; background-position: calc(100% + 1rem) bottom; background-size: 30% auto; background-repeat: no-repeat; background-image: url(../assets/dist/img/rhone.svg);">
                    <div class=" px-4 mt-4">
                      <h4 class="text-primary"> <b>Selamat Datang, <?= $user["nama"]; ?></b> </h4>
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
  <!-- Sweetalert -->
  <script src="../assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
  <script src="../assets/plugins/jslogout/logoutsweetalert.js"></script>
  <!-- AdminLTE App -->
  <script src="../assets/dist/js/adminlte.min.js"></script>
  <!-- Sidebar JS -->
  <script src="../assets/js/sidebar.js"></script>
</body>

</html>