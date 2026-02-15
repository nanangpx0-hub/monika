<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header bg-bps-blue">
                <h3 class="card-title"><i class="fas fa-plus"></i> Tambah Temuan Uji Petik</h3>
            </div>
            
            <form action="<?= base_url('uji-petik/store'); ?>" method="post">
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
                        <!-- Pilih NKS -->
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Nomor Kode Sampel (NKS) <span class="text-danger">*</span></label>
                                <select class="form-control" name="nks" required>
                                    <option value="">-- Pilih NKS --</option>
                                    <?php foreach ($nks_list as $nks): ?>
                                        <option value="<?= $nks['nks']; ?>" <?= old('nks') == $nks['nks'] ? 'selected' : '' ?>>
                                            <?= $nks['nks']; ?> - <?= $nks['kecamatan']; ?> - <?= $nks['desa']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- No Ruta -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No. Ruta <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="no_ruta" 
                                       min="1" max="10" placeholder="1-10" 
                                       value="<?= old('no_ruta') ?>" required>
                            </div>
                        </div>
                    </div>

                    <!-- Variabel -->
                    <div class="form-group">
                        <label>Nama Variabel <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="variabel" 
                               placeholder="Contoh: Blok IV Rincian 5" 
                               value="<?= old('variabel') ?>" required>
                        <small class="text-muted">Sebutkan blok dan rincian yang diperiksa</small>
                    </div>

                    <div class="row">
                        <!-- Isian K (Dokumen Fisik) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Isian Dokumen Fisik (K) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="isian_k" 
                                       placeholder="Isian di dokumen" 
                                       value="<?= old('isian_k') ?>" required>
                                <small class="text-success">Isian yang benar dari dokumen fisik</small>
                            </div>
                        </div>

                        <!-- Isian C (Komputer) -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Isian Komputer (C) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="isian_c" 
                                       placeholder="Isian di komputer" 
                                       value="<?= old('isian_c') ?>" required>
                                <small class="text-danger">Isian yang salah di sistem</small>
                            </div>
                        </div>
                    </div>

                    <!-- Alasan Kesalahan -->
                    <div class="form-group">
                        <label>Alasan Kesalahan <span class="text-danger">*</span></label>
                        <select class="form-control" name="alasan_kesalahan" required>
                            <option value="">-- Pilih Alasan --</option>
                            <?php foreach ($alasan_list as $alasan): ?>
                                <option value="<?= $alasan ?>" <?= old('alasan_kesalahan') == $alasan ? 'selected' : '' ?>>
                                    <?= $alasan ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Catatan -->
                    <div class="form-group">
                        <label>Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="3" 
                                  placeholder="Tambahkan catatan jika diperlukan"><?= old('catatan') ?></textarea>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary bg-bps-blue">
                        <i class="fas fa-save"></i> Simpan Temuan
                    </button>
                    <a href="<?= base_url('uji-petik'); ?>" class="btn btn-default float-right">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Panel -->
    <div class="col-md-4">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Panduan</h3>
            </div>
            <div class="card-body">
                <p><strong>Uji Petik Kualitas</strong> digunakan untuk membandingkan isian dokumen fisik dengan isian di komputer.</p>
                
                <hr>
                
                <p><strong>Isian K (Hijau):</strong><br>
                Isian yang benar dari dokumen fisik</p>
                
                <p><strong>Isian C (Merah):</strong><br>
                Isian yang salah di sistem komputer</p>
                
                <hr>
                
                <p class="mb-0"><small class="text-muted">
                    <i class="fas fa-lightbulb"></i> Pastikan data yang diinput sudah sesuai dengan hasil pemeriksaan fisik dokumen.
                </small></p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
