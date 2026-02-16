<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0">Setor Dokumen</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dokumen') ?>">Dokumen</a></li>
            <li class="breadcrumb-item active">Setor</li>
        </ol>
    </div>
</div>

<?php if (session()->getFlashdata('errors')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $e): ?>
            <li><?= esc($e) ?></li>
        <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file-upload mr-1"></i>Form Penyetoran Dokumen</h3>
            </div>

            <form action="<?= base_url('dokumen/store') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">
                    <div class="form-group">
                        <label>Kegiatan Survei <span class="text-danger">*</span></label>
                        <select name="id_kegiatan" class="form-control" required>
                            <option value="">-- Pilih Kegiatan Aktif --</option>
                            <?php foreach ($kegiatan as $k): ?>
                                <option value="<?= $k['id_kegiatan'] ?>"><?= esc($k['nama_kegiatan']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Kode Wilayah (NBS/ID SLS) <span class="text-danger">*</span></label>
                        <input type="text" name="kode_wilayah" class="form-control" placeholder="Contoh: 3509120001" required>
                        <small class="text-muted">Masukkan Kode Wilayah unik dari dokumen.</small>
                    </div>

                    <div class="form-group">
                        <label>Tanggal Setor <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_setor" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan</button>
                    <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#modal-import">
                        <i class="fas fa-file-excel mr-1"></i>Impor dari Excel
                    </button>
                    <a href="<?= base_url('dokumen') ?>" class="btn btn-default float-right">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<?= $this->include('dokumen/modal_import'); ?>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    var previewData = [];

    // Download Template
    $('#btn-download-template').on('click', function () {
        window.location.href = '<?= base_url('dokumen/download-template') ?>';
    });

    // Upload & Preview
    $('#btn-upload-preview').on('click', function () {
        var fileInput = $('#import_excel_file')[0];
        var idKegiatan = $('#import_id_kegiatan').val();
        var tanggalSetor = $('#import_tanggal_setor').val();

        if (!idKegiatan) { alert('Pilih Kegiatan terlebih dahulu.'); return; }
        if (!tanggalSetor) { alert('Pilih Tanggal Setor terlebih dahulu.'); return; }
        if (!fileInput.files.length) { alert('Pilih file Excel (.xlsx) terlebih dahulu.'); return; }

        var formData = new FormData();
        formData.append('excel_file', fileInput.files[0]);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        $('#import-status').html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses file...').show();
        $('#import-preview-area').hide();
        $('#btn-confirm-import').hide();

        $.ajax({
            url: '<?= base_url('dokumen/import-preview') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                if (!res.success) {
                    $('#import-status').html('<i class="fas fa-exclamation-circle text-danger mr-1"></i>' + res.message);
                    return;
                }

                previewData = res.data;
                var html = '';
                $.each(res.data, function (i, row) {
                    var cls = row.error ? 'table-danger' : '';
                    html += '<tr class="' + cls + '">';
                    html += '<td>' + (i + 1) + '</td>';
                    html += '<td>' + $('<span>').text(row.kode_wilayah).html() + '</td>';
                    html += '<td>' + (row.error ? '<span class="text-danger"><i class="fas fa-times-circle mr-1"></i>' + $('<span>').text(row.error).html() + '</span>' : '<span class="text-success"><i class="fas fa-check-circle"></i> OK</span>') + '</td>';
                    html += '</tr>';
                });
                $('#import-preview-body').html(html);
                $('#import-status').html(
                    '<i class="fas fa-info-circle text-info mr-1"></i>Total: <b>' + res.total + '</b> baris | ' +
                    '<span class="text-success"><b>' + res.valid + '</b> valid</span> | ' +
                    '<span class="text-danger"><b>' + res.errors + '</b> error</span>'
                );
                $('#import-preview-area').show();

                if (res.valid > 0) {
                    $('#btn-confirm-import').show();
                }
            },
            error: function () {
                $('#import-status').html('<i class="fas fa-exclamation-circle text-danger mr-1"></i>Gagal mengunggah file. Coba lagi.');
            }
        });
    });

    // Confirm Import
    $('#btn-confirm-import').on('click', function () {
        // Collect only valid kode_wilayah
        var validCodes = [];
        $.each(previewData, function (i, row) {
            if (!row.error) validCodes.push(row.kode_wilayah);
        });

        if (validCodes.length === 0) { alert('Tidak ada data valid untuk diimpor.'); return; }

        $('#import_kode_wilayah_list').val(JSON.stringify(validCodes));
        $('#form-import-confirm').submit();
    });
});
</script>
<?= $this->endSection(); ?>
