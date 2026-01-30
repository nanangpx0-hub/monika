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
                                <input type="text" class="form-control" id="fullname" name="fullname" value="<?= old('fullname', isset($pml) ? $pml['fullname'] : '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= old('username', isset($pml) ? $pml['username'] : '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email', isset($pml) ? $pml['email'] : '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Password <?= isset($pml) ? '(Kosongkan jika tidak ingin mengubah)' : '' ?></label>
                                <input type="password" class="form-control" id="password" name="password" <?= isset($pml) ? '' : 'required' ?>>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nik_ktp">NIK</label>
                                <input type="text" class="form-control" id="nik_ktp" name="nik_ktp" value="<?= old('nik_ktp', isset($pml) ? $pml['nik_ktp'] : '') ?>" required minlength="16" maxlength="16">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone_number">No. Telepon</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="<?= old('phone_number', isset($pml) ? $pml['phone_number'] : '') ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="wilayah_supervisi">Wilayah Supervisi</label>
                                <input type="text" class="form-control" id="wilayah_supervisi" name="wilayah_supervisi" value="<?= old('wilayah_supervisi', isset($pml) ? $pml['wilayah_supervisi'] : '') ?>" required placeholder="Contoh: Kecamatan X">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="qualification">Kualifikasi</label>
                                <input type="text" class="form-control" id="qualification" name="qualification" value="<?= old('qualification', isset($pml) ? $pml['qualification'] : '') ?>" required placeholder="Contoh: S1 Statistik">
                            </div>
                        </div>
                    </div>

                    <?php if (isset($pml)): ?>
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" <?= $pml['is_active'] ? 'checked' : '' ?>>
                                <label class="custom-control-label" for="is_active">Akun Aktif</label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('admin/pml') ?>" class="btn btn-default">Batal</a>
                </div>
            </form>
        </div>

        <?php if (isset($pml)): ?>
            <div class="card card-info mt-3">
                <div class="card-header">
                    <h3 class="card-title">Daftar PCL Binaan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nama PCL</th>
                                <th>Wilayah Kerja</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($assignedPcls)): ?>
                                <tr>
                                    <td colspan="3" class="text-center">Belum ada PCL yang dibina.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($assignedPcls as $pcl): ?>
                                    <tr>
                                        <td><?= esc($pcl['fullname']) ?></td>
                                        <td><?= esc($pcl['wilayah_kerja']) ?></td>
                                        <td>
                                            <a href="<?= base_url('admin/pml/unassignPcl/' . $pcl['id_user']) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Lepas tugas PCL ini?')">
                                                <i class="fas fa-minus"></i> Lepas
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>

                    <hr>
                    <h5>Tambah PCL Binaan</h5>
                    <form action="<?= base_url('admin/pml/assignPcl') ?>" method="post">
                        <?= csrf_field() ?>
                        <input type="hidden" name="pml_id" value="<?= $pml['id_user'] ?>">
                        <div class="form-group">
                            <label>Pilih PCL (Yang belum memiliki supervisor)</label>
                            <select name="pcl_ids[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                <?php foreach ($availablePcls as $pcl): ?>
                                    <option value="<?= $pcl['id_user'] ?>"><?= esc($pcl['fullname']) ?> - <?= esc($pcl['wilayah_kerja']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-sm">Tambahkan</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
