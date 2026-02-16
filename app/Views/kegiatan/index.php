<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <?= esc((string) session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors'); ?>
        <?php if (! empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul class="mb-0 pl-3">
                    <?php foreach ((array) $errors as $error): ?>
                        <li><?= esc((string) $error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Daftar Kegiatan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-tambah">
                        <i class="fas fa-plus"></i> Tambah Kegiatan
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-kegiatan" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kegiatan</th>
                                <th>Kode</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kegiatan as $k): ?>
                                <tr>
                                    <td><?= esc((string) $k['id_kegiatan']) ?></td>
                                    <td><?= esc((string) $k['nama_kegiatan']) ?></td>
                                    <td><span class="badge badge-info"><?= esc((string) $k['kode_kegiatan']) ?></span></td>
                                    <td><?= esc((string) $k['tanggal_mulai']) ?> s.d <?= esc((string) $k['tanggal_selesai']) ?></td>
                                    <td>
                                        <?php if ($k['status'] === 'Aktif'): ?>
                                            <span class="badge badge-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Selesai</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <form action="<?= base_url('kegiatan/status/' . $k['id_kegiatan']) ?>" method="post" class="d-inline">
                                            <?= csrf_field() ?>
                                            <?php if ($k['status'] === 'Aktif'): ?>
                                                <input type="hidden" name="status" value="Selesai">
                                                <button type="submit" class="btn btn-warning btn-xs" onclick="return confirm('Tutup kegiatan ini?')">
                                                    <i class="fas fa-stop-circle"></i> Tutup
                                                </button>
                                            <?php else: ?>
                                                <input type="hidden" name="status" value="Aktif">
                                                <button type="submit" class="btn btn-success btn-xs" onclick="return confirm('Aktifkan kembali kegiatan ini?')">
                                                    <i class="fas fa-play-circle"></i> Buka
                                                </button>
                                            <?php endif; ?>
                                        </form>

                                        <a
                                            href="<?= base_url('kegiatan/delete/' . $k['id_kegiatan']) ?>"
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini? Semua dokumen terkait akan ikut terhapus!');"
                                        >
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
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

<?= $this->include('kegiatan/modal_create'); ?>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    $('#table-kegiatan').DataTable({
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json'
        }
    });
});
</script>
<?= $this->endSection(); ?>
