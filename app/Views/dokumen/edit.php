<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Edit Dokumen' ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
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

      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="/logout" role="button">
            <i class="fas fa-sign-out-alt"></i> Logout
          </a>
        </li>
      </ul>
    </nav>

    <!-- Main Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <a href="/dashboard" class="brand-link d-flex align-items-center justify-content-between pb-3 pt-3">
        <div class="d-flex flex-column pl-2" style="line-height: 1;">
          <span class="brand-text font-weight-light" style="font-size: 1.25rem;">MONIKA</span>
          <span class="brand-text text-white-50"
            style="font-size: 0.65rem; font-weight: 300; margin-top: 2px; letter-spacing: 0.5px;">MOnitoring NIlai
            Kinerja & Anomali</span>
        </div>
        <img src="/assets/img/logo.svg" alt="Logo" class="brand-image float-none ml-2"
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
              <a href="/dashboard" class="nav-link">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>Dashboard</p>
              </a>
            </li>
            <?php if (session()->get('id_role') == 1): ?>
              <li class="nav-item">
                <a href="/kegiatan" class="nav-link">
                  <i class="nav-icon fas fa-calendar-alt"></i>
                  <p>Master Kegiatan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/monitoring" class="nav-link">
                  <i class="nav-icon fas fa-chart-bar"></i>
                  <p>Monitoring & Evaluasi</p>
                </a>
              </li>
            <?php endif; ?>
            <li class="nav-item">
              <a href="/dokumen" class="nav-link active">
                <i class="nav-icon fas fa-file-alt"></i>
                <p>Dokumen Survei</p>
              </a>
            </li>
            <?php if (session()->get('id_role') == 1): ?>
              <li class="nav-header">LAPORAN</li>
              <li class="nav-item">
                <a href="/laporan/pcl" class="nav-link">
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

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Edit Dokumen</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/dokumen">Dokumen</a></li>
                <li class="breadcrumb-item active">Edit</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-6">
              <div class="card card-warning">
                <div class="card-header">
                  <h3 class="card-title">Form Edit Data</h3>
                </div>

                <form action="/dokumen/update/<?= $dokumen['id_dokumen'] ?>" method="post">
                  <?= csrf_field() ?>
                  <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                      <div class="alert alert-danger">
                        <ul>
                          <?php foreach (session()->getFlashdata('errors') as $e): ?>
                            <li><?= $e ?></li>
                          <?php endforeach; ?>
                        </ul>
                      </div>
                    <?php endif; ?>

                    <?php if ($dokumen['status'] == 'Error'): ?>
                      <div class="callout callout-danger">
                        <h5>Status: Error!</h5>
                        <p>Dokumen ini ditandai error. Silakan perbaiki data di bawah ini jika diperlukan, lalu simpan. Status akan otomatis berubah menjadi <b>Setor</b> setelah disimpan.</p>
                      </div>
                    <?php elseif ($dokumen['status'] == 'Valid'): ?>
                      <div class="callout callout-info">
                        <h5>Status: Valid</h5>
                        <p>Dokumen ini sudah valid. Jika Anda mengubah data ini, status akan dikembalikan menjadi <b>Setor</b> untuk verifikasi ulang.</p>
                      </div>
                    <?php endif; ?>

                    <div class="form-group">
                      <label>Kegiatan Survei</label>
                      <select name="id_kegiatan" class="form-control" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        <?php foreach ($kegiatan as $k): ?>
                          <option value="<?= $k['id_kegiatan'] ?>" <?= ($dokumen['id_kegiatan'] == $k['id_kegiatan']) ? 'selected' : '' ?>>
                            <?= $k['nama_kegiatan'] ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </div>

                    <div class="form-group">
                      <label>Kode Wilayah (NKS/NBS/ID SLS)</label>
                      <input type="text" name="kode_wilayah" class="form-control" 
                        value="<?= old('kode_wilayah', $dokumen['kode_wilayah']) ?>" required>
                    </div>

                    <div class="form-group">
                      <label>Tanggal Setor</label>
                      <input type="date" name="tanggal_setor" class="form-control" 
                        value="<?= old('tanggal_setor', $dokumen['tanggal_setor']) ?>" required>
                    </div>
                  </div>

                  <div class="card-footer">
                    <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    <a href="/dokumen" class="btn btn-default float-right">Batal</a>
                  </div>
                </form>
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
