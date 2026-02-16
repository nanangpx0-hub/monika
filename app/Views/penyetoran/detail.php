<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-file-alt mr-2"></i><?= esc($title) ?></h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('penyetoran') ?>">Penyetoran</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>
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

<?php
    $s = $submission;
    $badge = 'warning'; $icon = 'clock';
    if ($s['status'] === 'Diterima') { $badge = 'success'; $icon = 'check-circle'; }
    elseif ($s['status'] === 'Ditolak') { $badge = 'danger'; $icon = 'times-circle'; }
?>

<div class="row">
    <!-- Header Info -->
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Info Penyerahan</h3>
                <span class="float-right badge badge-<?= $badge ?> px-3 py-1" style="font-size:0.9em;">
                    <i class="fas fa-<?= $icon ?> mr-1"></i><?= esc($s['status']) ?>
                </span>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr><th width="160">Kegiatan</th><td><?= esc($s['nama_kegiatan'] ?? '-') ?></td></tr>
                    <tr><th>Jenis Dokumen</th><td><?= esc($s['jenis_dokumen']) ?></td></tr>
                    <tr><th>Pengirim</th><td><i class="fas fa-user mr-1 text-muted"></i><?= esc($s['nama_pengirim']) ?></td></tr>
                    <tr><th>Diserahkan oleh</th><td><?= esc($s['nama_penyerah'] ?? '-') ?></td></tr>
                    <tr><th>Tanggal Penyerahan</th><td><?= date('d/m/Y', strtotime($s['tanggal_penyerahan'])) ?></td></tr>
                    <tr><th>Jumlah Dokumen</th><td><span class="badge badge-info"><?= count($s['details']) ?> ruta</span></td></tr>
                    <tr><th>Keterangan</th><td><?= esc($s['keterangan'] ?? '-') ?></td></tr>
                    <?php if ($s['file_pendukung']): ?>
                    <tr><th>File Pendukung</th><td><a href="<?= base_url('writable/' . $s['file_pendukung']) ?>" target="_blank" class="btn btn-outline-primary btn-xs"><i class="fas fa-download mr-1"></i>Download</a></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>

    <!-- Confirmation Panel -->
    <div class="col-md-6">
        <?php if ($s['status'] !== 'Diserahkan'): ?>
            <div class="card card-<?= $badge ?> card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clipboard-check mr-1"></i>Konfirmasi Penerimaan</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm mb-0">
                        <tr><th width="160">Status</th><td><span class="badge badge-<?= $badge ?>"><?= esc($s['status']) ?></span></td></tr>
                        <tr><th>Diterima oleh</th><td><?= esc($s['nama_penerima'] ?? '-') ?></td></tr>
                        <tr><th>Tanggal Konfirmasi</th><td><?= $s['tanggal_konfirmasi'] ? date('d/m/Y H:i', strtotime($s['tanggal_konfirmasi'])) : '-' ?></td></tr>
                        <tr><th>Catatan PLS</th><td><?= esc($s['catatan_penerima'] ?? '-') ?></td></tr>
                    </table>
                </div>
            </div>
        <?php elseif ($canConfirm): ?>
            <div class="card card-warning card-outline">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-clipboard-check mr-1"></i>Konfirmasi oleh Tim PLS</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('penyetoran/confirm/' . $s['id_penyetoran']) ?>">
                        <?= csrf_field() ?>
                        <div class="form-group">
                            <label>Catatan Penerima</label>
                            <textarea name="catatan_penerima" class="form-control" rows="3" placeholder="Catatan opsional..."></textarea>
                        </div>
                        <div class="btn-group w-100">
                            <button type="submit" name="status" value="Diterima" class="btn btn-success btn-lg w-50" onclick="return confirm('Konfirmasi terima dokumen ini?')">
                                <i class="fas fa-check mr-1"></i>Terima
                            </button>
                            <button type="submit" name="status" value="Ditolak" class="btn btn-danger btn-lg w-50" onclick="return confirm('Yakin tolak dokumen ini?')">
                                <i class="fas fa-times mr-1"></i>Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="callout callout-warning">
                <h5><i class="fas fa-info-circle mr-1"></i>Menunggu Konfirmasi</h5>
                <p>Dokumen ini sedang menunggu konfirmasi penerimaan oleh Tim PLS.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Detail Table -->
<div class="card card-outline card-dark">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table mr-1"></i>Data Progress Penerimaan Kuesioner</h3>
        <span class="float-right text-muted">Total: <?= count($s['details']) ?> baris</span>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm mb-0" id="table-detail">
                <thead style="background-color: #E65100; color: white;">
                    <tr>
                        <th class="text-center" width="40">#</th>
                        <th class="text-center">Kode Prop</th>
                        <th class="text-center">Kode Kab</th>
                        <th class="text-center">Kode NKS</th>
                        <th class="text-center">No Urut Ruta</th>
                        <th class="text-center">Sudah Selesai?</th>
                        <th class="text-center">TGL_PENERIMAAN</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($s['details'] as $i => $d): ?>
                    <?php
                        $rowBg = ($d['status_selesai'] === 'sudah') ? 'background-color: #4CAF50; color: white;' : '';
                    ?>
                    <tr style="<?= $rowBg ?>">
                        <td class="text-center"><?= $i + 1 ?></td>
                        <td class="text-center"><?= esc($d['kode_prop']) ?></td>
                        <td class="text-center"><?= esc($d['kode_kab']) ?></td>
                        <td class="text-center"><code style="<?= $rowBg ? 'color:white;' : '' ?>"><?= esc($d['kode_nks']) ?></code></td>
                        <td class="text-center"><?= (int) $d['no_urut_ruta'] ?></td>
                        <td class="text-center"><strong><?= esc($d['status_selesai']) ?></strong></td>
                        <td class="text-center"><?= $d['tgl_penerimaan'] ? date('d-m-Y', strtotime($d['tgl_penerimaan'])) : '' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<a href="<?= base_url('penyetoran') ?>" class="btn btn-default mb-4"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    $('#table-detail').DataTable({ responsive: true, autoWidth: false, paging: true, pageLength: 25, order: [[3, 'asc'], [4, 'asc']] });
});
</script>
<?= $this->endSection(); ?>
