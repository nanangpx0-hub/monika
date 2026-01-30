<div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Tambah Kegiatan Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/kegiatan/store" method="post">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label>Nama Kegiatan</label>
                        <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control" placeholder="Contoh: Sakernas Februari 2026" required>
                    </div>
                    <div class="form-group">
                        <label>Kode Kegiatan (Otomatis)</label>
                        <input type="text" id="kode_kegiatan_preview" class="form-control bg-light" placeholder="Akan digenerate otomatis..." readonly>
                        <small class="form-text text-muted">Kode akan digenerate otomatis berdasarkan nama kegiatan saat disimpan.</small>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var namaInput = document.getElementById('nama_kegiatan');
    var kodePreview = document.getElementById('kode_kegiatan_preview');
    
    namaInput.addEventListener('input', function() {
        var nama = this.value;
        if (nama.length >= 3) {
            // Ambil 3 huruf pertama (hanya huruf)
            var prefix = nama.replace(/[^a-zA-Z]/g, '').substring(0, 3).toUpperCase();
            if (prefix.length < 3) {
                prefix = (prefix + 'XXX').substring(0, 3);
            }
            // Tanggal sekarang (YYMM)
            var now = new Date();
            var yy = String(now.getFullYear()).slice(-2);
            var mm = String(now.getMonth() + 1).padStart(2, '0');
            // Preview (dengan xxx sebagai placeholder untuk random)
            kodePreview.value = prefix + '_' + yy + mm + '_XXX';
        } else {
            kodePreview.value = '';
        }
    });
});
</script>
