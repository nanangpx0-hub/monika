<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header bg-bps-blue">
                <h3 class="card-title">Edit Dokumen Masuk</h3>
            </div>

            <form action="<?= base_url('tanda-terima/update/' . $data_terima['id']); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="card-body">

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= esc($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label>Nomor Kode Sampel (NKS)</label>
                        <select class="form-control" name="nks" required>
                            <option value="">-- Pilih NKS --</option>
                            <?php foreach ($data_nks as $nks) : ?>
                                <?php
                                    $selectedNks = old('nks', $data_terima['nks'] ?? '');
                                ?>
                                <option value="<?= esc($nks['nks']); ?>" <?= ($selectedNks === $nks['nks']) ? 'selected' : ''; ?>>
                                    <?= esc($nks['nks']); ?> - <?= esc($nks['kecamatan']); ?> - <?= esc($nks['desa']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Ruta Diterima (Maks. 10)</label>
                        <input
                            type="number"
                            class="form-control"
                            name="jml_ruta_terima"
                            min="1"
                            max="10"
                            value="<?= esc((string) old('jml_ruta_terima', $data_terima['jml_ruta_terima'] ?? '')); ?>"
                            required
                        >
                        <small class="text-muted">Masukkan jumlah sesuai fisik dokumen yang diserahkan.</small>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Terima</label>
                        <input
                            type="date"
                            class="form-control"
                            name="tgl_terima"
                            value="<?= esc((string) old('tgl_terima', $data_terima['tgl_terima'] ?? date('Y-m-d'))); ?>"
                            required
                        >
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary bg-bps-blue">Update Data</button>
                    <a href="<?= base_url('tanda-terima'); ?>" class="btn btn-default float-right">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
