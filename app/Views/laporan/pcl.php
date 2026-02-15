<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Laporan PCL' ?></title>

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
  <?php include APPPATH . 'Views/dashboard/navbar_sidebar.php'; // Ideally extract this to a partial ?>
  <!-- Ideally we should partials for sidebar/navbar, but for now I'll duplicate menu or user includes partial later if asked. 
       Let's just use full layout to avoid breaking previous steps assumptions or create partial now?
       Let's create the full layout to be safe.
  -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link" href="/logout" role="button"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/dashboard" class="brand-link">
      <span class="brand-text font-weight-light">MONIKA System</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
          <?php if(session()->get('id_role') == 1): ?>
          <li class="nav-item"><a href="/kegiatan" class="nav-link"><i class="nav-icon fas fa-calendar-alt"></i><p>Master Kegiatan</p></a></li>
          <?php endif; ?>
          <li class="nav-item"><a href="/dokumen" class="nav-link"><i class="nav-icon fas fa-file-alt"></i><p>Dokumen Survei</p></a></li>
          
          <?php if(session()->get('id_role') == 1): ?>
          <li class="nav-header">LAPORAN</li>
          <li class="nav-item">
            <a href="/laporan/pcl" class="nav-link active">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>Kinerja PCL</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/laporan/pengolahan" class="nav-link">
              <i class="nav-icon fas fa-chart-pie"></i>
              <p>Kinerja Pengolahan</p>
            </a>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1 class="m-0">Laporan Kinerja PCL</h1></div>
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
                            <th>Nama PCL</th>
                            <th>Sobat ID</th>
                            <th>Total Dokumen</th>
                            <th>Total Error</th>
                            <th>Skor Kualitas</th>
                            <th>Predikat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($laporan as $i => $row): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $row['fullname'] ?></td>
                            <td><?= $row['sobat_id'] ?></td>
                            <td><?= $row['total_dokumen'] ?></td>
                            <td><?= $row['total_error'] ?></td>
                            <td>
                                <?php 
                                    $score = 0;
                                    if ($row['total_dokumen'] > 0) {
                                        $score = 100 - (($row['total_error'] / $row['total_dokumen']) * 100);
                                    }
                                    echo number_format($score, 2);
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($score >= 90) echo '<span class="badge badge-success">Sangat Baik</span>';
                                    elseif($score >= 75) echo '<span class="badge badge-primary">Baik</span>';
                                    elseif($score >= 60) echo '<span class="badge badge-warning">Cukup</span>';
                                    else echo '<span class="badge badge-danger">Kurang</span>';
                                ?>
                            </td>
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
    <strong>Copyright &copy; 2026 <a href="#">MONIKA</a>.</strong> All rights reserved.
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
