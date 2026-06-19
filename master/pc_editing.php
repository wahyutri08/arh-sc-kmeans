<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: " . base_url('auth/login'));
    exit;
}

$nama_pc = query("SELECT * FROM nama_pc");
$title = "Data PC Editing";
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
                            <h1 class="m-0">Data PC Editing</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
                                <li class="breadcrumb-item">Master Data</li>
                                <li class="breadcrumb-item active">Data PC Editing</li>
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
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title"><a href="<?= base_url('master/add_pc') ?>" class="btn btn-sm btn-block bg-gradient-success"><i class="fas fa-plus"></i> Tambah Data</a></h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive">
                                    <table id="example2" class="table table-bordered table-hover ">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Nama PC</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($nama_pc as $pc) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $pc["id_pc"]; ?></td>
                                                    <td><?= $pc["nama_pc"]; ?></td>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-expanded="false">
                                                                Action
                                                            </button>
                                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li><a class="dropdown-item" href="<?= base_url('master/edit_pc/' . $pc['id_pc']) ?>"><i class="fas fa-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item tombol-hapus" href="<?= base_url('master/delete_pc/' . $pc['id_pc']) ?>"><i class="far fa-trash-alt"></i> Delete</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </div>
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
    <script>
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "pageLength": 100,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": false,
            });
        });
    </script>
    <script>
        $(document).on('click', '.tombol-hapus', function(e) {
            e.preventDefault();
            let href = $(this).attr('href');
            Swal.fire({
                title: 'Apa Anda Yakin?',
                text: 'Data Akan Dihapus',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#pageLoader').show();
                    $.ajax({
                        url: href,
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            if (res.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: 'Data Berhasil Dihapus'
                                }).then(() => {
                                    location.reload();
                                });
                            } else if (res.status === 'redirect') {
                                window.location.href = '<?= base_url("auth/login") ?>';
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: res.message || 'Data Gagal Dihapus'
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            Swal.fire({
                                icon: 'error',
                                title: 'Server Error',
                                text: 'Terjadi Kesalahan Pada Server'
                            });
                        },
                        complete: function() {
                            $('#pageLoader').hide();
                        }
                    });
                }
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