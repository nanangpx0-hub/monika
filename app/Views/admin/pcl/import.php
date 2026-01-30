<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">Import Data PCL</h3>
        <div class="card-tools">
            <a href="<?= base_url('admin/pcl') ?>" class="btn btn-default btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="icon fas fa-exclamation-triangle"></i> <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session('import_errors')): ?>
            <div class="alert alert-warning">
                <h5><i class="icon fas fa-exclamation-triangle"></i> Error pada data CSV:</h5>
                <ul class="mb-0">
                    <?php foreach (session('import_errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <form action="<?= base_url('admin/pcl/import') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="form-group">
                        <label for="csv_file">File CSV <span class="text-danger">*</span></label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="csv_file" name="csv_file" accept=".csv" required>
                            <label class="custom-file-label" for="csv_file">Pilih file...</label>
                        </div>
                        <small class="text-muted">Format file harus CSV. Maksimal ukuran 5MB.</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Import Data
                        </button>
                        <a href="<?= base_url('admin/pcl/template') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </form>
            </div>

            <div class="col-md-6">
                <div class="card card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle"></i> Petunjuk Import</h3>
                    </div>
                    <div class="card-body">
                        <ol>
                            <li>Download template CSV terlebih dahulu</li>
                            <li>Isi data PCL sesuai dengan kolom yang tersedia</li>
                            <li>Pastikan format data sudah benar:
                                <ul>
                                    <li>NIK: 16 digit angka</li>
                                    <li>Email: format email valid</li>
                                    <li>No Telp: minimal 10 digit</li>
                                    <li>Tanggal: format YYYY-MM-DD</li>
                                </ul>
                            </li>
                            <li>Simpan file dalam format CSV (UTF-8)</li>
                            <li>Upload file dan klik "Import Data"</li>
                        </ol>
                        <div class="alert alert-info">
                            <i class="fas fa-lock"></i> <strong>Password Default:</strong> pcl12345<br>
                            <small>PCL harus mengganti password setelah login pertama kali.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = e.target.files[0].name;
    e.target.nextElementSibling.innerText = fileName;
});
</script>
<?= $this->endSection() ?>
