<?php
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('HTTP/1.1 403 Forbidden');
    include("../errors/403.html");
    exit();
}

function currentPath()
{
    return trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
}

function currentPage()
{
    return basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
}

function isModule($module)
{
    return strpos(currentPath(), $module) !== false;
}

function pathContains($paths = [])
{
    $current = currentPath();

    foreach ($paths as $path) {
        if (strpos($current, $path) !== false) {
            return true;
        }
    }

    return false;
}

// sidebar.php (partial) — tidak perlu logic aktif di PHP, aktif handle by JS!
$id = $_SESSION["id"];
$role = $_SESSION["role"];
$user = query("SELECT * FROM users WHERE id = $id")[0];
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('home') ?>" class="brand-link">
        <img src="<?= base_url('assets/dist/img/logo/logo2.png') ?>" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="h6 ml-1">BERSAUDARA PRINT</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('assets/dist/img/profile/' . htmlspecialchars($user['avatar'])); ?>" class="brand-image img-circle elevation-3" alt="User Image">
            </div>
            <div class="info">
                <a href="<?= base_url('home') ?>" class="d-block ml-1 h6"><span><?= htmlspecialchars($user["nama"]); ?></span></a>
            </div>
        </div>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('home') ?>" class="nav-link <?= isModule('home') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- Master Data Menu -->
                <li class="nav-item has-treeview <?= isModule('master') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= isModule('master') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>
                            Master Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('master/atribut') ?>"
                                class="nav-link <?= pathContains([
                                                    'master/atribut',
                                                    'master/add_atribut',
                                                    'master/edit_atribut'
                                                ]) ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Atribut</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('master/pc_editing') ?>"
                                class="nav-link <?= pathContains([
                                                    'master/pc_editing',
                                                    'master/add_pc',
                                                    'master/edit_pc'
                                                ]) ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data PC Editing</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('master/cluster') ?>"
                                class="nav-link <?= pathContains([
                                                    'master/cluster',
                                                    'master/add_cluster',
                                                    'master/edit_cluster'
                                                ]) ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Data Cluster</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview <?= isModule('nilaiData') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= isModule('nilaiData') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Nilai Data
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('nilaiData/nilai_data_pc') ?>"
                                class="nav-link <?= pathContains([
                                                    'nilaiData/nilai_data_pc',
                                                    'nilaiData/edit_nilaipc'
                                                ]) ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Data PC</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('nilaiData/nilai_data_cluster') ?>"
                                class="nav-link <?= pathContains([
                                                    'nilaiData/nilai_data_cluster',
                                                    'nilaiData/edit_nilaicluster'
                                                ]) ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Nilai Data Cluster</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">PERHITUNGAN</li>
                <li class="nav-item has-treeview <?= isModule('proses') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= isModule('proses') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Proses
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('proses/iterasi') ?>"
                                class="nav-link <?= pathContains([
                                                    'proses/iterasi'
                                                ]) ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Iterasi</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-header">LAPORAN</li>
                <li class="nav-item">
                    <a href="<?= base_url('laporan/hasil_perhitungan') ?>"
                        class="nav-link <?= pathContains([
                                            'laporan/hasil_perhitungan',
                                            'laporan/detail'
                                        ]) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Hasil Perhitungan</p>
                    </a>
                </li>
                <li class="nav-header">SETTINGS</li>
                <li class="nav-item has-treeview <?= isModule('account') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= isModule('account') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Account
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('account/profile') ?>"
                                class="nav-link
                                <?= pathContains([
                                    'account/profile'
                                ]) ? 'active' : '' ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('account/change_password') ?>"
                                class="nav-link
                                <?= pathContains([
                                    'account/change_password'
                                ]) ? 'active' : '' ?>">
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