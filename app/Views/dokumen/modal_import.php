<!-- Modal Import Excel -->
<div class="modal fade" id="modal-import" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title"><i class="fas fa-file-excel mr-1"></i>Impor Dokumen dari Excel</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Step 1: Configuration -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kegiatan Survei <span class="text-danger">*</span></label>
                            <select id="import_id_kegiatan" class="form-control" required>
                                <option value="">-- Pilih Kegiatan --</option>
                                <?php foreach ($kegiatan as $k): ?>
                                    <option value="<?= $k['id_kegiatan'] ?>"><?= esc($k['nama_kegiatan']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Setor <span class="text-danger">*</span></label>
                            <input type="date" id="import_tanggal_setor" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Step 2: File Upload -->
                <div class="form-group">
                    <label>File Excel (.xlsx) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="import_excel_file" accept=".xlsx">
                            <label class="custom-file-label" for="import_excel_file">Pilih file...</label>
                        </div>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-info" id="btn-upload-preview">
                                <i class="fas fa-search mr-1"></i>Preview
                            </button>
                        </div>
                    </div>
                    <small class="text-muted">Maks. 2MB. Format: .xlsx — <a href="#" id="btn-download-template" class="text-success"><i class="fas fa-download mr-1"></i>Download Template</a></small>
                </div>

                <!-- Status -->
                <div id="import-status" class="alert alert-light py-2 mb-2" style="display:none;"></div>

                <!-- Preview Table -->
                <div id="import-preview-area" style="display:none;">
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-bordered table-sm table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Kode Wilayah</th>
                                    <th width="200">Status</th>
                                </tr>
                            </thead>
                            <tbody id="import-preview-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <!-- Hidden form for confirmed submission -->
                <form id="form-import-confirm" method="post" action="<?= base_url('dokumen/import-store') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="import_id_kegiatan" id="import_id_kegiatan_hidden">
                    <input type="hidden" name="import_tanggal_setor" id="import_tanggal_setor_hidden">
                    <input type="hidden" name="kode_wilayah_list" id="import_kode_wilayah_list">
                </form>
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="btn-confirm-import" style="display:none;">
                    <i class="fas fa-check mr-1"></i>Konfirmasi Impor
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Sync hidden fields and show filename
$(function () {
    $('#import_excel_file').on('change', function () {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
    });
    $('#import_id_kegiatan').on('change', function () {
        $('#import_id_kegiatan_hidden').val($(this).val());
    });
    $('#import_tanggal_setor').on('change', function () {
        $('#import_tanggal_setor_hidden').val($(this).val());
    }).trigger('change');
});
</script>
