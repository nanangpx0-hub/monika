<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MONIKA | BPS Kab. Jember</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/monika-ui.css'); ?>">

    <style>
        .bg-bps-blue { background: #0099d8; }
        .bg-bps-orange { background: #f7941d; }
        .bg-bps-green { background: #8cc63f; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<?php if (session()->get('is_logged_in')): ?>
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Buka menu navigasi">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link monika-logout-link" href="<?= base_url('logout'); ?>" role="button" aria-label="Logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="logout-text">Logout</span>
                </a>
            </li>
        </ul>
    </nav>

    <a class="monika-logout-fab" href="<?= base_url('logout'); ?>" role="button" aria-label="Logout cepat">
        <i class="fas fa-sign-out-alt"></i>
        <span class="logout-text">Logout</span>
    </a>
<?php endif; ?>
