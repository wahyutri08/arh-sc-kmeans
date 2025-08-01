<?php
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.1 403 Forbidden');
    include("../errors/404.html");
    exit();
}
// sidebar.php (partial) — tidak perlu logic aktif di PHP, aktif handle by JS!
$id = $_SESSION["id"];
$role = $_SESSION["role"];
$user = query("SELECT * FROM users WHERE id = $id")[0];
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../home" class="brand-link">
        <img src="../assets/dist/img/logo/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="h6 ml-1">BERSAUDARA PRINT</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../assets/dist/img/profile/<?= $user['avatar']; ?>" class="brand-image img-circle elevation-3" alt="User Image">
            </div>
            <div class="info">
                <a href="../home" class="d-block ml-1 h6"><span><?= $user["nama"]; ?></span></a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="../home" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- Master Data Menu -->
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Master Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../atribut" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Atribut</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../nama_pc" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data PC Editing</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../cluster" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Cluster</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Nilai Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../nilai_pc" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Data PC</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../nilai_cluster" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Cluster</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">PERHITUNGAN</li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Proses
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../iterasi" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Iterasi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">LAPORAN</li>
                <li class="nav-item">
                    <a href="../laporan" class="nav-link">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Hasil Perhitungan</p>
                    </a>
                </li>
                <li class="nav-header">SETTINGS</li>
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Account
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../profile" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../change_password" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="../user_management" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>User Management</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../logout" class="nav-link" id="btnLogout">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>