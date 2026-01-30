<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Laporan Pengolahan' ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar & Sidebar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="<?= base_url('logout') ?>" role="button"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>

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
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
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
          <li class="nav-item">
            <a href="<?= base_url('dokumen') ?>" class="nav-link">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Dokumen Survei</p>
            </a>
          </li>
          <li class="nav-header">LAPORAN</li>
          <li class="nav-item">
            <a href="<?= base_url('laporan/pcl') ?>" class="nav-link">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Kinerja PCL</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('laporan/pengolahan') ?>" class="nav-link active">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Kinerja Pengolahan</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1 class="m-0">Laporan Kinerja Pengolahan</h1></div>
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

    <div class="content">
      <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <table id="table-laporan" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Petugas</th>
                            <th>Sobat ID</th>
                            <th>Total Entry</th>
                            <!-- <th>Total Temuan Error</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($laporan as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $row['fullname'] ?></td>
                            <td><?= $row['sobat_id'] ?></td>
                            <td><?= $row['total_entry'] ?></td>
                           
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script>
  $(function () {
    $("#table-laporan").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    });
  });
</script>
</body>
</html>
