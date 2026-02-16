<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Dokumen Survei</h1>
    </div>
    <div class="col-sm-6">
        <div class="float-sm-right">
            <?php if (in_array((int) $role_id, [1, 3], true)): ?>
                <button type="button" class="btn btn-success mr-1" data-toggle="modal" data-target="#modal-import">
                    <i class="fas fa-file-excel"></i> Impor Excel
                </button>
                <a href="<?= base_url('dokumen/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Setor Dokumen
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success" role="alert">
        <?= esc((string) session()->getFlashdata('success')) ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger" role="alert">
        <?= esc((string) session()->getFlashdata('error')) ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Dokumen</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table-dokumen" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kegiatan</th>
                        <th>Wilayah</th>
                        <th>PCL</th>
                        <th>Tgl Setor</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dokumen as $d): ?>
                        <tr>
                            <td><?= esc((string) $d['id_dokumen']) ?></td>
                            <td><?= esc((string) $d['nama_kegiatan']) ?></td>
                            <td><?= esc((string) $d['kode_wilayah']) ?></td>
                            <td><?= esc((string) $d['nama_pcl']) ?></td>
                            <td><?= esc((string) $d['tanggal_setor']) ?></td>
                            <td>
                                <?php
                                    $badge = 'secondary';
                                    if ($d['status'] === 'Uploaded') {
                                        $badge = 'info';
                                    } elseif ($d['status'] === 'Sudah Entry') {
                                        $badge = 'primary';
                                    } elseif ($d['status'] === 'Error') {
                                        $badge = 'danger';
                                    } elseif ($d['status'] === 'Valid') {
                                        $badge = 'success';
                                    }
                                ?>
                                <span class="badge badge-<?= $badge ?>"><?= esc((string) $d['status']) ?></span>
                            </td>
                            <td>
                                <?php if (in_array((int) $role_id, [1, 4], true) && $d['status'] === 'Uploaded'): ?>
                                    <form action="<?= base_url('dokumen/mark-entry/' . $d['id_dokumen']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-primary btn-xs" onclick="return confirm('Tandai sudah entry?')">
                                            <i class="fas fa-check"></i> Entry
                                        </button>
                                    </form>

                                    <button
                                        type="button"
                                        class="btn btn-danger btn-xs btn-error"
                                        data-id="<?= esc((string) $d['id_dokumen']) ?>"
                                        data-toggle="modal"
                                        data-target="#modal-error"
                                    >
                                        <i class="fas fa-exclamation-triangle"></i> Error
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->include('dokumen/modal_error'); ?>
<?= $this->include('dokumen/modal_import'); ?>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    $('#table-dokumen').DataTable({
        responsive: true,
        autoWidth: false
    });

    $('.btn-error').on('click', function () {
        $('#error_id_dokumen').val($(this).data('id'));
    });
});
</script>
<?= $this->endSection(); ?>
