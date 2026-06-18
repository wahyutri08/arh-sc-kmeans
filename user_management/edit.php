<?php
session_start();
include_once("../auth_check.php");
if (!isset($_SESSION["login"]) || $_SESSION["login"] !== true) {
    header("Location: " . base_url('auth/login'));
    exit;
}

if ($_SESSION['role'] !== 'Admin') {
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);
    exit;
}

if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = (int)$_GET["id"];
} else {
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);
    exit;
}

$users = query("SELECT * FROM users WHERE id = $id");
if (empty($users)) {
    header("HTTP/1.1 404 Not Found");
    http_response_code(404);
    exit;
}

$users = $users[0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil nilai yang dikirimkan untuk username baru
    $newUsername = $_POST["username"];

    // Lakukan pemeriksaan dengan database
    $query = "SELECT username FROM users WHERE username = '$newUsername'";
    $result = mysqli_query($db, $query);

    // Jika username yang dikirim sudah ada di database selain username saat ini, tampilkan pesan kesalahan
    if (mysqli_num_rows($result) > 0 && $newUsername !== $users["username"]) {
        echo json_encode(["status" => "error", "message" => "Username already exists. Please choose another username."]);
    } else {
        // Lanjutkan dengan pembaruan data jika tidak ada masalah
        $result = editUsers($_POST);
        if ($result > 0) {

            // Update session data dengan data baru
            $_SESSION['user_data']['username'] = $_POST['username'];
            $_SESSION['user_data']['nama'] = $_POST['nama'];
            $_SESSION['user_data']['email'] = $_POST['email'];
            $_SESSION['user_data']['role'] = $_POST['role'];
            // $_SESSION['user_data']['avatar'] = $_POST['avatar'];

            echo json_encode(["status" => "success", "message" => "Data Successfully Changed"]);
        } elseif ($result == -1) {
            echo json_encode(["status" => "error", "message" => "Non-Image File Format"]);
        } elseif ($result == -2) {
            echo json_encode(["status" => "error", "message" => "Image Size Too Large"]);
        } elseif ($result == -3) {
            echo json_encode(["status" => "error", "message" => "Confirm Password Invalid"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Data Failed to Change"]);
        }
    }
    exit;
}

$title = "Edit User - {$users['nama']}";
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
                            <h1 class="m-0">Edit Users</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('home') ?>">Home</a></li>
                                <li class="breadcrumb-item">User Management</li>
                                <li class="breadcrumb-item">Edit</li>
                                <li class="breadcrumb-item active"><?= $users["nama"]; ?></li>
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
                            <div class="card card-warning">
                                <div class="card-header">
                                    <span class="nav-icon fas fa-user"></span> &nbsp;<?= htmlspecialchars($users["nama"]); ?>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form method="POST" action="" enctype="multipart/form-data" id="quickForm">
                                    <input type="hidden" name="id" value="<?= htmlspecialchars($users["id"]); ?>">
                                    <input type="hidden" name="avatarLama" value="<?= htmlspecialchars($users["avatar"]); ?>">
                                    <div class="card-body">
                                        <div class="form-group col-md-5">
                                            <label for="username">Username:</label>
                                            <input type="text" name="username" class="form-control" id="username" placeholder="Username" value="<?= htmlspecialchars($users["username"]); ?>">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="nama">Nama:</label>
                                            <input type="text" name="nama" class="form-control" id="nama" placeholder="Nama" value="<?= htmlspecialchars($users["nama"]); ?>">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="email">Email:</label>
                                            <input type="email" name="email" class="form-control" id="email" placeholder="Email" value="<?= htmlspecialchars($users["email"]); ?>">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="password">Password:</label>
                                            <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="password2">Confirm Password:</label>
                                            <input type="password" name="password2" class="form-control" id="password2" placeholder="Confirm Password">
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label>Role <span class="text-danger">*</span></label>
                                            <select class="custom-select form-control" id="role" name="role">
                                                <option value="Admin" <?= (htmlspecialchars($users["role"]) == "Admin") ? "selected" : "" ?>>Admin</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label>Status <span class="text-danger">*</span></label>
                                            <select class="custom-select form-control" id="status" name="status">
                                                <option value="Aktif" <?= (htmlspecialchars($users["status"]) == "Aktif") ? "selected" : "" ?>>Aktif</option>
                                                <option value="Tidak Aktif" <?= (htmlspecialchars($users["status"]) == "Tidak Aktif") ? "selected" : "" ?>>Tidak Aktif</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-5">
                                            <label for="avatar">Photo Profile</label>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" class="form-control" name="avatar" id="avatar">
                                                    <label class="custom-file-label" for="avatar">Choose file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-warning"><i class="fas fa-solid fa-check"></i> Save Change</button>
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
            bsCustomFileInput.init();
        });
    </script>
    <script>
        $(function() {
            // Inisialisasi validasi jQuery
            $('#quickForm').validate({
                rules: {
                    username: {
                        required: true
                    },
                    nama: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    password: {
                        required: true
                    },
                    password2: {
                        required: true
                    },
                    role: {
                        required: true
                    },
                    status: {
                        required: true
                    }
                },
                messages: {
                    username: {
                        required: "Please enter an Username"
                    },
                    nama: {
                        required: "Please enter an Nama"
                    },
                    email: {
                        required: "Please enter an Email"
                    },
                    password: {
                        required: "Please enter an Password"
                    },
                    password2: {
                        required: "Please enter an Confirm Password"
                    },
                    role: {
                        required: "Please enter an Role"
                    },
                    status: {
                        required: "Please enter an Status"
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
                                window.location.href = '<?= base_url('user_management/users') ?>';
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