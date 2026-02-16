<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-file-export mr-2"></i>Penyetoran Dokumen</h1>
    </div>
    <div class="col-sm-6">
        <div class="float-sm-right">
            <?php if ($canCreate): ?>
                <a href="<?= base_url('penyetoran/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus mr-1"></i>Setor Dokumen Baru
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

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

<!-- Filter -->
<div class="card card-outline card-primary mb-3">
    <div class="card-body py-2">
        <form method="get" class="form-inline">
            <label class="mr-2">Filter Status:</label>
            <select name="status" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="Diserahkan" <?= ($filterStatus === 'Diserahkan') ? 'selected' : '' ?>>Diserahkan</option>
                <option value="Diterima" <?= ($filterStatus === 'Diterima') ? 'selected' : '' ?>>Diterima</option>
                <option value="Ditolak" <?= ($filterStatus === 'Ditolak') ? 'selected' : '' ?>>Ditolak</option>
            </select>
            <?php if ($filterStatus): ?>
                <a href="<?= base_url('penyetoran') ?>" class="btn btn-default btn-sm"><i class="fas fa-times"></i> Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-penyetoran" class="table table-bordered table-hover table-striped">
                <thead class="thead-light">
                    <tr>
                        <th width="50">#</th>
                        <th>Kegiatan</th>
                        <th>Pengirim</th>
                        <th>Tanggal</th>
                        <th>Jenis Dokumen</th>
                        <th class="text-center">Jml Dok</th>
                        <th class="text-center">Status</th>
                        <th width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $i => $s): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= esc($s['nama_kegiatan'] ?? '-') ?></td>
                        <td><?= esc($s['nama_pengirim']) ?></td>
                        <td><?= date('d/m/Y', strtotime($s['tanggal_penyerahan'])) ?></td>
                        <td><?= esc($s['jenis_dokumen']) ?></td>
                        <td class="text-center"><span class="badge badge-secondary"><?= (int) $s['jumlah_dokumen'] ?></span></td>
                        <td class="text-center">
                            <?php
                                $badge = 'warning';
                                $icon = 'clock';
                                if ($s['status'] === 'Diterima') { $badge = 'success'; $icon = 'check-circle'; }
                                elseif ($s['status'] === 'Ditolak') { $badge = 'danger'; $icon = 'times-circle'; }
                            ?>
                            <span class="badge badge-<?= $badge ?>"><i class="fas fa-<?= $icon ?> mr-1"></i><?= esc($s['status']) ?></span>
                        </td>
                        <td>
                            <a href="<?= base_url('penyetoran/detail/' . $s['id_penyetoran']) ?>" class="btn btn-info btn-xs">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    $('#table-penyetoran').DataTable({ responsive: true, autoWidth: false, order: [[0, 'asc']] });
});
</script>
<?= $this->endSection(); ?>
