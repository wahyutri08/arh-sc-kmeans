<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

$query = mysqli_query($db, "SELECT MAX(id_pc) AS max_id FROM nama_pc");
$data = mysqli_fetch_assoc($query);
$next_id = $data['max_id'] + 1;
if (!$next_id) {
    $next_id = 1; // jika belum ada data sama sekali
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $result = addPC($_POST);
    if ($result > 0) {
        echo json_encode(["status" => "success", "message" => "Data Added Successfully"]);
    } elseif ($result == -1) {
        echo json_encode(["status" => "error", "message" => "PC Name Already Exists"]);
    } elseif ($result == -2) {
        echo json_encode(["status" => "error", "message" => "PC ID Already Exists"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Data Failed to Change"]);
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
    <title>Tambah Atribut</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/dist/img/logo/logo2.png">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/dist/css/adminlte.min.css">
    <style>
        .overlay {
            position: fixed;
            /* penting: supaya menempel di layar */
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* tengah secara vertikal */
            align-items: center;
            /* tengah secara horizontal */
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="overlay-wrapper" id="pageLoader">
        <div class="overlay"><i class="fas fa-3x fa-sync-alt fa-spin"></i>
            <div class="text-bold pt-2">Processing...</div>
        </div>
    </div>
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
                            <h1 class="m-0">Tambah PC</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item">Master Data</li>
                                <li class="breadcrumb-item">Data PC Editing</li>
                                <li class="breadcrumb-item active">Tambah PC Editing</li>
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
                                    <h3 class="card-title">Tambah Data PC Editing</h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="quickForm">
                                    <div class="card-body">
                                        <div class="form-group col-md-5">
                                            <label for="id_pc">ID PC:</label>
                                            <input type="number" name="id_pc" class="form-control" id="id_pc" placeholder="ID PC" value="<?= $next_id ?>" readonly required>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="nama_pc">Nama PC:</label>
                                            <input type="text" name="nama_pc" class="form-control" id="nama_pc" placeholder="Nama PC">
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-solid fa-check"></i> Submit</button>
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
        </div>
        <!-- /.content-wrapper -->

        <!-- Control Sidebar -->
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
    <!-- jquery-validation -->
    <script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../assets/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Sweetalert -->
    <script src="../assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script src="../assets/plugins/jslogout/logoutsweetalert.js"></script>
    <!-- DarkMode -->
    <script src="../assets/dist/js/darkmode.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- Sidebar JS -->
    <script src="../assets/js/sidebar.js"></script>
    <!-- jQuery Validation + AJAX Submit -->
    <script>
        $(function() {
            // Inisialisasi validasi jQuery
            $('#quickForm').validate({
                rules: {
                    id_pc: {
                        required: true
                    },
                    nama_pc: {
                        required: true
                    }
                },
                messages: {
                    id_pc: {
                        required: "Please enter an ID PC"
                    },
                    nama_pc: {
                        required: "Please enter a Nama PC"
                    }
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
                }
            });

            // Submit dengan AJAX hanya jika valid
            $('#quickForm').on('submit', function(e) {
                e.preventDefault();

                if (!$(this).valid()) return; // Stop jika form tidak valid

                $.ajax({
                    url: '', // Ganti dengan URL aksi jika perlu
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let res;
                        try {
                            res = JSON.parse(response);
                        } catch (e) {
                            Swal.fire('Error', 'Invalid Server Response', 'error');
                            return;
                        }

                        if (res.status === 'success') {
                            Swal.fire({
                                title: "Success",
                                text: res.message,
                                icon: "success"
                            }).then(() => {
                                window.location.href = '../nama_pc';
                            });
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'An Error Occurred on the Server', 'error');
                    }
                });
            });
        });
    </script>
    <script>
        $(window).on('load', function() {
            $('#pageLoader').fadeOut(250);
        });
    </script>
</body>

</html>