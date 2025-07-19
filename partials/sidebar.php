<?php
// Mendapatkan halaman saat ini dari URL
$current_page = basename($_SERVER['REQUEST_URI']);

// Halaman-halaman yang berada di dalam Master Data
$master_data_pages = ['atribut', 'nama_pc', 'cluster'];
$keputusan = ['proses', 'penilaian'];
$settings_page = ['profile', 'change_password'];
$id = $_SESSION["id"];
$role = $_SESSION["role"];
$user = query("SELECT * FROM users WHERE id = $id")[0];
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="../dashboard" class="brand-link">
        <img src="../assets/dist/img/logo/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="h6 ml-1">BERSAUDARA PRINT</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../assets/dist/img/<?= $user['avatar']; ?>" class="brand-image img-circle elevation-3" alt="User Image">
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
                    <a href="../home" class="nav-link <?= ($current_page == 'home' || $current_page == 'index.php') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Master Data Menu -->
                <li class="nav-item <?= (in_array($current_page, $master_data_pages)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($current_page, $master_data_pages)) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Master Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../atribut" class="nav-link <?= ($current_page == 'atribut' ? 'active' : '') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Atribut</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../nama_pc" class="nav-link <?= ($current_page == 'nama_pc' ? 'active' : '') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data PC Editing</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../cluster" class="nav-link <?= ($current_page == 'cluster' ? 'active' : '') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Cluster</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-header">PROSES</li> -->
                <li class="nav-item <?= (in_array($current_page, $keputusan)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($current_page, $keputusan)) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Nilai Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../nilai_pc" class="nav-link <?= ($current_page == 'nilai_pc') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Data PC</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../nilai_cluster" class="nav-link <?= ($current_page == 'nilai_cluster') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Cluster</p>
                            </a>
                        </li>
                    </ul>
                </li> <!-- ← ini tag penutup yang benar -->

                <li class="nav-header">PERHITUNGAN</li>
                <li class="nav-item <?= (in_array($current_page, $keputusan)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($current_page, $keputusan)) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Proses
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../iterasi" class="nav-link <?= ($current_page == 'iterasi') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Iterasi</p>
                            </a>
                        </li>
                    </ul>
                </li> <!-- ← ini tag penutup yang benar -->

                <!-- Laporan Hasil Analisa -->
                <li class="nav-header">LAPORAN</li>
                <li class="nav-item">
                    <a href="../hasil" class="nav-link <?= ($current_page == 'hasil') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Hasil Perhitungan</p>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-header">SETTINGS</li>
                <li class="nav-item <?= (in_array($current_page, $settings_page)) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (in_array($current_page, $settings_page)) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Account
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="../profile" class="nav-link <?= ($current_page == 'profile') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../change_password" class="nav-link <?= ($current_page == 'change_password') ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- User Management -->
                <?php
                if ($user['role'] == 'Admin') {
                    echo '
                    <li class="nav-item">
                        <a href="../user_management" class="nav-link ' . ($current_page == 'user_management' ? 'active' : '') . '">
                            <i class="nav-icon fas fa-users"></i>
                            <p>User Management</p>
                        </a>
                    </li>
                    ';
                } else {
                    echo '';
                }
                ?>
                <li class="nav-item">
                    <a href="../logout" class="nav-link">
                        <i class="nav-icon fas fa-power-off"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>