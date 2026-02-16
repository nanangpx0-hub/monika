<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0"><i class="fas fa-file-upload mr-2"></i>Setor Dokumen Baru</h1>
    </div>
    <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('penyetoran') ?>">Penyetoran</a></li>
            <li class="breadcrumb-item active">Setor Baru</li>
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

<form action="<?= base_url('penyetoran/store') ?>" method="post" enctype="multipart/form-data" id="form-penyetoran">
    <?= csrf_field() ?>

    <!-- Card 1: Header Info -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i>Informasi Penyerahan</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Kegiatan Survei <span class="text-danger">*</span></label>
                        <select name="id_kegiatan" class="form-control" required>
                            <option value="">-- Pilih Kegiatan Aktif --</option>
                            <?php foreach ($kegiatan as $k): ?>
                                <option value="<?= $k['id_kegiatan'] ?>" <?= (old('id_kegiatan') == $k['id_kegiatan']) ? 'selected' : '' ?>>
                                    <?= esc($k['nama_kegiatan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Jenis Dokumen <span class="text-danger">*</span></label>
                        <input type="text" name="jenis_dokumen" class="form-control" placeholder="Contoh: Kuesioner Susenas" value="<?= old('jenis_dokumen') ?>" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Nama Pengirim (Tim Sosial) <span class="text-danger">*</span></label>
                        <input type="text" name="nama_pengirim" class="form-control" placeholder="Nama pengirim" value="<?= old('nama_pengirim') ?? esc(session()->get('fullname')) ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tanggal Penyerahan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_penyerahan" class="form-control" value="<?= old('tanggal_penyerahan') ?? date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>File Pendukung</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="file_pendukung" name="file_pendukung" accept=".pdf,.jpg,.png,.xlsx,.docx">
                            <label class="custom-file-label" for="file_pendukung">Pilih file...</label>
                        </div>
                        <small class="text-muted">Opsional. Format: PDF, JPG, PNG, XLSX, DOCX</small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)"><?= old('keterangan') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Card 2: Detail Rows -->
    <div class="card card-success card-outline">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-table mr-1"></i>Detail Dokumen (per Ruta)</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-success btn-sm mr-1" data-toggle="modal" data-target="#modal-import">
                    <i class="fas fa-file-excel mr-1"></i>Impor Excel
                </button>
                <a href="<?= base_url('penyetoran/download-template') ?>" class="btn btn-outline-success btn-sm mr-1">
                    <i class="fas fa-download mr-1"></i>Template
                </a>
                <button type="button" class="btn btn-primary btn-sm" id="btn-add-row">
                    <i class="fas fa-plus mr-1"></i>Tambah Baris
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" id="table-detail">
                    <thead class="bg-light">
                        <tr>
                            <th width="40" class="text-center">#</th>
                            <th width="100">Kode Prop</th>
                            <th width="100">Kode Kab</th>
                            <th width="120">Kode NKS</th>
                            <th width="100">No Ruta</th>
                            <th width="140">Status</th>
                            <th width="160">Tgl Penerimaan</th>
                            <th width="50"></th>
                        </tr>
                    </thead>
                    <tbody id="detail-body">
                        <!-- Dynamic rows -->
                    </tbody>
                </table>
            </div>
            <div class="p-2 text-muted text-center" id="empty-msg">
                <i class="fas fa-info-circle mr-1"></i>Belum ada data. Klik "Tambah Baris" atau "Impor Excel".
            </div>
        </div>
    </div>

    <!-- Submit -->
    <div class="mb-4">
        <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-paper-plane mr-1"></i>Setor Dokumen</button>
        <a href="<?= base_url('penyetoran') ?>" class="btn btn-default btn-lg ml-2">Batal</a>
    </div>
</form>

<!-- Import Modal -->
<div class="modal fade" id="modal-import" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title"><i class="fas fa-file-excel mr-1"></i>Impor dari Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>File Excel (.xlsx)</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="import_file" accept=".xlsx">
                            <label class="custom-file-label" for="import_file">Pilih file...</label>
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-info" id="btn-preview-import"><i class="fas fa-search mr-1"></i>Preview</button>
                        </div>
                    </div>
                </div>
                <div id="import-status" class="alert alert-light py-2" style="display:none;"></div>
                <div id="import-preview-area" style="display:none;">
                    <div class="table-responsive" style="max-height:300px; overflow-y:auto;">
                        <table class="table table-bordered table-sm">
                            <thead class="thead-light">
                                <tr><th>Prop</th><th>Kab</th><th>NKS</th><th>Ruta</th><th>Status</th><th>Tgl</th><th>Validasi</th></tr>
                            </thead>
                            <tbody id="import-preview-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btn-confirm-import" style="display:none;"><i class="fas fa-check mr-1"></i>Masukkan ke Form</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    var rowIdx = 0;
    var importData = [];

    // File input label
    $('#file_pendukung, #import_file').on('change', function () {
        $(this).next('.custom-file-label').html($(this).val().split('\\').pop() || 'Pilih file...');
    });

    function addRow(data) {
        data = data || {};
        rowIdx++;
        var html = '<tr>' +
            '<td class="text-center align-middle row-num">' + rowIdx + '</td>' +
            '<td><input type="text" name="kode_prop[]" class="form-control form-control-sm" maxlength="2" placeholder="35" value="' + (data.kode_prop || '') + '"></td>' +
            '<td><input type="text" name="kode_kab[]" class="form-control form-control-sm" maxlength="2" placeholder="09" value="' + (data.kode_kab || '') + '"></td>' +
            '<td><input type="text" name="kode_nks[]" class="form-control form-control-sm" maxlength="5" placeholder="00051" value="' + (data.kode_nks || '') + '" required></td>' +
            '<td><input type="number" name="no_urut_ruta[]" class="form-control form-control-sm" min="1" max="99" placeholder="1" value="' + (data.no_urut_ruta || '') + '" required></td>' +
            '<td><select name="status_selesai[]" class="form-control form-control-sm"><option value="belum"' + (data.status === 'sudah' ? '' : ' selected') + '>belum</option><option value="sudah"' + (data.status === 'sudah' ? ' selected' : '') + '>sudah</option></select></td>' +
            '<td><input type="date" name="tgl_penerimaan[]" class="form-control form-control-sm" value="' + (data.tgl || '') + '"></td>' +
            '<td><button type="button" class="btn btn-danger btn-xs btn-remove-row"><i class="fas fa-trash"></i></button></td>' +
            '</tr>';
        $('#detail-body').append(html);
        $('#empty-msg').hide();
        renumber();
    }

    function renumber() {
        $('#detail-body tr').each(function (i) { $(this).find('.row-num').text(i + 1); });
        rowIdx = $('#detail-body tr').length;
    }

    // Add Row
    $('#btn-add-row').on('click', function () { addRow(); });

    // Remove Row
    $(document).on('click', '.btn-remove-row', function () {
        $(this).closest('tr').remove();
        renumber();
        if ($('#detail-body tr').length === 0) $('#empty-msg').show();
    });

    // Import Preview
    $('#btn-preview-import').on('click', function () {
        var file = $('#import_file')[0].files[0];
        if (!file) { alert('Pilih file Excel.'); return; }

        var fd = new FormData();
        fd.append('excel_file', file);
        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        $('#import-status').html('<i class="fas fa-spinner fa-spin mr-1"></i>Memproses...').show();
        $('#import-preview-area').hide();
        $('#btn-confirm-import').hide();

        $.ajax({
            url: '<?= base_url('penyetoran/import-preview') ?>',
            type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json',
            success: function (res) {
                if (!res.success) { $('#import-status').html('<span class="text-danger"><i class="fas fa-times-circle mr-1"></i>' + res.message + '</span>'); return; }
                importData = res.data;
                var html = '';
                $.each(res.data, function (i, r) {
                    var cls = r.error ? 'table-danger' : '';
                    html += '<tr class="' + cls + '"><td>' + r.kode_prop + '</td><td>' + r.kode_kab + '</td><td>' + r.kode_nks + '</td><td>' + r.no_urut_ruta + '</td><td>' + r.status + '</td><td>' + (r.tgl || '-') + '</td>';
                    html += '<td>' + (r.error ? '<span class="text-danger">' + r.error + '</span>' : '<span class="text-success"><i class="fas fa-check"></i></span>') + '</td></tr>';
                });
                $('#import-preview-body').html(html);
                $('#import-status').html('Total: <b>' + res.total + '</b> | <span class="text-success">' + res.valid + ' valid</span> | <span class="text-danger">' + res.errors + ' error</span>');
                $('#import-preview-area').show();
                if (res.valid > 0) $('#btn-confirm-import').show();
            },
            error: function () { $('#import-status').html('<span class="text-danger">Gagal memproses file.</span>'); }
        });
    });

    // Confirm Import → Add to form
    $('#btn-confirm-import').on('click', function () {
        $.each(importData, function (i, r) {
            if (!r.error) addRow(r);
        });
        $('#modal-import').modal('hide');
    });

    // Add initial empty row
    addRow();
});
</script>
<?= $this->endSection(); ?>
