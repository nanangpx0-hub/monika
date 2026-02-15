<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-id-card"></i> Kartu Kendali Digital
                </h3>
            </div>
            <div class="card-body">
                <p class="mb-0">
                    Daftar NKS yang dokumennya sudah diterima dari logistik beserta progres entry ruta.
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table"></i> Daftar NKS</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="table-kartu-kendali">
                        <thead>
                            <tr>
                                <th style="width: 120px;">NKS</th>
                                <th>Kecamatan</th>
                                <th>Desa</th>
                                <th class="text-center" style="width: 140px;">Ruta Diterima</th>
                                <th style="min-width: 220px;">Progress Entry</th>
                                <th class="text-center" style="width: 130px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (! empty($nks_list)): ?>
                                <?php foreach ($nks_list as $row): ?>
                                    <?php
                                    $nks = $row['nks'] ?? '';
                                    $kecamatan = $row['kecamatan'] ?? '-';
                                    $desa = $row['desa'] ?? '-';
                                    $jmlRutaTerima = (int) ($row['jml_ruta_terima'] ?? 0);
                                    $jmlSelesaiRaw = (int) ($row['jml_selesai'] ?? 0);
                                    $jmlSelesai = min($jmlSelesaiRaw, $jmlRutaTerima);
                                    $progress = $jmlRutaTerima > 0 ? (int) round(($jmlSelesai / $jmlRutaTerima) * 100) : 0;

                                    $progressClass = 'bg-secondary';
                                    if ($jmlRutaTerima > 0) {
                                        if ($progress >= 100) {
                                            $progressClass = 'bg-success';
                                        } elseif ($progress >= 50) {
                                            $progressClass = 'bg-warning';
                                        } else {
                                            $progressClass = 'bg-danger';
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td><strong><?= esc($nks) ?></strong></td>
                                        <td><?= esc($kecamatan) ?></td>
                                        <td><?= esc($desa) ?></td>
                                        <td class="text-center">
                                            <span class="badge badge-info"><?= $jmlRutaTerima ?></span>
                                            <span class="text-muted">/ 10</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 22px;">
                                                <div
                                                    class="progress-bar <?= $progressClass ?>"
                                                    role="progressbar"
                                                    style="width: <?= $progress ?>%;"
                                                    aria-valuenow="<?= $progress ?>"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100"
                                                >
                                                    <strong><?= $jmlSelesai ?> / <?= $jmlRutaTerima ?> (<?= $progress ?>%)</strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <a href="<?= base_url('kartu-kendali/detail/' . $nks) ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Buka
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <strong><i class="fas fa-info-circle"></i> Keterangan Progress:</strong>
            <span class="badge badge-danger ml-2">0-49%</span> Rendah
            <span class="badge badge-warning ml-2">50-99%</span> Proses
            <span class="badge badge-success ml-2">100%</span> Selesai
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
$(function () {
    $('#table-kartu-kendali').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        order: [[0, 'asc']],
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
