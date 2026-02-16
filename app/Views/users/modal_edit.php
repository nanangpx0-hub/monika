<!-- Modal Edit Pengguna -->
<div class="modal fade" id="modal-edit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="form-edit" method="post" action="">
            <?= csrf_field() ?>
            <input type="hidden" id="edit-id" name="id">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title"><i class="fas fa-user-edit mr-1"></i>Edit Pengguna</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="fullname" id="edit-fullname" class="form-control" required minlength="3" maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Username <span class="text-danger">*</span></label>
                                <input type="text" name="username" id="edit-username" class="form-control" required minlength="3" maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="edit-email" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Role <span class="text-danger">*</span></label>
                                <select name="id_role" id="edit-role" class="form-control" required>
                                    <option value="">-- Pilih Role --</option>
                                    <?php foreach ($roles as $r): ?>
                                        <option value="<?= $r['id_role'] ?>"><?= esc($r['role_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIK KTP</label>
                                <input type="text" name="nik_ktp" id="edit-nik" class="form-control" maxlength="16" pattern="[0-9]*">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Sobat ID</label>
                                <input type="text" name="sobat_id" id="edit-sobat" class="form-control" maxlength="50">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Supervisor (PML)</label>
                                <select name="id_supervisor" id="edit-supervisor" class="form-control">
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
                    <button type="submit" class="btn btn-info"><i class="fas fa-save mr-1"></i>Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>
