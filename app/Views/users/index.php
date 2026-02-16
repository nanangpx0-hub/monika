<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-check-circle mr-1"></i><?= esc((string) session()->getFlashdata('success')) ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-circle mr-1"></i><?= esc((string) session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <?php $errors = session()->getFlashdata('errors'); ?>
        <?php if (! empty($errors)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <ul class="mb-0 pl-3">
                    <?php foreach ((array) $errors as $error): ?>
                        <li><?= esc((string) $error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Filter Card -->
        <div class="card card-outline card-primary mb-3">
            <div class="card-header py-2">
                <h3 class="card-title"><i class="fas fa-filter mr-1"></i>Filter</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body py-2">
                <form method="get" action="<?= base_url('users') ?>" class="form-inline">
                    <div class="form-group mr-2">
                        <label class="mr-2">Role:</label>
                        <select name="role" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Role --</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id_role'] ?>" <?= ($filterRole == $r['id_role']) ? 'selected' : '' ?>>
                                    <?= esc($r['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php if ($filterRole): ?>
                        <a href="<?= base_url('users') ?>" class="btn btn-sm btn-outline-secondary"><i class="fas fa-times mr-1"></i>Reset</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <!-- User Table -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users-cog mr-1"></i>Daftar Pengguna</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modal-create">
                        <i class="fas fa-user-plus mr-1"></i>Tambah Pengguna
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table-users" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="40">No</th>
                                <th>Nama Lengkap</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th width="70">Status</th>
                                <th width="200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $i => $u): ?>
                            <tr>
                                <td><?= $i + 1 ?></td>
                                <td><?= esc($u['fullname'] ?? '-') ?></td>
                                <td><code><?= esc($u['username']) ?></code></td>
                                <td><?= esc($u['email']) ?></td>
                                <td><span class="badge badge-info"><?= esc($u['role_name'] ?? 'N/A') ?></span></td>
                                <td class="text-center">
                                    <?php if ((int)($u['is_active'] ?? 1) === 1): ?>
                                        <span class="badge badge-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Non-Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <!-- Edit -->
                                    <button type="button" class="btn btn-info btn-xs btn-edit"
                                        data-id="<?= $u['id_user'] ?>"
                                        data-fullname="<?= esc($u['fullname'] ?? '') ?>"
                                        data-username="<?= esc($u['username']) ?>"
                                        data-email="<?= esc($u['email']) ?>"
                                        data-role="<?= $u['id_role'] ?>"
                                        data-nik="<?= esc($u['nik_ktp'] ?? '') ?>"
                                        data-sobat="<?= esc($u['sobat_id'] ?? '') ?>"
                                        data-supervisor="<?= $u['id_supervisor'] ?? '' ?>"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Reset Password -->
                                    <button type="button" class="btn btn-warning btn-xs btn-reset-pw"
                                        data-id="<?= $u['id_user'] ?>"
                                        data-username="<?= esc($u['username']) ?>"
                                        title="Reset Password">
                                        <i class="fas fa-key"></i>
                                    </button>

                                    <!-- Toggle Active -->
                                    <?php if ((int) $u['id_user'] !== (int) session()->get('id_user')): ?>
                                    <form action="<?= base_url('users/toggle-active/' . $u['id_user']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <?php if ((int)($u['is_active'] ?? 1) === 1): ?>
                                            <button type="submit" class="btn btn-secondary btn-xs" onclick="return confirm('Nonaktifkan pengguna ini?')" title="Nonaktifkan">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button type="submit" class="btn btn-success btn-xs" onclick="return confirm('Aktifkan kembali pengguna ini?')" title="Aktifkan">
                                                <i class="fas fa-user-check"></i>
                                            </button>
                                        <?php endif; ?>
                                    </form>

                                    <!-- Delete -->
                                    <form action="<?= base_url('users/delete/' . $u['id_user']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('HAPUS pengguna <?= esc($u['username']) ?>? Tindakan ini tidak dapat dibatalkan!')" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('users/modal_create'); ?>
<?= $this->include('users/modal_edit'); ?>

<!-- Reset Password Modal -->
<div class="modal fade" id="modal-reset-pw" tabindex="-1">
    <div class="modal-dialog">
        <form id="form-reset-pw" method="post" action="">
            <?= csrf_field() ?>
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title"><i class="fas fa-key mr-1"></i>Reset Password</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Reset password untuk: <strong id="reset-pw-username"></strong></p>
                    <div class="form-group">
                        <label>Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" name="new_password" id="new_password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary btn-toggle-pw" tabindex="-1"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save mr-1"></i>Reset Password</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    // DataTable
    $('#table-users').DataTable({
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        pageLength: 10,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ pengguna",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Pengguna tidak ditemukan",
            paginate: { first: "Pertama", last: "Terakhir", next: "»", previous: "«" }
        }
    });

    // Edit Modal — populate from data attributes
    $(document).on('click', '.btn-edit', function () {
        var btn = $(this);
        $('#edit-id').val(btn.data('id'));
        $('#edit-fullname').val(btn.data('fullname'));
        $('#edit-username').val(btn.data('username'));
        $('#edit-email').val(btn.data('email'));
        $('#edit-role').val(btn.data('role'));
        $('#edit-nik').val(btn.data('nik'));
        $('#edit-sobat').val(btn.data('sobat'));
        $('#edit-supervisor').val(btn.data('supervisor'));
        $('#form-edit').attr('action', '<?= base_url('users/update') ?>/' + btn.data('id'));
        $('#modal-edit').modal('show');
    });

    // Reset Password Modal
    $(document).on('click', '.btn-reset-pw', function () {
        var btn = $(this);
        $('#reset-pw-username').text(btn.data('username'));
        $('#new_password').val('');
        $('#form-reset-pw').attr('action', '<?= base_url('users/reset-password') ?>/' + btn.data('id'));
        $('#modal-reset-pw').modal('show');
    });

    // Toggle password visibility
    $(document).on('click', '.btn-toggle-pw', function () {
        var input = $(this).closest('.input-group').find('input');
        var icon = $(this).find('i');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
});
</script>
<?= $this->endSection(); ?>
