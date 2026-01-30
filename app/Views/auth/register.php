<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title ?? 'Register' ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/icheck-bootstrap/3.0.1/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">
  <div class="register-box">
    <div class="register-logo">
      <a href="<?= base_url() ?>"><b>MONIKA</b>System</a>
    </div>

    <div class="card">
      <div class="card-body register-card-body">
        <p class="login-box-msg">Register a new membership (Mitra)</p>

        <form action="<?= base_url('register') ?>" method="post">
          <?= csrf_field() ?>

          <div class="input-group mb-3">
            <input type="text" name="fullname"
              class="form-control <?= session('errors.fullname') ? 'is-invalid' : '' ?>" placeholder="Full name"
              value="<?= old('fullname') ?>" required minlength="3" maxlength="100">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
            <?php if (session('errors.fullname')): ?>
              <div class="invalid-feedback"><?= session('errors.fullname') ?></div>
            <?php endif ?>
          </div>

          <div class="input-group mb-3">
            <input type="text" name="username"
              class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" placeholder="Username"
              value="<?= old('username') ?>" required minlength="3" maxlength="50">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user-tag"></span>
              </div>
            </div>
            <?php if (session('errors.username')): ?>
              <div class="invalid-feedback"><?= session('errors.username') ?></div>
            <?php endif ?>
          </div>

          <div class="input-group mb-3">
            <input type="email" name="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>"
              placeholder="Email" value="<?= old('email') ?>" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
            <?php if (session('errors.email')): ?>
              <div class="invalid-feedback"><?= session('errors.email') ?></div>
            <?php endif ?>
          </div>

          <div class="input-group mb-3">
            <select name="id_role" class="form-control <?= session('errors.id_role') ? 'is-invalid' : '' ?>" required>
              <option value="">-- Select Role --</option>
              <option value="3" <?= old('id_role') == 3 ? 'selected' : '' ?>>Petugas Pendataan (PCL)</option>
              <option value="4" <?= old('id_role') == 4 ? 'selected' : '' ?>>Petugas Pengolahan</option>
            </select>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-users"></span>
              </div>
            </div>
            <?php if (session('errors.id_role')): ?>
              <div class="invalid-feedback"><?= session('errors.id_role') ?></div>
            <?php endif ?>
          </div>

          <div class="input-group mb-3">
            <input type="text" name="nik_ktp" class="form-control <?= session('errors.nik_ktp') ? 'is-invalid' : '' ?>"
              placeholder="NIK (16 Digit)" value="<?= old('nik_ktp') ?>" required pattern="\d{16}" minlength="16"
              maxlength="16" title="NIK harus terdiri dari 16 digit angka" inputmode="numeric">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-id-card"></span>
              </div>
            </div>
            <?php if (session('errors.nik_ktp')): ?>
              <div class="invalid-feedback"><?= session('errors.nik_ktp') ?></div>
            <?php endif ?>
          </div>

          <div class="input-group mb-3">
            <input type="text" name="sobat_id"
              class="form-control <?= session('errors.sobat_id') ? 'is-invalid' : '' ?>" placeholder="Sobat ID"
              value="<?= old('sobat_id') ?>" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-id-badge"></span>
              </div>
            </div>
            <?php if (session('errors.sobat_id')): ?>
              <div class="invalid-feedback"><?= session('errors.sobat_id') ?></div>
            <?php endif ?>
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password"
              class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" placeholder="Password" required
              minlength="6">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <?php if (session('errors.password')): ?>
              <div class="invalid-feedback"><?= session('errors.password') ?></div>
            <?php endif ?>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="confpassword"
              class="form-control <?= session('errors.confpassword') ? 'is-invalid' : '' ?>"
              placeholder="Retype password" required minlength="6">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <?php if (session('errors.confpassword')): ?>
              <div class="invalid-feedback"><?= session('errors.confpassword') ?></div>
            <?php endif ?>
          </div>

          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="agreeTerms" name="terms" value="agree" required>
                <label for="agreeTerms">
                  I agree to the <a href="#">terms</a>
                </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Register</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <a href="<?= base_url('login') ?>" class="text-center">I already have a membership</a>
      </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
  <!-- /.register-box -->

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>