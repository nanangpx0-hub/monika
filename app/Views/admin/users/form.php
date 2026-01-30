<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <?= $title ?>
                </h3>
            </div>

            <form action="<?= $action_url ?>" method="post">
                <?= csrf_field() ?>
                <?php if (isset($method) && $method == 'PUT'): ?>
                    <input type="hidden" name="_method" value="PUT">
                <?php endif; ?>

                <div class="card-body">
                    <div class="form-group">
                        <label for="fullname">Nama Lengkap</label>
                        <input type="text" name="fullname"
                            class="form-control <?= session('errors.fullname') ? 'is-invalid' : '' ?>" id="fullname"
                            placeholder="Masukkan nama lengkap" value="<?= old('fullname', $user['fullname'] ?? '') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.fullname') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username"
                            class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" id="username"
                            placeholder="Masukkan username" value="<?= old('username', $user['username'] ?? '') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.username') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" name="email"
                            class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" id="email"
                            placeholder="Masukkan email" value="<?= old('email', $user['email'] ?? '') ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.email') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password"
                            class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" id="password"
                            placeholder="<?= isset($user) ? 'Kosongkan jika tidak ingin mengubah password' : 'Minimal 6 karakter' ?>">
                        <div class="invalid-feedback">
                            <?= session('errors.password') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="confpassword">Konfirmasi Password</label>
                        <input type="password" name="confpassword"
                            class="form-control <?= session('errors.confpassword') ? 'is-invalid' : '' ?>"
                            id="confpassword" placeholder="Ulangi password">
                        <div class="invalid-feedback">
                            <?= session('errors.confpassword') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="id_role">Role / Jabatan</label>
                        <select name="id_role" class="form-control <?= session('errors.id_role') ? 'is-invalid' : '' ?>"
                            id="id_role">
                            <option value="">-- Pilih Role --</option>
                            <option value="1" <?= (old('id_role', $user['id_role'] ?? '') == 1) ? 'selected' : '' ?>
                                >Administrator</option>
                            <option value="3" <?= (old('id_role', $user['id_role'] ?? '') == 3) ? 'selected' : '' ?>
                                >Petugas Pendataan (PCL)</option>
                            <option value="5" <?= (old('id_role', $user['id_role'] ?? '') == 5) ? 'selected' : '' ?>
                                >Pengawas Lapangan (PML)</option>
                            <option value="4" <?= (old('id_role', $user['id_role'] ?? '') == 4) ? 'selected' : '' ?>
                                >Petugas Pengolahan</option>
                            <option value="6" <?= (old('id_role', $user['id_role'] ?? '') == 6) ? 'selected' : '' ?>
                                >Pengawas Pengolahan</option>
                        </select>
                        <div class="invalid-feedback">
                            <?= session('errors.id_role') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nik_ktp">NIK (Opsional)</label>
                        <input type="text" name="nik_ktp"
                            class="form-control <?= session('errors.nik_ktp') ? 'is-invalid' : '' ?>" id="nik_ktp"
                            value="<?= old('nik_ktp', $user['nik_ktp'] ?? '') ?>" maxlength="16">
                        <div class="invalid-feedback">
                            <?= session('errors.nik_ktp') ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="sobat_id">Sobat ID (Opsional)</label>
                        <input type="text" name="sobat_id"
                            class="form-control <?= session('errors.sobat_id') ? 'is-invalid' : '' ?>" id="sobat_id"
                            value="<?= old('sobat_id', $user['sobat_id'] ?? '') ?>">
                    </div>

                    <?php if (isset($user)): ?>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" <?= (old('is_active', $user['is_active']) == 1) ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="is_active">Status Aktif</label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-default float-right">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>