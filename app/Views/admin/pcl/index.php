<?= $this->extend('layout/dashboard') ?>

<?= $this->section('header_actions') ?>
<div class="float-sm-right">
    <a href="<?= base_url('admin/pcl/template') ?>" class="btn btn-outline-secondary btn-sm mr-1">
        <i class="fas fa-download"></i> Template
    </a>
    <a href="<?= base_url('admin/pcl/import') ?>" class="btn btn-info btn-sm mr-1">
        <i class="fas fa-file-import"></i> Impor CSV
    </a>
    <a href="<?= base_url('admin/pcl/export') ?>" class="btn btn-success btn-sm mr-1">
        <i class="fas fa-file-export"></i> Ekspor CSV
    </a>
    <a href="<?= base_url('admin/pcl/create') ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah PCL
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Petugas Pendataan Lapangan (PCL)</h3>
        <div class="card-tools">
            <form action="" method="get">
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <input type="text" name="wilayah" class="form-control form-control-sm" placeholder="Filter Wilayah..." value="<?= esc($filters['wilayah']) ?>">
                    </div>
                    <div class="col-auto">
                        <select name="status" class="form-control form-control-sm">
                            <option value="">Semua Status</option>
                            <option value="1" <?= $filters['status'] === '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= $filters['status'] === '0' ? 'selected' : '' ?>>Non-Aktif</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-default btn-sm">
                            <i class="fas fa-search"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>NIK</th>
                    <th>No Telp</th>
                    <th>Wilayah Kerja</th>
                    <th>Masa Tugas</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pcls)): ?>
                    <tr>
                        <td colspan="9" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pcls as $pcl): ?>
                        <tr>
                            <td><?= $pcl['id_user'] ?></td>
                            <td><?= esc($pcl['fullname']) ?></td>
                            <td><?= esc($pcl['username']) ?></td>
                            <td><?= esc($pcl['nik_ktp']) ?></td>
                            <td><?= esc($pcl['phone_number']) ?></td>
                            <td><?= esc($pcl['wilayah_kerja']) ?></td>
                            <td>
                                <small>
                                    Mulai: <?= $pcl['masa_tugas_start'] ? date('d-m-Y', strtotime($pcl['masa_tugas_start'])) : '-' ?><br>
                                    Selesai: <?= $pcl['masa_tugas_end'] ? date('d-m-Y', strtotime($pcl['masa_tugas_end'])) : '-' ?>
                                </small>
                            </td>
                            <td>
                                <?= ($pcl['is_active']) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non-Aktif</span>' ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/pcl/edit/' . $pcl['id_user']) ?>" class="btn btn-warning btn-xs" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <?= $pager->links() ?>
    </div>
</div>
<?= $this->endSection() ?>
