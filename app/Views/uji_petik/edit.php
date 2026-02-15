<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header bg-bps-blue">
                <h3 class="card-title"><i class="fas fa-edit"></i> Edit Temuan Uji Petik</h3>
            </div>
            
            <form action="<?= base_url('uji-petik/update/' . $finding['id']); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="card-body">
                    
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nomor Kode Sampel (NKS) <span class="text-danger">*</span></label>
                                <?php $selectedNks = old('nks', $finding['nks'] ?? ''); ?>
                                <select class="form-control" name="nks" required>
                                    <option value="">-- Pilih NKS --</option>
                                    <?php foreach ($nks_list as $nks): ?>
                                        <option value="<?= esc($nks['nks']); ?>" <?= ($selectedNks === $nks['nks']) ? 'selected' : '' ?>>
                                            <?= esc($nks['nks']); ?> - <?= esc($nks['kecamatan']); ?> - <?= esc($nks['desa']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. Ruta <span class="text-danger">*</span></label>
                                <input
                                    type="number"
                                    class="form-control"
                                    name="no_ruta"
                                    min="1"
                                    max="10"
                                    value="<?= esc((string) old('no_ruta', $finding['no_ruta'] ?? '')); ?>"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nama Variabel <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            class="form-control"
                            name="variabel"
                            value="<?= esc((string) old('variabel', $finding['variabel'] ?? '')); ?>"
                            required
                        >
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Isian Dokumen Fisik (K) <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="isian_k"
                                    value="<?= esc((string) old('isian_k', $finding['isian_k'] ?? '')); ?>"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Isian Komputer (C) <span class="text-danger">*</span></label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="isian_c"
                                    value="<?= esc((string) old('isian_c', $finding['isian_c'] ?? '')); ?>"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Alasan Kesalahan <span class="text-danger">*</span></label>
                        <?php $selectedAlasan = old('alasan_kesalahan', $finding['alasan_kesalahan'] ?? ''); ?>
                        <select class="form-control" name="alasan_kesalahan" required>
                            <option value="">-- Pilih Alasan --</option>
                            <?php foreach ($alasan_list as $alasan): ?>
                                <option value="<?= esc($alasan); ?>" <?= ($selectedAlasan === $alasan) ? 'selected' : '' ?>>
                                    <?= esc($alasan) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="3"><?= esc((string) old('catatan', $finding['catatan'] ?? '')); ?></textarea>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary bg-bps-blue">
                        <i class="fas fa-save"></i> Update Temuan
                    </button>
                    <a href="<?= base_url('uji-petik'); ?>" class="btn btn-default float-right">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Panduan</h3>
            </div>
            <div class="card-body">
                <p>Perbarui data sesuai hasil uji petik terbaru.</p>
                <p><strong>Isian K:</strong> nilai benar dari dokumen fisik.</p>
                <p><strong>Isian C:</strong> nilai yang tercatat di sistem.</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
