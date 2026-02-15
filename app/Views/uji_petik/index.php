<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Uji Petik Kualitas</h3>
                <div class="card-tools">
                    <a href="<?= base_url('uji-petik/new') ?>" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Tambah Temuan
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm" id="tableUjiPetik">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">NKS</th>
                                <th width="12%">Kec/Desa</th>
                                <th width="5%">Ruta</th>
                                <th width="15%">Variabel</th>
                                <th width="12%">Isian K</th>
                                <th width="12%">Isian C</th>
                                <th width="12%">Alasan</th>
                                <th width="12%">Catatan</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($findings)): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        <i class="fas fa-inbox"></i> Belum ada temuan uji petik.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; ?>
                                <?php foreach ($findings as $finding): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++ ?></td>
                                        <td><strong><?= esc($finding['nks']) ?></strong></td>
                                        <td>
                                            <?php if (!empty($finding['kecamatan'])): ?>
                                                <small><?= esc($finding['kecamatan']) ?> / <?= esc($finding['desa']) ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><?= esc($finding['no_ruta']) ?></td>
                                        <td><?= esc($finding['variabel']) ?></td>
                                        <td><span class="text-success font-weight-bold"><?= esc($finding['isian_k']) ?></span></td>
                                        <td><span class="text-danger font-weight-bold"><?= esc($finding['isian_c']) ?></span></td>
                                        <td>
                                            <span class="badge badge-warning">
                                                <?= esc($finding['alasan_kesalahan']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($finding['catatan'])): ?>
                                                <small><?= esc($finding['catatan']) ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('uji-petik/edit/' . $finding['id']) ?>" 
                                               class="btn btn-warning btn-xs"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('uji-petik/delete/' . $finding['id']) ?>" 
                                               class="btn btn-danger btn-xs"
                                               onclick="return confirm('Yakin ingin menghapus temuan ini?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#tableUjiPetik').DataTable({
        "responsive": true,
        "autoWidth": false,
        "order": [[0, "desc"]],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>

<?= $this->endSection(); ?>
