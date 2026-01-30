<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        <?= $title ?? 'Dashboard' ?> | MONIKA
    </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <?= $this->renderSection('styles') ?>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= base_url('logout') ?>" role="button">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="<?= base_url('dashboard') ?>"
                class="brand-link d-flex align-items-center justify-content-between pb-3 pt-3">
                <div class="d-flex flex-column pl-2" style="line-height: 1;">
                    <span class="brand-text font-weight-light" style="font-size: 1.25rem;">MONIKA</span>
                    <span class="brand-text text-white-50"
                        style="font-size: 0.65rem; font-weight: 300; margin-top: 2px; letter-spacing: 0.5px;">MOnitoring
                        NIlai Kinerja & Anomali</span>
                </div>
                <img src="<?= base_url('assets/img/logo.svg') ?>" alt="Logo" class="brand-image float-none ml-2"
                    style="max-height: 38px; width: auto; opacity: .9; margin-right: .5rem;">
            </a>

            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="info">
                        <a href="#" class="d-block">
                            <?= session()->get('fullname') ?>
                        </a>
                    </div>
                </div>

                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">
                        <li class="nav-item">
                            <a href="<?= base_url('dashboard') ?>"
                                class="nav-link <?= (uri_string() == 'dashboard') ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>

                        <?php if (session()->get('id_role') == 1): ?>
                            <li class="nav-header">ADMINISTRATOR</li>
                            <li class="nav-item">
                                <a href="<?= base_url('admin/users') ?>"
                                    class="nav-link <?= (strpos(uri_string(), 'admin/users') !== false) ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Manajemen User</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('kegiatan') ?>"
                                    class="nav-link <?= (strpos(uri_string(), 'kegiatan') !== false) ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-calendar-alt"></i>
                                    <p>Master Kegiatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('monitoring') ?>"
                                    class="nav-link <?= (strpos(uri_string(), 'monitoring') !== false) ? 'active' : '' ?>">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>Monitoring & Evaluasi</p>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-header">DATA</li>
                        <li class="nav-item">
                            <a href="<?= base_url('dokumen') ?>"
                                class="nav-link <?= (strpos(uri_string(), 'dokumen') !== false) ? 'active' : '' ?>">
                                <i class="nav-icon fas fa-file-alt"></i>
                                <p>Dokumen Survei</p>
                            </a>
                        </li>

                        <?php if (session()->get('id_role') == 1): ?>
                            <li class="nav-header">LAPORAN</li>
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/pcl') ?>" class="nav-link">
                                    <i class="nav-icon fas fa-chart-line"></i>
                                    <p>Kinerja PCL</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('laporan/pengolahan') ?>" class="nav-link">
                                    <i class="nav-icon fas fa-chart-pie"></i>
                                    <p>Kinerja Pengolahan</p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <?= $page_title ?? 'Page Title' ?>
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <?= $this->renderSection('header_actions') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main content -->
            <div class="content">
                <div class="container-fluid">
                    <!-- Flash Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>

        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">Beta Version</div>
            <strong>Copyright &copy; 2026 <a href="#">Nanang Pamungkas</a>.</strong> All rights reserved.
        </footer>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>