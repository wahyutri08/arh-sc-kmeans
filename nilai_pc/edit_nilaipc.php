<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

if (isset($_GET["id_pc"]) && is_numeric($_GET["id_pc"])) {
    $id_pc = $_GET["id_pc"];
} else {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

$nama_pc = query("SELECT * FROM nama_pc WHERE id_pc = $id_pc");
if (empty($nama_pc)) {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}
$nama_pc = $nama_pc[0];

$atribut = query("SELECT * FROM atribut");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST)) {
        echo json_encode(["status" => "error", "message" => "Tidak ada data yang diinput"]);
        exit;
    }

    // Eksekusi dan cek hasil
    ob_start(); // untuk debug jika error
    $affected = dataPostnilaiPC($_POST, $_GET);

    if ($affected > 0) {
        echo json_encode(["status" => "success", "message" => "Data Berhasil Diubah"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Tidak ada data yang diubah"]);
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
    <title>Edit Nilai PC - <?= $nama_pc["nama_pc"]; ?></title>

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
                            <h1 class="m-0">Edit PC Editing</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item">Master Data</li>
                                <li class="breadcrumb-item">Data PC Editing</li>
                                <li class="breadcrumb-item">Edit PC Editing</li>
                                <li class="breadcrumb-item active"><?= $nama_pc["nama_pc"]; ?></li>
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
                                    <h3 class="card-title"><?= $nama_pc["nama_pc"]; ?></h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="quickForm">
                                    <input type="hidden" name="id_pc" value="<?= $nama_pc["id_pc"]; ?>">
                                    <div class="card-body">
                                        <?php foreach ($atribut as $row) : ?>
                                            <?php $nilaiPC = query("SELECT * FROM nilai_pc WHERE id_pc = " . $nama_pc['id_pc'] . " AND id_atribut = " . $row['id_atribut']); ?>
                                            <div class="form-group col-md-5">
                                                <label for="<?= $row["id_atribut"]; ?>"><?= $row["nama_atribut"]; ?>:</label>
                                                <input type="number" name="<?= $row["id_atribut"]; ?>" id="<?= $row["id_atribut"]; ?>" class="form-control" value="<?= $nilaiPC ? $nilaiPC[0]["nilai"] : ""; ?>" placeholder="Nilai">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" name="submit" class="btn btn-success"><i class="fas fa-solid fa-check"></i> Save Change</button>
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
    <!-- jquery-validation -->
    <script src="../assets/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../assets/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Sweetalert -->
    <script src="../assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/dist/js/adminlte.min.js"></script>
    <!-- jQuery Validation + AJAX Submit -->
    <script>
        $(function() {
            $('#quickForm').validate({
                rules: {
                    <?php
                    $last = end($atribut);
                    foreach ($atribut as $row):
                    ?> "<?= $row['id_atribut']; ?>": {
                            required: true
                        }
                        <?= $row !== $last ? ',' : '' ?>
                    <?php endforeach; ?>
                },
                messages: {
                    <?php
                    $last = end($atribut);
                    foreach ($atribut as $row):
                    ?> "<?= $row['id_atribut']; ?>": {
                            required: "Nilai <?= addslashes($row['nama_atribut']); ?> wajib diisi"
                        }
                        <?= $row !== $last ? ',' : '' ?>
                    <?php endforeach; ?>
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

            $('#quickForm').on('submit', function(e) {
                e.preventDefault();
                if (!$(this).valid()) return;

                $.ajax({
                    url: '',
                    type: 'POST',
                    data: new FormData(this),
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        let res;
                        try {
                            res = JSON.parse(response);
                        } catch (e) {
                            Swal.fire('Error', 'Respon server tidak valid', 'error');
                            return;
                        }

                        if (res.status === 'success') {
                            Swal.fire('Berhasil', res.message, 'success').then(() => {
                                window.location.href = '../nilai_pc';
                            });
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Gagal', 'Terjadi kesalahan pada server', 'error');
                    }
                });
            });
        });
    </script>


</body>

</html>