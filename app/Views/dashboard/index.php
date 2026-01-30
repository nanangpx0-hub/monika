<?= $this->extend('layout/dashboard') ?>

<?= $this->section('header_actions') ?>
    <form method="get" action="">
        <div class="form-group row float-sm-right">
            <div class="col-auto">
                <select name="kegiatan" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Semua Kegiatan --</option>
                    <?php foreach($list_kegiatan as $k): ?>
                        <option value="<?= $k['id_kegiatan'] ?>" <?= ($selected_kegiatan == $k['id_kegiatan']) ? 'selected' : '' ?>>
                            <?= $k['nama_kegiatan'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </form>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3><?= $stat_total ?></h3>
            <p>Total Dokumen Masuk</p>
          </div>
          <div class="icon">
            <i class="fas fa-file"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3><?= $stat_entry ?></h3>
            <p>Sudah Entry</p>
          </div>
          <div class="icon">
            <i class="fas fa-check-circle"></i>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3><?= $stat_error ?></h3>
            <p>Dokumen Error / Anomali</p>
          </div>
          <div class="icon">
            <i class="fas fa-exclamation-triangle"></i>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-6">
        <div class="card card-danger card-outline">
          <div class="card-header">
            <h5 class="card-title m-0">Top 5 Petugas Error Terbanyak</h5>
          </div>
          <div class="card-body p-0">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama PCL</th>
                        <th>Jumlah Error</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($ranking)): ?>
                        <tr><td colspan="3" class="text-center">Belum ada data error.</td></tr>
                    <?php else: ?>
                        <?php foreach($ranking as $i => $r): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $r['fullname'] ?></td>
                            <td><span class="badge badge-danger"><?= $r['error_count'] ?></span></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <div class="col-lg-6">
         <div class="card card-primary card-outline">
            <div class="card-header">
                <h5 class="card-title m-0">Informasi</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Selamat datang di sistem <strong>MONIKA</strong>. Gunakan filter di pojok kanan atas untuk melihat statistik spesifik per Kegiatan Survei.</p>
            </div>
         </div>
      </div>
    </div>
<?= $this->endSection() ?>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
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
    <a href="<?= base_url('dashboard') ?>" class="brand-link d-flex align-items-center justify-content-between pb-3 pt-3">
      <div class="d-flex flex-column pl-2" style="line-height: 1;">
        <span class="brand-text font-weight-light" style="font-size: 1.25rem;">MONIKA</span>
        <span class="brand-text text-white-50" style="font-size: 0.65rem; font-weight: 300; margin-top: 2px; letter-spacing: 0.5px;">MOnitoring NIlai Kinerja & Anomali</span>
      </div>
      <img src="<?= base_url('assets/img/logo.svg') ?>" alt="Logo" class="brand-image float-none ml-2" style="max-height: 38px; width: auto; opacity: .9; margin-right: .5rem;">
    </a>

    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block"><?= session()->get('fullname') ?></a>
        </div>
      </div>

      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <?php if(session()->get('id_role') == 1): ?>
          <li class="nav-item">
            <a href="<?= base_url('kegiatan') ?>" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Master Kegiatan</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('monitoring') ?>" class="nav-link">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Monitoring & Evaluasi</p>
            </a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a href="<?= base_url('dokumen') ?>" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Dokumen Survei</p>
            </a>
          </li>
          <?php if(session()->get('id_role') == 1): ?>
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
            <h1 class="m-0">Dashboard Monitoring</h1>
          </div>
          <div class="col-sm-6">
            <form method="get" action="">
                <div class="form-group row float-sm-right">
                    <div class="col-auto">
                        <select name="kegiatan" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Kegiatan --</option>
                            <?php foreach($list_kegiatan as $k): ?>
                                <option value="<?= $k['id_kegiatan'] ?>" <?= ($selected_kegiatan == $k['id_kegiatan']) ? 'selected' : '' ?>>
                                    <?= $k['nama_kegiatan'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-4 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $stat_total ?></h3>
                <p>Total Dokumen Masuk</p>
              </div>
              <div class="icon">
                <i class="fas fa-file"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $stat_entry ?></h3>
                <p>Sudah Entry</p>
              </div>
              <div class="icon">
                <i class="fas fa-check-circle"></i>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= $stat_error ?></h3>
                <p>Dokumen Error / Anomali</p>
              </div>
              <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6">
            <div class="card card-danger card-outline">
              <div class="card-header">
                <h5 class="card-title m-0">Top 5 Petugas Error Terbanyak</h5>
              </div>
              <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama PCL</th>
                            <th>Jumlah Error</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($ranking)): ?>
                            <tr><td colspan="3" class="text-center">Belum ada data error.</td></tr>
                        <?php else: ?>
                            <?php foreach($ranking as $i => $r): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= $r['fullname'] ?></td>
                                <td><span class="badge badge-danger"><?= $r['error_count'] ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
              </div>
            </div>
          </div>
          
          <div class="col-lg-6">
             <div class="card card-primary card-outline">
                <div class="card-header">
                    <h5 class="card-title m-0">Informasi</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Selamat datang di sistem <strong>MONIKA</strong>. Gunakan filter di pojok kanan atas untuk melihat statistik spesifik per Kegiatan Survei.</p>
                </div>
             </div>
          </div>
        </div>
        
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
</body>
</html>
