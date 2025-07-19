<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

$id = $_SESSION["id"];
$users = query("SELECT * FROM users WHERE id = $id")[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = editProfile($_POST);
    if ($result > 0) {
        echo json_encode(["status" => "success", "message" => "Data Berhasil Diubah"]);
    } elseif ($result == -1) {
        echo json_encode(["status" => "error", "message" => "File Bukan Format Gambar"]);
    } elseif ($result == -2) {
        echo json_encode(["status" => "error", "message" => "Ukuran Gambar Terlalu Besar"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data Gagal Diubah"]);
    }
    exit;
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
    <title>Profile - <?= $users["nama"]; ?></title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!-- <link href="../assets/plugins/fontawesome-free/css/fontawesome.css" rel="stylesheet" />
  <link href="../assets/plugins/fontawesome-free/css/brands.css" rel="stylesheet" />
  <link href="../assets/plugins/fontawesome-free/css/solid.css" rel="stylesheet" /> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php require_once '../partials/navbar.php' ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php require_once '../partials/sidebar.php' ?>
        <!-- End Sidebar -->

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Profile</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item">Settings</li>
                                <li class="breadcrumb-item">Profile</li>
                                <li class="breadcrumb-item active"><?= $users["nama"]; ?></li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">

                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="../assets/dist/img/<?= $users["avatar"]; ?>"
                                            style="width: 150px; height: 140px;">
                                    </div>
                                    <h3 class="profile-username text-center"><?= $users["nama"]; ?></h3>
                                    <p class="text-muted text-center"><?= $users["role"]; ?></p>
                                </div>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                        <div class="col-md-9">
                            <div class="card card-primary card-outline">
                                <div class="card-header p-2">
                                    <ul class="nav nav-pills">
                                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a></li>
                                    </ul>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="settings">
                                            <form class="form-horizontal" action="" method="POST" enctype="multipart/form-data" id="myForm">
                                                <input type="hidden" name="id" value="<?= $users["id"]; ?>">
                                                <input type="hidden" name="avatarLama" value="<?= $users["avatar"]; ?>">
                                                <div class="form-group row">
                                                    <label for="username" class="col-sm-2 col-form-label">Username <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" placeholder="Username" value="<?= $users["username"]; ?>" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="email" class="col-sm-2 col-form-label">Email <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= $users["email"]; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="nama" class="col-sm-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Name" value="<?= $users["nama"]; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="avatar" class="col-sm-2 col-form-label">Photo Profile</label>
                                                    <div class="input-group col-sm-10">
                                                        <div class="custom-file">
                                                            <input type="file" class="form-control" name="avatar" id="avatar">
                                                            <label class="custom-file-label" for="avatar">Choose file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text">Upload</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="offset-sm-2 col-sm-10">
                                                        <button type="submit" name="submit" class="btn btn-success float-right"><i class="fa-solid fa-check"></i> Save</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!-- /.tab-content -->
                                </div><!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        <?php require_once '../partials/footer.php' ?>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- bs-custom-file-input -->
    <script src="../assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- Sweetalert -->
    <script src="../assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script>
        $(function() {
            bsCustomFileInput.init();
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#myForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        const res = JSON.parse(response);
                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Success",
                                text: res.message,
                                icon: "success"
                            }).then(() => {
                                window.location.href = '../dashboard';
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Terjadi kesalahan pada server', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>