<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
}

if (isset($_GET["id_cluster"]) && is_numeric($_GET["id_cluster"])) {
    $id_cluster = $_GET["id_cluster"];
} else {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}

$cluster = query("SELECT * FROM cluster WHERE id_cluster = $id_cluster");
if (empty($cluster)) {
    header("HTTP/1.1 404 Not Found");
    include("../errors/404.html");
    exit;
}
$cluster = $cluster[0];

$atribut = query("SELECT * FROM atribut");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST)) {
        echo json_encode(["status" => "error", "message" => "No Data Was Inputted"]);
        exit;
    }

    // Eksekusi dan cek hasil
    ob_start(); // untuk debug jika error
    $affected = dataPostnilaiCluster($_POST, $_GET);

    if ($affected > 0) {
        echo json_encode(["status" => "success", "message" => "Data Successfully Changed"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No Data Changed"]);
    }
    exit;
}

$title = "Edit Cluster - {$cluster['nama_cluster']}";
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
                            <h1 class="m-0">Edit Nilai Cluster</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
                                <li class="breadcrumb-item">Master Data</li>
                                <li class="breadcrumb-item">Data Cluster</li>
                                <li class="breadcrumb-item">Edit Nilai Cluster</li>
                                <li class="breadcrumb-item active"><?= $cluster["nama_cluster"]; ?></li>
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
                                    <h3 class="card-title"><?= $cluster["nama_cluster"]; ?></h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="quickForm">
                                    <input type="hidden" name="id_cluster" value="<?= $cluster["id_cluster"]; ?>">
                                    <div class="card-body">
                                        <?php foreach ($atribut as $row) : ?>
                                            <?php $nilaiCluster = query("SELECT * FROM nilai_cluster WHERE id_cluster = " . $cluster['id_cluster'] . " AND id_atribut = " . $row['id_atribut']); ?>
                                            <div class="form-group col-md-5">
                                                <label for="<?= $row["id_atribut"]; ?>"><?= $row["nama_atribut"]; ?>:</label>
                                                <input type="number" name="<?= $row["id_atribut"]; ?>" id="<?= $row["id_atribut"]; ?>" class="form-control" value="<?= $nilaiCluster ? $nilaiCluster[0]["nilai"] : ""; ?>" placeholder="Nilai">
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
                            Swal.fire('Error', 'Invalid server response', 'error');
                            return;
                        }

                        if (res.status === 'success') {
                            Swal.fire('Success', res.message, 'success').then(() => {
                                window.location.href = '<?= base_url('nilaiData/nilai_data_cluster') ?>';
                            });
                        } else {
                            Swal.fire('Failed', res.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Failed', 'An error occurred on the server', 'error');
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