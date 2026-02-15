<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Monitoring Dashboard' ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/css/monika-ui.css'); ?>">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar & Sidebar -->
   <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a></li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <li class="nav-item"><a class="nav-link monika-logout-link" href="<?= base_url('logout'); ?>" role="button" aria-label="Logout"><i class="fas fa-sign-out-alt"></i> <span class="logout-text">Logout</span></a></li>
    </ul>
  </nav>

  <a class="monika-logout-fab" href="<?= base_url('logout'); ?>" role="button" aria-label="Logout cepat">
    <i class="fas fa-sign-out-alt"></i>
    <span class="logout-text">Logout</span>
  </a>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/monitoring" class="brand-link">
      <span class="brand-text font-weight-light">MONIKA System</span>
    </a>
    <div class="sidebar">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
          <li class="nav-item">
              <a href="/monitoring" class="nav-link active">
                  <i class="nav-icon fas fa-tachometer-alt"></i><p>Monitoring Dashboard</p>
              </a>
          </li>
          <?php if(session()->get('id_role') == 1): ?>
          <li class="nav-item"><a href="/kegiatan" class="nav-link"><i class="nav-icon fas fa-calendar-alt"></i><p>Master Kegiatan</p></a></li>
          <?php endif; ?>
          <li class="nav-item"><a href="/dokumen" class="nav-link"><i class="nav-icon fas fa-file-alt"></i><p>Dokumen Survei</p></a></li>
        </ul>
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6"><h1 class="m-0">Monitoring & Evaluasi</h1></div>
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
        
        <!-- Stats Widgets -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner"><h3><?= $stat_total ?></h3><p>Total Dokumen</p></div>
              <div class="icon"><i class="fas fa-file"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner"><h3><?= $stat_entry ?></h3><p>Sudah Entry</p></div>
              <div class="icon"><i class="fas fa-check"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner"><h3><?= $stat_clean ?></h3><p>Data Clean</p></div>
              <div class="icon"><i class="fas fa-thumbs-up"></i></div>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner"><h3><?= $stat_error ?></h3><p>Active Errors</p></div>
              <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            </div>
          </div>
        </div>

        <div class="row">
            <!-- Ranking Error -->
            <div class="col-md-4">
                <div class="card card-danger">
                    <div class="card-header"><h3 class="card-title">Top 5 Error Contributors</h3></div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-sm">
                            <thead><tr><th>Nama PCL</th><th>Errors</th></tr></thead>
                            <tbody>
                                <?php foreach($ranking_error as $r): ?>
                                <tr><td><?= $r['fullname'] ?></td><td><span class="badge badge-danger"><?= $r['error_count'] ?></span></td></tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Supervisor Evaluation -->
             <div class="col-md-8">
                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title">Evaluasi Kinerja Pengawas (PML)</h3></div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-sm">
                            <thead><tr><th>Nama PML</th><th>Jml PCL</th><th>Total Docs Team</th><th>Total Error Team</th><th>Error Ratio</th></tr></thead>
                            <tbody>
                                <?php foreach($eval_spv as $spv): ?>
                                <tr>
                                    <td><?= $spv['fullname'] ?></td>
                                    <td><?= $spv['team_size'] ?></td>
                                    <td><?= $spv['total_team_docs'] ?></td>
                                    <td><?= $spv['total_team_errors'] ?></td>
                                    <td>
                                        <?php 
                                            $ratio = $spv['total_team_docs'] > 0 ? ($spv['total_team_errors'] / $spv['total_team_docs']) * 100 : 0;
                                            echo number_format($ratio, 2) . '%';
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

        <div class="row">
            <!-- PCL Evaluation -->
             <div class="col-md-6">
                <div class="card card-outline card-info">
                    <div class="card-header"><h3 class="card-title">Evaluasi PCL</h3></div>
                    <div class="card-body">
                        <table id="table-pcl" class="table table-bordered table-hover">
                            <thead><tr><th>Nama</th><th>Total Docs</th><th>Error Ratio</th><th>Score</th></tr></thead>
                            <tbody>
                                <?php foreach($eval_pcl as $pcl): ?>
                                <tr>
                                    <td><?= $pcl['fullname'] ?></td>
                                    <td><?= $pcl['total_dokumen'] ?></td>
                                    <td>
                                        <?php 
                                            $ratio = $pcl['total_dokumen'] > 0 ? ($pcl['total_error'] / $pcl['total_dokumen']) * 100 : 0;
                                            echo number_format($ratio, 1) . '%';
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $score = 100 - $ratio;
                                            $badge = $score > 90 ? 'success' : ($score > 70 ? 'warning' : 'danger');
                                            echo "<span class='badge badge-$badge'>".number_format($score, 1)."</span>"; 
                                        ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
             </div>

             <!-- Processor Evaluation -->
             <div class="col-md-6">
                <div class="card card-outline card-success">
                    <div class="card-header"><h3 class="card-title">Evaluasi Pengolahan</h3></div>
                    <div class="card-body">
                         <table id="table-proc" class="table table-bordered table-hover">
                            <thead><tr><th>Nama</th><th>Productivity (Entry)</th><!-- <th>Accuracy (Errors Found)</th> --></tr></thead>
                            <tbody>
                                <?php foreach($eval_proc as $proc): ?>
                                <tr>
                                    <td><?= $proc['fullname'] ?></td>
                                    <td><?= $proc['total_entry'] ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
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
    $("#table-pcl").DataTable({"pageLength": 5, "lengthChange": false});
    $("#table-proc").DataTable({"pageLength": 5, "lengthChange": false});
  });
</script>
</body>
</html>
