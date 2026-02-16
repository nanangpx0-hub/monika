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
    <style>
        /* Password Toggle Visibility */
        .password-input-group {
            flex-wrap: nowrap;
            align-items: stretch;
        }

        .password-field {
            position: relative;
            display: flex;
            align-items: center;
            flex: 1 1 auto;
            min-width: 0;
        }

        .password-field input {
            width: 100%;
            padding-right: 44px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .password-icon-addon .input-group-text {
            width: 42px;
            justify-content: center;
            color: #6c757d;
            background-color: #f8f9fa;
        }

        .password-icon-addon .input-group-text .fas {
            font-size: 14px;
            line-height: 1;
        }

        .password-input-group:focus-within .password-icon-addon .input-group-text {
            border-color: #80bdff;
            color: #495057;
        }

        .password-toggle-btn {
            position: absolute;
            right: 6px;
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            font-size: 18px;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease, transform 0.2s ease;
            z-index: 10;
            width: 40px;
            height: 40px;
            border-radius: 4px;
        }

        .password-toggle-btn:hover {
            color: #495057;
            background-color: rgba(0, 0, 0, 0.03);
        }

        .password-toggle-btn:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
            background-color: rgba(0, 0, 0, 0.03);
        }

        .password-toggle-btn:active {
            transform: scale(0.95);
        }

        .password-toggle-btn.show {
            color: #007bff;
        }

        /* Icon animation */
        .password-toggle-btn i {
            transition: opacity 0.2s ease;
        }

        /* Mobile responsiveness */
        @media (max-width: 576px) {
            .password-toggle-btn {
                width: 36px;
                height: 36px;
                font-size: 16px;
                right: 4px;
            }

            .password-icon-addon .input-group-text {
                width: 38px;
            }

            .password-icon-addon .input-group-text .fas {
                font-size: 13px;
            }

            .password-field input {
                padding-right: 40px;
            }
        }

        /* High contrast mode support */
        @media (prefers-contrast: more) {
            .password-toggle-btn {
                border: 1px solid #6c757d;
            }

            .password-toggle-btn:focus {
                outline-width: 3px;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            .password-toggle-btn {
                transition: none;
            }

            .password-toggle-btn i {
                transition: none;
            }
        }
    </style>
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

                <div class="input-group mb-3 password-input-group">
                    <div class="password-field">
                        <input 
                            type="password" 
                            id="passwordInput" 
                            name="password" 
                            class="form-control" 
                            placeholder="Password" 
                            required
                            autocomplete="current-password"
                        >
                        <button 
                            type="button" 
                            class="password-toggle-btn" 
                            id="passwordToggle"
                            aria-label="Tampilkan password"
                            title="Tampilkan/Sembunyikan password (tekan Enter atau Space)"
                            tabindex="0"
                        >
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="input-group-append password-icon-addon">
                        <div class="input-group-text">
                            <span class="fas fa-lock" aria-hidden="true"></span>
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
    /**
     * Password Visibility Toggle Feature
     * Supports keyboard navigation (Tab, Enter, Space)
     * Screen reader accessible with aria-label
     * Cross-browser compatible and mobile responsive
     */
    (() => {
        // Password toggle functionality
        const passwordInput = document.getElementById('passwordInput');
        const passwordToggle = document.getElementById('passwordToggle');
        let isPasswordVisible = false;

        // Update aria-label and icon based on visibility state
        function updatePasswordVisibility() {
            if (isPasswordVisible) {
                passwordInput.type = 'text';
                passwordToggle.classList.add('show');
                passwordToggle.setAttribute('aria-label', 'Sembunyikan password');
                passwordToggle.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                passwordToggle.classList.remove('show');
                passwordToggle.setAttribute('aria-label', 'Tampilkan password');
                passwordToggle.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }

        // Toggle password visibility on button click
        passwordToggle.addEventListener('click', (e) => {
            e.preventDefault();
            isPasswordVisible = !isPasswordVisible;
            updatePasswordVisibility();
            // Keep focus on toggle button
            passwordToggle.focus();
        });

        // Support keyboard navigation (Enter and Space)
        passwordToggle.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                isPasswordVisible = !isPasswordVisible;
                updatePasswordVisibility();
            }
        });

        // Optional: Allow Alt+P or Ctrl+Shift+P to toggle password (keyboard shortcut)
        document.addEventListener('keydown', (e) => {
            if ((e.altKey && e.key === 'p') || (e.ctrlKey && e.shiftKey && e.key === 'P')) {
                e.preventDefault();
                isPasswordVisible = !isPasswordVisible;
                updatePasswordVisibility();
                passwordToggle.focus();
            }
        });

        // Maintain visibility state during form validation
        // If form loses focus and regains, state should persist
        passwordInput.addEventListener('blur', () => {
            // Keep the current state
        });

        // Initialize password field on page load
        updatePasswordVisibility();

        // Login form functionality
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
