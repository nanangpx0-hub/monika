<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <!-- NKS Info Card -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-map-marker-alt"></i> 
                    Detail Kartu Kendali - NKS <?= esc($nks) ?>
                </h3>
                <div class="card-tools">
                    <a href="<?= base_url('kartu-kendali') ?>" class="btn btn-sm btn-light">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong><i class="fas fa-building"></i> Kecamatan:</strong><br>
                        <?= esc($nks_info['kecamatan']) ?>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-home"></i> Desa:</strong><br>
                        <?= esc($nks_info['desa']) ?>
                    </div>
                    <div class="col-md-4">
                        <strong><i class="fas fa-inbox"></i> Dokumen Diterima:</strong><br>
                        <span class="badge badge-info"><?= $jml_ruta_terima ?> dari 10 Ruta</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ruta Grid -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-th"></i> Grid Entry Ruta (1-10)</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($ruta_boxes as $box): ?>
                        <?php
                        $noRuta = $box['no_ruta'];
                        $status = $box['status'];
                        $statusEntry = $box['status_entry'];
                        $isPatchIssue = $box['is_patch_issue'];
                        $userName = $box['user_name'];
                        $tglEntry = $box['tgl_entry'];
                        $canEdit = $box['can_edit'];

                        // Determine card styling
                        $cardClass = 'card-outline card-primary';
                        $btnClass = 'btn-outline-primary';
                        $icon = 'fa-edit';
                        $disabled = '';
                        $clickable = true;
                        $badgeHtml = '';

                        switch ($status) {
                            case 'LOCKED_LOGISTIC':
                                $cardClass = 'card-secondary';
                                $btnClass = 'btn-secondary';
                                $icon = 'fa-lock';
                                $disabled = 'disabled';
                                $clickable = false;
                                $badgeHtml = '<span class="badge badge-secondary">Belum Diterima</span>';
                                break;

                            case 'LOCKED_USER':
                                $cardClass = 'card-warning';
                                $btnClass = 'btn-warning';
                                $icon = 'fa-user-lock';
                                $disabled = 'disabled';
                                $clickable = false;
                                $badgeHtml = '<span class="badge badge-warning">Dikerjakan: ' . esc($userName) . '</span>';
                                break;

                            case 'DONE':
                                if ($statusEntry === 'Clean') {
                                    $cardClass = 'card-success';
                                    $btnClass = 'btn-success';
                                    $icon = 'fa-check-circle';
                                    $badgeHtml = '<span class="badge badge-success">Clean</span>';
                                } else {
                                    $cardClass = 'card-danger';
                                    $btnClass = 'btn-danger';
                                    $icon = 'fa-exclamation-triangle';
                                    $badgeHtml = '<span class="badge badge-danger">Error</span>';
                                }
                                if ($isPatchIssue) {
                                    $badgeHtml .= ' <span class="badge badge-dark">Patch Issue</span>';
                                }
                                break;

                            case 'OPEN':
                            default:
                                $cardClass = 'card-outline card-primary';
                                $btnClass = 'btn-outline-primary';
                                $icon = 'fa-edit';
                                $badgeHtml = '<span class="badge badge-light">Siap Dikerjakan</span>';
                                break;
                        }
                        ?>

                        <div class="col-md-2 col-sm-4 col-6 mb-3">
                            <div class="card <?= $cardClass ?> text-center">
                                <div class="card-body p-3">
                                    <h2 class="mb-2"><?= $noRuta ?></h2>
                                    <?= $badgeHtml ?>
                                    <?php if ($tglEntry): ?>
                                        <small class="d-block text-muted mt-1">
                                            <?= date('d/m/Y', strtotime($tglEntry)) ?>
                                        </small>
                                    <?php endif; ?>
                                    <hr class="my-2">
                                    <?php if ($clickable): ?>
                                        <button type="button" 
                                                class="btn btn-sm <?= $btnClass ?> btn-block btn-entry"
                                                data-nks="<?= esc($nks) ?>"
                                                data-no-ruta="<?= $noRuta ?>"
                                                data-status="<?= $status ?>"
                                                data-status-entry="<?= $statusEntry ?>"
                                                data-is-patch="<?= $isPatchIssue ?>"
                                                data-can-edit="<?= $canEdit ? '1' : '0' ?>">
                                            <i class="fas <?= $icon ?>"></i>
                                            <?= $canEdit ? 'Edit' : 'Entry' ?>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm <?= $btnClass ?> btn-block" disabled>
                                            <i class="fas <?= $icon ?>"></i> Terkunci
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Legend -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle"></i> Keterangan:</strong><br>
                            <span class="badge badge-secondary">Abu-abu</span> = Belum diterima dari logistik &nbsp;
                            <span class="badge badge-warning">Kuning</span> = Sedang dikerjakan petugas lain &nbsp;
                            <span class="badge badge-success">Hijau</span> = Selesai (Clean) &nbsp;
                            <span class="badge badge-danger">Merah</span> = Selesai (Error) &nbsp;
                            <span class="badge badge-light border">Putih</span> = Siap dikerjakan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Entry/Edit -->
<div class="modal fade" id="modalEntry" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-edit"></i> Entry Data Ruta <span id="modalRutaNo"></span>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="formEntry">
                <?= csrf_field() ?>
                <input type="hidden" name="nks" id="inputNks">
                <input type="hidden" name="no_ruta" id="inputNoRuta">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label>Status Entry <span class="text-danger">*</span></label>
                        <select name="status_entry" id="inputStatusEntry" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Clean">Clean (Tidak Ada Error)</option>
                            <option value="Error">Error (Ada Kesalahan)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="inputPatchIssue" name="is_patch_issue" value="1">
                            <label class="custom-control-label" for="inputPatchIssue">
                                <i class="fas fa-bug"></i> Masalah Aplikasi (Patch Issue)
                            </label>
                            <small class="form-text text-muted">
                                Centang jika data benar tapi aplikasi entry bermasalah
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger" id="btnDelete" style="display:none;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                    <button type="submit" class="btn btn-primary" id="btnSave">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    const modal = $('#modalEntry');
    const form = $('#formEntry');
    const btnSave = $('#btnSave');
    const btnDelete = $('#btnDelete');
    
    let currentNks = '';
    let currentNoRuta = 0;
    let canEdit = false;

    // Open modal when clicking entry button
    $('.btn-entry').on('click', function() {
        currentNks = $(this).data('nks');
        currentNoRuta = $(this).data('no-ruta');
        const status = $(this).data('status');
        const statusEntry = $(this).data('status-entry');
        const isPatch = $(this).data('is-patch');
        canEdit = $(this).data('can-edit') == '1';

        // Reset form
        form[0].reset();
        $('#inputNks').val(currentNks);
        $('#inputNoRuta').val(currentNoRuta);
        $('#modalRutaNo').text(currentNoRuta);

        // If editing existing entry
        if (canEdit && statusEntry) {
            $('#inputStatusEntry').val(statusEntry);
            $('#inputPatchIssue').prop('checked', isPatch == 1);
            btnDelete.show();
            modal.find('.modal-title').html('<i class="fas fa-edit"></i> Edit Data Ruta ' + currentNoRuta);
        } else {
            btnDelete.hide();
            modal.find('.modal-title').html('<i class="fas fa-edit"></i> Entry Data Ruta ' + currentNoRuta);
        }

        modal.modal('show');
    });

    // Submit form
    form.on('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        
        btnSave.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: '<?= base_url('kartu-kendali/store') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: response.message
                    });
                    btnSave.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Terjadi kesalahan pada server.'
                });
                btnSave.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            }
        });
    });

    // Delete entry
    btnDelete.on('click', function() {
        Swal.fire({
            title: 'Hapus Data?',
            text: 'Data entry ruta ' + currentNoRuta + ' akan dihapus!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '<?= base_url('kartu-kendali/delete') ?>',
                    type: 'POST',
                    data: {
                        nks: currentNks,
                        no_ruta: currentNoRuta,
                        <?= csrf_token() ?>: $('input[name="<?= csrf_token() ?>"]').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Terhapus!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: response.message
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Terjadi kesalahan pada server.'
                        });
                    }
                });
            }
        });
    });
});
</script>
