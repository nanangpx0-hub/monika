<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Dokumen Survei' ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
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
      <a href="<?= base_url('dashboard') ?>"
        class="brand-link d-flex align-items-center justify-content-between pb-3 pt-3">
        <div class="d-flex flex-column pl-2" style="line-height: 1;">
          <span class="brand-text font-weight-light" style="font-size: 1.25rem;">MONIKA</span>
          <span class="brand-text text-white-50"
            style="font-size: 0.65rem; font-weight: 300; margin-top: 2px; letter-spacing: 0.5px;">MOnitoring NIlai
            Kinerja & Anomali</span>
        </div>
        <img src="<?= base_url('assets/img/logo.svg') ?>" alt="Logo" class="brand-image float-none ml-2"
          style="max-height: 38px; width: auto; opacity: .9; margin-right: .5rem;">
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
              <a href="<?= base_url('dokumen') ?>" class="nav-link active">
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
              <a href="<?= base_url('laporan/pengolahan') ?>" class="nav-link">
                <i class="nav-icon fas fa-chart-pie"></i>
                <p>Kinerja Pengolahan</p>
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

          <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
          <?php endif; ?>
          <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
          <?php endif; ?>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar Dokumen</h3>
              <div class="card-tools">
                <?php if (in_array($role_id, [1, 3])): // Admin & PCL ?>
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
                    <th>PML</th>
                    <th>Tgl Setor</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($dokumen as $d): ?>
                    <tr>
                      <td><?= $d['id_dokumen'] ?></td>
                      <td><?= $d['nama_kegiatan'] ?></td>
                      <td><?= $d['kode_wilayah'] ?></td>
                      <td><?= esc($d['nama_pcl'] ?? '-') ?></td>
                      <td><?= esc($d['nama_pml'] ?? '-') ?></td>
                      <td><?= $d['tanggal_setor'] ?></td>
                      <td>
                        <?php
                        $badge = 'secondary';
                        if ($d['status'] == 'Setor')
                          $badge = 'info';
                        if ($d['status'] == 'Sudah Entry')
                          $badge = 'primary';
                        if ($d['status'] == 'Error')
                          $badge = 'danger';
                        if ($d['status'] == 'Valid')
                          $badge = 'success';
                        ?>
                        <?php if (!empty($d['status'])): ?>
                        <span class="badge badge-<?= $badge ?>"><?= $d['status'] ?></span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if (in_array($role_id, [1, 4])): // Admin & Pengolahan ?>
                          <?php if ($d['status'] == 'Setor'): ?>
                            <form action="/dokumen/mark-entry/<?= $d['id_dokumen'] ?>" method="post" style="display:inline;">
                              <?= csrf_field() ?>
                              <button type="submit" class="btn btn-primary btn-xs"
                                onclick="return confirm('Tandai sudah entry?')">
                                <i class="fas fa-check"></i> Entry
                              </button>
                            </form>

                            <form action="/dokumen/mark-clean/<?= $d['id_dokumen'] ?>" method="post" style="display:inline;">
                              <?= csrf_field() ?>
                              <button type="submit" class="btn btn-success btn-xs"
                                onclick="return confirm('Tandai clean/valid?')">
                                <i class="fas fa-broom"></i> Clean
                              </button>
                            </form>

                            <button type="button" class="btn btn-danger btn-xs btn-error" data-id="<?= $d['id_dokumen'] ?>"
                              data-toggle="modal" data-target="#modal-error">
                              <i class="fas fa-exclamation-triangle"></i> Error
                            </button>
                          <?php elseif ($d['status'] == 'Error'): ?>
                            <!-- Action Buttons for Error Status -->
                            <a href="/dokumen/edit/<?= $d['id_dokumen'] ?>" class="btn btn-warning btn-xs" title="Perbaiki Data">
                              <i class="fas fa-edit"></i> Fix
                            </a>

                            <form action="/dokumen/mark-clean/<?= $d['id_dokumen'] ?>" method="post" style="display:inline;">
                              <?= csrf_field() ?>
                              <button type="submit" class="btn btn-success btn-xs"
                                onclick="return confirm('Data sudah diperbaiki secara manual? Tandai Valid.')" title="Tandai Valid">
                                <i class="fas fa-check-circle"></i> Resolve
                              </button>
                            </form>
                          <?php elseif ($d['status'] == 'Valid' || $d['status'] == 'Sudah Entry'): ?>
                             <a href="/dokumen/edit/<?= $d['id_dokumen'] ?>" class="btn btn-warning btn-xs" title="Edit Data">
                              <i class="fas fa-edit"></i> Edit
                            </a>
                          <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($role_id == 1): // Admin always has Edit, Reset & Delete ?>
                          <a href="<?= base_url('dokumen/edit/' . $d['id_dokumen']) ?>" class="btn btn-info btn-xs" title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                          <?php if (!empty($d['status'])): ?>
                          <form action="<?= base_url('dokumen/reset-status/' . $d['id_dokumen']) ?>" method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-secondary btn-xs" onclick="return confirm('Reset status dokumen menjadi kosong?')" title="Reset Status ke Blank">
                              <i class="fas fa-undo"></i>
                            </button>
                          </form>
                          <?php endif; ?>
                          <form action="<?= base_url('dokumen/delete/' . $d['id_dokumen']) ?>" method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Hapus dokumen ini?')" title="Hapus">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
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
      $("#table-dokumen").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
      });

      // Pass ID to Modal
      $('.btn-error').on('click', function () {
        var id = $(this).data('id');
        $('#error_id_dokumen').val(id);
      });
    });
  </script>
</body>

</html>