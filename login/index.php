<?php
session_start();
require_once '../functions.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Cegah akses ke halaman ini jika pengguna sudah login
if (isset($_SESSION["login"]) && $_SESSION["login"] === true) {
    header("Location: ../dashboard");
    exit;
}

$error = '';

if (isset($_POST["login"])) {
    $usernameOremail = $_POST["username"];
    $password = $_POST["password"];

    // Query untuk mencari pengguna berdasarkan username atau email
    $result = mysqli_query($db, "SELECT * FROM users WHERE username = '$usernameOremail' OR email = '$usernameOremail'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Cek status pengguna
        if ($row['status'] === 'Aktif') {
            // Verifikasi password
            if (password_verify($password, $row["password"])) {
                // Jika login berhasil, set session
                $_SESSION["login"] = true;
                $_SESSION['nama'] = $row['nama'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['avatar'] = $row['avatar'];
                $_SESSION['role'] = $row['role'];
                header("Location: ../home");
                exit;
            } else {
                $error = 'Wrong Password.';
            }
        } else {
            $error = 'Your account is inactive. Please contact the admin.';
        }
    } else {
        $error = 'Username or Email not found.';
    }
}
?>

<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login</title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE 4 | Login Page v2" />
    <meta name="author" content="ColorlibHQ" />
    <meta
        name="description"
        content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance." />
    <meta
        name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant" />
    <!--end::Primary Meta Tags-->
    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="../css/adminlte.css" as="style" />
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/dist/img/logo/logo2.png">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/plugins/fontawesome-free/css/all.min.css">
    <!--end::Accessibility Features-->
    <!--begin::Fonts-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
        crossorigin="anonymous"
        media="print"
        onload="this.media='all'" />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../assets/V2/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex align-items-center">
                <img src="../assets/dist/img/logo/logo.png" alt="Logo" id="logo" class="brand-image img-circle" style="width: 50px; height: 50px;">
                <a href="../login" class="link-dark text-decoration-none ms-2">
                    <h3 class="mb-0"><b>BERSAUDARA PRINT</b></h3>
                </a>
            </div>

            <div class="card-body login-card-body">
                <?php if ($error) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $error ?>
                    </div>
                <?php endif; ?>
                <p class="login-box-msg">Sign in to start your session</p>
                <form action="" method="POST" id="myForm">
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input id="username" name="username" type="text" class="form-control" value="" placeholder="" required />
                            <label for="username">Username</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                    </div>
                    <div class="input-group mb-1">
                        <div class="form-floating">
                            <input id="password" name="password" type="password" class="form-control" placeholder="" required />
                            <label for="password">Password</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <!-- /.col -->
                        <div class="col-5 mt-3">
                            <div class="d-grid gap-2">
                                <button type="submit" name="login" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Sign In</button>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>
                <!-- /.social-auth-links -->

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="../assets/plugins/jquery/jquery.min.js"></script>
    <script
        src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="../assets/V2/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script src="../assets/plugins/sweetalert/sweetalert2.all.min.js"></script>
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
            if (sidebarWrapper && OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!-- Script cegah back -->
    <script>
        window.history.forward();

        function noBack() {
            window.history.forward();
        }
        window.onpageshow = function(evt) {
            if (evt.persisted) noBack();
        }
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>