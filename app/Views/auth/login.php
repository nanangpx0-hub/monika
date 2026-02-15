<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'MONIKA LOGIN'); ?></title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo text-center">
        <img src="https://via.placeholder.com/100x100.png?text=BPS" alt="BPS Logo" class="img-fluid mb-2" style="max-height: 70px;">
        <br>
        <a href="<?= base_url(); ?>"><b>MONIKA</b> LOGIN</a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Masuk ke sistem MONIKA</p>

            <?php
                $successMessage = (string) (session()->getFlashdata('success') ?? '');
                if ($successMessage === '' && service('request')->getGet('logout') === '1') {
                    $successMessage = 'Logout berhasil.';
                }
            ?>

            <?php if ($successMessage !== ''): ?>
                <div class="alert alert-success" role="alert">
                    <?= esc($successMessage); ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-warning" role="alert">
                    <?= esc((string) session()->getFlashdata('error')); ?>
                </div>
            <?php endif; ?>

            <form id="loginForm" method="post" action="<?= base_url('auth/login'); ?>" novalidate>
                <?= csrf_field(); ?>
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username atau Email" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember_me" name="remember_me" value="1">
                            <label for="remember_me">Remember Me</label>
                        </div>
                    </div>
                </div>

                <button type="submit" id="btnLogin" class="btn btn-primary btn-block">
                    <span class="btn-text"><i class="fas fa-sign-in-alt"></i> Login</span>
                    <span class="btn-loading d-none"><i class="fas fa-spinner fa-spin"></i> Memproses...</span>
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(() => {
    const form = document.getElementById('loginForm');
    const btnLogin = document.getElementById('btnLogin');
    const btnText = btnLogin.querySelector('.btn-text');
    const btnLoading = btnLogin.querySelector('.btn-loading');
    const csrfInput = form.querySelector('input[name="<?= csrf_token(); ?>"]');

    function setLoading(loading) {
        btnLogin.disabled = loading;
        btnText.classList.toggle('d-none', loading);
        btnLoading.classList.toggle('d-none', !loading);
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        setLoading(true);

        try {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfInput ? csrfInput.value : ''
                },
                body: formData
            });

            const payload = await response.json();

            if (payload && payload.csrf_hash && csrfInput) {
                csrfInput.value = payload.csrf_hash;
            }

            if (!response.ok || payload.status !== 'success') {
                await Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    text: payload.message || 'Username atau password salah.'
                });
                setLoading(false);
                return;
            }

            await Swal.fire({
                icon: 'success',
                title: 'Login Berhasil',
                text: 'Mengalihkan ke dashboard...',
                timer: 1200,
                showConfirmButton: false
            });

            window.location.href = payload.redirect || '<?= base_url('dashboard'); ?>';
        } catch (error) {
            await Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: 'Tidak dapat terhubung ke server.'
            });
            setLoading(false);
        }
    });
})();
</script>
</body>
</html>
