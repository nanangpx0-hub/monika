<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header bg-bps-blue">
                <h3 class="card-title">Input Dokumen Masuk</h3>
            </div>
            
            <form action="<?= base_url('tanda-terima/create'); ?>" method="post">
                <?= csrf_field(); ?>
                <div class="card-body">
                    
                    <!-- Pilih NKS -->
                    <div class="form-group">
                        <label>Nomor Kode Sampel (NKS)</label>
                        <select class="form-control" name="nks" required>
                            <option value="">-- Pilih NKS --</option>
                            <?php foreach ($data_nks as $nks) : ?>
                                <option value="<?= $nks['nks']; ?>">
                                    <?= $nks['nks']; ?> - <?= $nks['kecamatan']; ?> - <?= $nks['desa']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Jml Ruta -->
                    <div class="form-group">
                        <label>Jumlah Ruta Diterima (Maks. 10)</label>
                        <input type="number" class="form-control" name="jml_ruta_terima" min="1" max="10" placeholder="Contoh: 4" required>
                        <small class="text-muted">Masukkan jumlah sesuai fisik dokumen yang diserahkan.</small>
                    </div>

                    <!-- Tanggal -->
                    <div class="form-group">
                        <label>Tanggal Terima</label>
                        <input type="date" class="form-control" name="tgl_terima" value="<?= date('Y-m-d'); ?>" required>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary bg-bps-blue">Simpan Data</button>
                    <a href="<?= base_url('tanda-terima'); ?>" class="btn btn-default float-right">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
