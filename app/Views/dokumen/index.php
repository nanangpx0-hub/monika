<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Dokumen Survei' ?></title>

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
        <a class="nav-link monika-logout-link" href="<?= base_url('logout'); ?>" role="button" aria-label="Logout">
          <i class="fas fa-sign-out-alt"></i> <span class="logout-text">Logout</span>
        </a>
      </li>
    </ul>
  </nav>

  <a class="monika-logout-fab" href="<?= base_url('logout'); ?>" role="button" aria-label="Logout cepat">
    <i class="fas fa-sign-out-alt"></i>
    <span class="logout-text">Logout</span>
  </a>

  <!-- Main Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/dashboard" class="brand-link">
      <span class="brand-text font-weight-light">MONIKA System</span>
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
            <a href="/dashboard" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <?php if(session()->get('id_role') == 1): ?>
          <li class="nav-item">
            <a href="/kegiatan" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Master Kegiatan</p>
            </a>
          </li>
          <?php endif; ?>
          <li class="nav-item">
            <a href="/dokumen" class="nav-link active">
              <i class="nav-icon fas fa-file-alt"></i>
              <p>Dokumen Survei</p>
            </a>
          </li>
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
            <h1 class="m-0">Dokumen Survei</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dokumen</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        
        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Dokumen</h3>
                <div class="card-tools">
                    <?php if(in_array($role_id, [1, 3])): // Admin & PCL ?>
                    <a href="/dokumen/create" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Setor Dokumen
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <table id="table-dokumen" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Kegiatan</th>
                            <th>Wilayah</th>
                            <th>PCL</th>
                            <th>Tgl Setor</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($dokumen as $d): ?>
                        <tr>
                            <td><?= $d['id_dokumen'] ?></td>
                            <td><?= $d['nama_kegiatan'] ?></td>
                            <td><?= $d['kode_wilayah'] ?></td>
                            <td><?= $d['nama_pcl'] ?></td>
                            <td><?= $d['tanggal_setor'] ?></td>
                            <td>
                                <?php 
                                    $badge = 'secondary';
                                    if($d['status'] == 'Uploaded') $badge = 'info';
                                    if($d['status'] == 'Sudah Entry') $badge = 'primary';
                                    if($d['status'] == 'Error') $badge = 'danger';
                                    if($d['status'] == 'Valid') $badge = 'success';
                                ?>
                                <span class="badge badge-<?= $badge ?>"><?= $d['status'] ?></span>
                            </td>
                            <td>
                                <?php if(in_array($role_id, [1, 4]) && $d['status'] == 'Uploaded'): // Admin & Pengolahan ?>
                                    <form action="/dokumen/mark-entry/<?= $d['id_dokumen'] ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-primary btn-xs" onclick="return confirm('Tandai sudah entry?')">
                                            <i class="fas fa-check"></i> Entry
                                        </button>
                                    </form>

                                    <button type="button" class="btn btn-danger btn-xs btn-error" 
                                        data-id="<?= $d['id_dokumen'] ?>" 
                                        data-toggle="modal" data-target="#modal-error">
                                        <i class="fas fa-exclamation-triangle"></i> Error
                                    </button>
                                <?php endif; ?>
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

  <?php include 'modal_error.php'; ?>

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
    $("#table-dokumen").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print"]
    });

    // Pass ID to Modal
    $('.btn-error').on('click', function() {
        var id = $(this).data('id');
        $('#error_id_dokumen').val(id);
    });
  });
</script>
</body>
</html>
