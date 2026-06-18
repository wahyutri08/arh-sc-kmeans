<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: " . base_url('auth/login'));
    exit;
}

$cluster = query("SELECT DISTINCT
                  c.id_cluster,
                  c.nama_cluster,
                  np.nama_pc
                  FROM cluster c
                  LEFT JOIN nilai_cluster nc
                  ON c.id_cluster = nc.id_cluster
                  LEFT JOIN nama_pc np
                  ON nc.id_pc = np.id_pc
                  ORDER BY c.id_cluster");

$nama_pc = query("SELECT * FROM nama_pc");

$title = "Nilai Data Cluster";
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
                            <h1 class="m-0">Nilai Data Cluster</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
                                <li class="breadcrumb-item">Nilai Data</li>
                                <li class="breadcrumb-item active">Nilai Data Cluster</li>
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
                                <div class="card-body table-responsive">
                                    <table id="example2" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Nama Cluster</th>
                                                <th class="text-center">Nama PC</th>
                                                <?php $atribut = query("SELECT *FROM atribut"); ?>
                                                <?php foreach ($atribut as $atr) : ?>
                                                    <th class="text-center"><?= $atr["nama_atribut"]; ?></th>
                                                <?php endforeach ?>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($cluster as $cls) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $cls["id_cluster"]; ?></td>
                                                    <td><?= $cls["nama_cluster"]; ?></td>
                                                    <td><?= $cls["nama_pc"]; ?></td>
                                                    <?php foreach ($atribut as $row) : ?>
                                                        <td class="text-center">
                                                            <?php
                                                            $nilaiCluster = query("SELECT * FROM nilai_cluster WHERE id_cluster = " . $cls['id_cluster'] . " AND id_atribut = " . $row['id_atribut']);
                                                            if ($nilaiCluster) {
                                                                echo $nilaiCluster[0]['nilai'];
                                                            } else {
                                                                echo " ";
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                    <td class="text-center">
                                                        <div class="dropdown">
                                                            <button class="btn btn-danger btn-sm tombol-hapus"
                                                                data-id="<?= $cls['id_cluster']; ?>"
                                                                type="button">
                                                                <i class="far fa-trash-alt"></i>
                                                            </button>
                                                            <!-- <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                                                <li><a class="dropdown-item" href="<?= base_url('nilaiData/edit_nilaicluster/' . $cls['id_cluster']) ?>"><i class="fas fa-edit"></i> Edit</a></li>
                                                                <li><a class="dropdown-item tombol-hapus" href="<?= base_url('nilaiData/delete_nilaicluster/' . $cls['id_cluster']) ?>"><i class="far fa-trash-alt"></i> Delete</a></li>
                                                            </ul> -->
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
            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="nav-icon fas fa-table"></i>&nbsp; Nilai Data PC Editing</h3>
                                </div>
                                <div class="card-body table-responsive">
                                    <table id="example3" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Nama PC</th>
                                                <?php $atribut = query("SELECT *FROM atribut"); ?>
                                                <?php foreach ($atribut as $atr) : ?>
                                                    <th class="text-center"><?= $atr["nama_atribut"]; ?></th>
                                                <?php endforeach ?>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($nama_pc as $pc) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $pc["id_pc"]; ?></td>
                                                    <td><?= $pc["nama_pc"]; ?></td>
                                                    <?php foreach ($atribut as $row) : ?>
                                                        <td class="text-center">
                                                            <?php
                                                            $nilaiPc = query("SELECT * FROM nilai_pc WHERE id_pc = " . $pc['id_pc'] . " AND id_atribut = " . $row['id_atribut']);
                                                            if ($nilaiPc) {
                                                                echo $nilaiPc[0]['nilai'];
                                                            } else {
                                                                echo " ";
                                                            }
                                                            ?>
                                                        </td>
                                                    <?php endforeach; ?>
                                                    <td class="text-center">
                                                        <?php
                                                        $cekDipakai = query("
                                                            SELECT *
                                                            FROM nilai_cluster
                                                            WHERE id_pc = {$pc['id_pc']}
                                                            LIMIT 1
                                                        ");
                                                        ?>
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm <?= $cekDipakai ? 'btn-secondary' : 'btn-primary' ?> btn-tambah-cluster"
                                                            <?= $cekDipakai ? 'disabled' : '' ?>
                                                            data-id="<?= $pc['id_pc']; ?>"
                                                            data-nama="<?= $pc['nama_pc']; ?>">
                                                            <i class="fas fa-plus-circle"></i>
                                                        </button>
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
            $('#example3').DataTable({
                "paging": true,
                "lengthChange": true,
                "pageLength": 25,
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
            const id_cluster = $(this).data('id');
            Swal.fire({
                title: 'Apa Kamu Yakin?',
                text: "Data Akan Dihapus",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, Hapus Saja!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "<?= base_url('nilaiData/delete_nilaicluster') ?>",
                        type: "POST",
                        data: {
                            id_cluster: id_cluster
                        },
                        dataType: "json", // 🔥 penting
                        beforeSend: function() {
                            $('#pageLoader').show();
                        },
                        success: function(res) {
                            if (res.status === 'success') {

                                Swal.fire(
                                    'Dihapus!',
                                    'Data Berhasil Dihapus',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire('Error', res.message, 'error');
                            }
                        },
                        complete: function() {
                            $('#pageLoader').hide(); // 🔥 pasti hilang
                        },
                        error: function(xhr) {
                            console.log(xhr.responseText);
                            Swal.fire(
                                'Server Error',
                                'Check console for error',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    </script>
    <script>
        $(document).on('click', '.btn-tambah-cluster', function() {
            let id_pc = $(this).data('id');
            let nama_pc = $(this).data('nama');
            $.ajax({
                url: "<?= base_url('nilaiData/get_cluster_tujuan') ?>",
                type: "POST",
                success: function(response) {
                    let res = JSON.parse(response);
                    if (res.status == 'redirect') {
                        window.location.href = "<?= base_url('logout') ?>";
                        return;
                    }
                    if (res.status == 'error') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: res.message
                        });
                        return;
                    }
                    Swal.fire({
                        title: 'Konfirmasi',
                        text: 'Tambah "' + nama_pc + '" ke ' + res.nama_cluster + ' ?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "<?= base_url('nilaiData/add_to_cluster') ?>",
                                type: "POST",
                                data: {
                                    id_pc: id_pc,
                                    id_cluster: res.id_cluster
                                },
                                success: function(r) {
                                    let hasil = JSON.parse(r);
                                    if (hasil.status == 'redirect') {
                                        window.location.href = "<?= base_url('logout') ?>";
                                        return;
                                    }
                                    if (hasil.status == 'success') {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil',
                                            text: nama_pc + ' Berhasil Ditambahkan Ke ' + res.nama_cluster
                                        }).then(() => {
                                            location.reload();
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'Peringatan',
                                            text: hasil.message
                                        });
                                    }
                                }
                            });
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