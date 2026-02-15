<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h1>Tanda Terima Dokumen</h1>
    </div>
    <div class="col-sm-6">
        <div class="float-sm-right">
            <a href="<?= base_url('tanda-terima/new') ?>" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Tambah Terima Baru
            </a>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('pesan')) : ?>
    <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header bg-bps-blue">
        <h3 class="card-title">Log Dokumen Masuk (Dari Tim Sosial)</h3>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped projects">
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Tanggal</th>
                    <th>NKS</th>
                    <th>Wilayah (Kec - Desa)</th>
                    <th class="text-center">Jml Ruta</th>
                    <th style="width: 20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                <?php foreach ($data_terima as $d) : ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= date('d-m-Y', strtotime($d['tgl_terima'])); ?></td>
                    <td><span class="badge badge-info"><?= $d['nks']; ?></span></td>
                    <td>
                        <small><?= $d['kecamatan']; ?></small><br>
                        <strong><?= $d['desa']; ?></strong>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-success" style="font-size: 14px;">
                            <?= $d['jml_ruta_terima']; ?>
                        </span>
                    </td>
                    <td class="project-actions">
                        <a class="btn btn-danger btn-sm" href="<?= base_url('tanda-terima/delete/' . $d['id']); ?>" onclick="return confirm('Yakin hapus data ini?');">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection(); ?>
