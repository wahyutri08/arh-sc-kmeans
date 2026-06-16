<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: ../login");
    exit;
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
            <div id="hasilIterasi"></div>
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
            // Sembunyikan overlay saat halaman pertama dibuka
            $('.overlay-wrapper .overlay').hide();
            // ==========================
            // Function Inisialisasi DataTable
            // ==========================
            function initDataTables() {
                $('#hasilIterasi table').each(function() {
                    if ($.fn.DataTable.isDataTable(this)) {
                        return;
                    }
                    let table = $(this).DataTable({
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
                        buttons: [
                            "excel",
                            "print",
                            "colvis"
                        ]
                    });

                    table.buttons().container()
                        .appendTo(
                            $(this)
                            .closest('.dataTables_wrapper')
                            .find('.col-md-6:eq(0)')
                        );
                });
            }
            // ==========================
            // Validasi Form
            // ==========================
            $('#quickForm').validate({
                rules: {
                    iterasi: {
                        required: true,
                        min: 1
                    }
                },
                messages: {
                    iterasi: {
                        required: "Masukkan Jumlah Iterasi",
                        min: "Iterasi Minimal 1"
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
                },
                submitHandler: function(form) {
                    // Bersihkan hasil lama
                    $('#hasilIterasi').html('');
                    // Loading SweetAlert
                    Swal.fire({
                        title: 'Processing...',
                        html: 'Sedang Melakukan Proses Clustering K-Means',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    $('.overlay-wrapper .overlay').show();
                    $.ajax({
                        url: '<?= base_url("ajax/proses_iterasi") ?>',
                        type: 'POST',
                        data: $(form).serialize(),
                        dataType: 'json',
                        timeout: 300000, // 5 menit
                        success: function(res) {
                            Swal.close();
                            $('.overlay-wrapper .overlay').hide();
                            console.log(res);
                            if (typeof res !== 'object') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Response Server Tidak Valid'
                                });
                                return;
                            }
                            if (res.redirect) {
                                window.location.href = res.url;
                                return;
                            }
                            if (!res.status) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Peringatan',
                                    text: res.message
                                });
                                return;
                            }
                            // Hapus DataTable lama
                            $('#hasilIterasi table').each(function() {
                                if ($.fn.DataTable.isDataTable(this)) {
                                    $(this).DataTable().destroy();
                                }
                            });
                            // Tampilkan hasil
                            $('#hasilIterasi').html(res.html);
                            // Inisialisasi ulang DataTable
                            initDataTables();
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                confirmButtonColor: '#3085d6',
                                text: 'Proses Clustering Selesai Pada Iterasi Ke-' + res.iteration
                            });
                        },

                        error: function(xhr, status, error) {

                            Swal.close();
                            $('.overlay-wrapper .overlay').hide();

                            console.log('STATUS :', status);
                            console.log('ERROR  :', error);
                            console.log('RESPONSE :', xhr.responseText);

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: '<b>Status:</b> ' + status +
                                    '<br><b>Error:</b> ' + error
                            });
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</body>

</html>