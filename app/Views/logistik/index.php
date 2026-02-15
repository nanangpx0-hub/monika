<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-boxes"></i> Inventaris Logistik</h3>
            </div>
            <div class="card-body">
                <p class="mb-0">Daftar barang logistik dan kondisi stok operasional MONIKA.</p>
            </div>
        </div>

        <?php if (! $table_ready): ?>
            <div class="alert alert-warning">
                <strong>Tabel logistik belum tersedia.</strong><br>
                Jalankan migrasi terlebih dahulu: <code>php spark migrate</code>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table"></i> Daftar Barang</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table-logistik">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Satuan</th>
                                <th class="text-center">Stok</th>
                                <th>Kondisi</th>
                                <th>Lokasi</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($barang_list)): ?>
                                <?php $no = 1; ?>
                                <?php foreach ($barang_list as $barang): ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><strong><?= esc($barang['kode_barang']); ?></strong></td>
                                        <td><?= esc($barang['nama_barang']); ?></td>
                                        <td><?= esc($barang['kategori'] ?? '-'); ?></td>
                                        <td><?= esc($barang['satuan'] ?? '-'); ?></td>
                                        <td class="text-center"><?= esc((string) ($barang['stok'] ?? 0)); ?></td>
                                        <td>
                                            <?php
                                                $kondisi = (string) ($barang['kondisi'] ?? 'Baik');
                                                $badgeClass = 'badge-success';
                                                if ($kondisi === 'Rusak Ringan') {
                                                    $badgeClass = 'badge-warning';
                                                } elseif ($kondisi === 'Rusak Berat') {
                                                    $badgeClass = 'badge-danger';
                                                }
                                            ?>
                                            <span class="badge <?= $badgeClass; ?>"><?= esc($kondisi); ?></span>
                                        </td>
                                        <td><?= esc($barang['lokasi'] ?? '-'); ?></td>
                                        <td><?= esc($barang['keterangan'] ?? '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    $('#table-logistik').DataTable({
        pageLength: 10,
        order: [[1, 'asc']],
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
            infoEmpty: 'Tidak ada data',
            zeroRecords: 'Data tidak ditemukan',
            paginate: {
                first: 'Awal',
                last: 'Akhir',
                next: 'Selanjutnya',
                previous: 'Sebelumnya'
            }
        }
    });
});
</script>
<?= $this->endSection(); ?>
