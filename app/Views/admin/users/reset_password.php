<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-key"></i> Reset Password
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('admin/users') ?>" class="btn btn-default btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <?php if (session('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <div class="callout callout-info">
                    <h5>Informasi User</h5>
                    <p>
                        <strong>Nama:</strong> <?= esc($user['fullname']) ?><br>
                        <strong>Username:</strong> <?= esc($user['username']) ?><br>
                        <strong>Email:</strong> <?= esc($user['email']) ?>
                    </p>
                </div>

                <form action="<?= base_url('admin/users/reset-password/' . $user['id_user']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="new_password" name="new_password" 
                                   minlength="6" placeholder="Masukkan password baru...">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary" onclick="generatePassword()">
                                    <i class="fas fa-random"></i> Generate
                                </button>
                            </div>
                        </div>
                        <small class="text-muted">Password minimal 6 karakter. Klik Generate untuk membuat password acak.</small>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="generate_random" name="generate_random" value="1">
                            <label class="custom-control-label" for="generate_random">
                                Generate password secara otomatis (abaikan input di atas)
                            </label>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Perhatian:</strong> Password baru akan langsung berlaku setelah direset. 
                        Pastikan untuk memberitahu user mengenai password baru mereka.
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key"></i> Reset Password
                        </button>
                        <a href="<?= base_url('admin/users') ?>" class="btn btn-default">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function generatePassword() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%&*';
    let password = '';
    for (let i = 0; i < 10; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    document.getElementById('new_password').value = password;
    document.getElementById('new_password').type = 'text';
}
</script>
<?= $this->endSection() ?>
