<!-- Modal Tambah Pengguna -->
<div class="modal fade" id="modal-create" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="post" action="<?= base_url('users/store') ?>">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title"><i class="fas fa-user-plus mr-1"></i>Tambah Pengguna Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="fullname" class="form-control" required minlength="3" maxlength="100"
                                       value="<?= old('fullname') ?>" placeholder="Nama lengkap pengguna">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" class="form-control" required minlength="3" maxlength="50"
                                       value="<?= old('username') ?>" placeholder="Username unik">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" required
                                       value="<?= old('email') ?>" placeholder="email@contoh.com">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-secondary btn-toggle-pw" tabindex="-1"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Role <span class="text-danger">*</span></label>
                                <select name="id_role" class="form-control" required>
                                    <option value="">-- Pilih Role --</option>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r['id_role'] ?>" <?= (old('id_role') == $r['id_role']) ? 'selected' : '' ?>>
                                            <?= esc($r['role_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIK KTP</label>
                                <input type="text" name="nik_ktp" class="form-control" maxlength="16" pattern="[0-9]*"
                                       value="<?= old('nik_ktp') ?>" placeholder="16 digit NIK">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sobat ID</label>
                                <input type="text" name="sobat_id" class="form-control" maxlength="50"
                                       value="<?= old('sobat_id') ?>" placeholder="ID Sobat BPS">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Supervisor (PML)</label>
                                <select name="id_supervisor" class="form-control">
                                    <option value="">-- Tanpa Supervisor --</option>
                                    <?php foreach ($supervisors as $s): ?>
                                        <option value="<?= $s['id_user'] ?>"><?= esc($s['fullname']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
