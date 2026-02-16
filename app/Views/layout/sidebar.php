<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url() ?>" class="brand-link">
        <img src="https://upload.wikimedia.org/wikipedia/commons/2/28/Lambang_Badan_Pusat_Statistik_%28BPS%29_Indonesia.svg" 
             alt="BPS Logo" 
             class="brand-image img-circle elevation-3" 
             style="opacity: .8; background-color: white;">
        <span class="brand-text font-weight-light"><b>MONIKA</b> JEMBER</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <?php
            $sessionRole = (string) (session()->get('id_role') ?? session()->get('role') ?? '');
            $isAdmin = in_array(strtolower($sessionRole), ['1', 'admin'], true);
        ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="<?= base_url('/') ?>" class="nav-link <?= (uri_string() == '' || uri_string() == '/') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- MENU LOGISTIK -->
                <li class="nav-header">LOGISTIK</li>
                
                <li class="nav-item">
                    <!-- Menu Tanda Terima -->
                    <a href="<?= base_url('tanda-terima') ?>" class="nav-link <?= (strpos(uri_string(), 'tanda-terima') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-signature"></i>
                        <p>Tanda Terima</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('logistik') ?>" class="nav-link <?= (strpos(uri_string(), 'logistik') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Logistik</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('dokumen') ?>" class="nav-link <?= (strpos(uri_string(), 'dokumen') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Dokumen</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('penyetoran') ?>" class="nav-link <?= (strpos(uri_string(), 'penyetoran') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-export"></i>
                        <p>Penyetoran Dokumen</p>
                    </a>
                </li>
                
                <!-- MENU PENGOLAHAN -->
                <li class="nav-header">PENGOLAHAN</li>

                <li class="nav-item">
                    <!-- Menu Presensi -->
                    <a href="<?= base_url('presensi') ?>" class="nav-link <?= (strpos(uri_string(), 'presensi') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-check"></i>
                        <p>Presensi</p>
                    </a>
                </li>

                <li class="nav-item">
                    <!-- Menu Kartu Kendali -->
                    <a href="<?= base_url('kartu-kendali') ?>" class="nav-link <?= (strpos(uri_string(), 'kartu-kendali') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Kartu Kendali</p>
                    </a>
                </li>

                <!-- MENU KUALITAS -->
                <li class="nav-header">KUALITAS</li>

                <li class="nav-item">
                    <!-- Menu Uji Petik -->
                    <a href="<?= base_url('uji-petik') ?>" class="nav-link <?= (strpos(uri_string(), 'uji-petik') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-vial"></i>
                        <p>Uji Petik</p>
                    </a>
                </li>

                <?php if ($isAdmin): ?>
                <li class="nav-header">MANAJEMEN</li>

                <li class="nav-item">
                    <a href="<?= base_url('kegiatan') ?>" class="nav-link <?= (strpos(uri_string(), 'kegiatan') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Kegiatan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('laporan') ?>" class="nav-link <?= (strpos(uri_string(), 'laporan') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Laporan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('monitoring') ?>" class="nav-link <?= (strpos(uri_string(), 'monitoring') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-pie"></i>
                        <p>Monitoring</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url('users') ?>" class="nav-link <?= (strpos(uri_string(), 'users') !== false) ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-users-cog"></i>
                        <p>Kelola Pengguna</p>
                    </a>
                </li>
                <?php endif; ?>

                <?php if (session()->get('is_logged_in')): ?>
                <li class="nav-header">AKUN</li>
                <li class="nav-item">
                    <a href="<?= base_url('logout'); ?>" class="nav-link monika-sidebar-logout" role="button" aria-label="Logout">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
