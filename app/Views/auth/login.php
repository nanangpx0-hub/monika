<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?> | MONIKA</title>

    <!-- Custom Modern Auth Styles -->
    <link rel="stylesheet" href="<?= base_url('assets/css/auth-modern.css') ?>">
    <!-- Font Awesome (for Icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>

    <!-- Animated Background Shapes -->
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <a href="<?= base_url() ?>" class="brand-logo">MONIKA</a>
                <p class="auth-subtitle">Welcome back! Please sign in to continue.</p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= session()->getFlashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <span><?= session()->getFlashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="post" id="loginForm">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="login_id" class="form-label">Email or Username</label>
                    <div class="input-wrapper">
                        <input type="text" name="login_id" id="login_id" class="form-control"
                            placeholder="Enter your email or username" required autofocus>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter your password" required>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="custom-checkbox">
                        <input type="checkbox" name="remember">
                        <span>Remember me</span>
                    </label>
                    <!-- Note: Add logic later if needed or link to specific recovery page -->
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-primary" id="submitBtn">
                    <div class="spinner"></div>
                    <span class="btn-text">Sign In</span>
                    <i class="fas fa-arrow-right btn-icon" style="margin-left: 0.5rem;"></i>
                </button>
            </form>

            <div class="auth-footer">
                <p style="margin-top: 0.5rem; font-size: 0.75rem; opacity: 0.6;">&copy; <?= date('Y') ?> BPS Kabupaten
                    Jember</p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Password Toggle
            const togglePassword = document.querySelector('#togglePassword');
            const password = document.querySelector('#password');

            togglePassword.addEventListener('click', function (e) {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);

                // Toggle eye icon
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });

            // Loading State
            const loginForm = document.getElementById('loginForm');
            const submitBtn = document.getElementById('submitBtn');
            const btnIcon = submitBtn.querySelector('.btn-icon');

            loginForm.addEventListener('submit', function () {
                submitBtn.classList.add('btn-loading');
                submitBtn.setAttribute('disabled', 'disabled');
                if (btnIcon) btnIcon.style.display = 'none';
            });
        });
    </script>
</body>

</html>