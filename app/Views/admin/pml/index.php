<?= $this->extend('layout/dashboard') ?>

<?= $this->section('header_actions') ?>
<div class="float-sm-right">
    <a href="<?= base_url('admin/pml/create') ?>" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah PML
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Daftar Petugas Monitoring Lapangan (PML)</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>No Telp</th>
                    <th>Wilayah Supervisi</th>
                    <th>Kualifikasi</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pmls)): ?>
                    <tr>
                        <td colspan="8" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pmls as $pml): ?>
                        <tr>
                            <td><?= $pml['id_user'] ?></td>
                            <td><?= esc($pml['fullname']) ?></td>
                            <td><?= esc($pml['username']) ?></td>
                            <td><?= esc($pml['phone_number']) ?></td>
                            <td><?= esc($pml['wilayah_supervisi']) ?></td>
                            <td><?= esc($pml['qualification']) ?></td>
                            <td>
                                <?= ($pml['is_active']) ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Non-Aktif</span>' ?>
                            </td>
                            <td>
                                <a href="<?= base_url('admin/pml/edit/' . $pml['id_user']) ?>" class="btn btn-warning btn-xs" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('admin/pml/monitoring/' . $pml['id_user']) ?>" class="btn btn-info btn-xs" title="Monitoring">
                                    <i class="fas fa-eye"></i> Log
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
