<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
            </div>
            <form action="<?= $action_url ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= $error ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fullname">Nama Lengkap</label>
                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?= old('fullname', isset($pcl) ? $pcl['fullname'] : '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= old('username', isset($pcl) ? $pcl['username'] : '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email', isset($pcl) ? $pcl['email'] : '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <?= isset($pcl) ? '(Kosongkan jika tidak ingin mengubah)' : '' ?></label>
                                <input type="password" class="form-control" id="password" name="password" <?= isset($pcl) ? '' : 'required' ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik_ktp">NIK</label>
                                <input type="text" class="form-control" id="nik_ktp" name="nik_ktp" value="<?= old('nik_ktp', isset($pcl) ? $pcl['nik_ktp'] : '') ?>" required minlength="16" maxlength="16">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number">No. Telepon</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= old('phone_number', isset($pcl) ? $pcl['phone_number'] : '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="wilayah_kerja">Wilayah Kerja</label>
                        <input type="text" class="form-control" id="wilayah_kerja" name="wilayah_kerja" value="<?= old('wilayah_kerja', isset($pcl) ? $pcl['wilayah_kerja'] : '') ?>" required placeholder="Contoh: Desa A, Blok 1">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="masa_tugas_start">Masa Tugas Mulai</label>
                                <input type="date" class="form-control" id="masa_tugas_start" name="masa_tugas_start" value="<?= old('masa_tugas_start', isset($pcl) ? $pcl['masa_tugas_start'] : '') ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="masa_tugas_end">Masa Tugas Selesai</label>
                                <input type="date" class="form-control" id="masa_tugas_end" name="masa_tugas_end" value="<?= old('masa_tugas_end', isset($pcl) ? $pcl['masa_tugas_end'] : '') ?>">
                            </div>
                        </div>
                    </div>

                    <?php if (isset($pcl)): ?>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?= $pcl['is_active'] ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="is_active">Akun Aktif</label>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('admin/pcl') ?>" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
