<div class="modal fade" id="modal-error">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">Lapor Error / Anomali</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/dokumen/report-error" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id_dokumen" id="error_id_dokumen">
                    
                    <div class="form-group">
                        <label>Jenis Error</label>
                        <select name="jenis_error" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="Isian Kosong">Isian Kosong</option>
                            <option value="Inkomsistensi">Inkomsistensi Antar Blok</option>
                            <option value="Tulisan Tidak Terbaca">Tulisan Tidak Terbaca</option>
                            <option value="Salah Kode">Salah Kode Wilayah/Klasifikasi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Keterangan Detail</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Jelaskan letak kesalahan..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Simpan Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>
