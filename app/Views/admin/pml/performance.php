<?= $this->extend('layout/dashboard') ?>

<?= $this->section('header_actions') ?>
<div class="float-sm-right">
    <a href="<?= base_url('admin/pml/export-performance/' . $pml['id_user']) ?>" class="btn btn-success btn-sm mr-1">
        <i class="fas fa-file-export"></i> Ekspor Laporan
    </a>
    <a href="<?= base_url('admin/pml') ?>" class="btn btn-default btn-sm">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Summary Cards -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $summary['total'] ?></h3>
                <p>Total Aktivitas</p>
            </div>
            <div class="icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $summary['this_month'] ?></h3>
                <p>Aktivitas Bulan Ini</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $totalPcl ?></h3>
                <p>PCL Dibina</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box <?= $summary['growth'] >= 0 ? 'bg-success' : 'bg-danger' ?>">
            <div class="inner">
                <h3><?= $summary['growth'] ?>%</h3>
                <p>Pertumbuhan (vs Bulan Lalu)</p>
            </div>
            <div class="icon">
                <i class="fas fa-<?= $summary['growth'] >= 0 ? 'arrow-up' : 'arrow-down' ?>"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Monthly Activity Chart -->
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar"></i> Aktivitas Bulanan (6 Bulan Terakhir)</h3>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
        </div>
    </div>

    <!-- Activity by Type -->
    <div class="col-md-4">
        <div class="card card-outline card-info">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie"></i> Aktivitas per Jenis</h3>
            </div>
            <div class="card-body">
                <?php if (empty($summary['by_type'])): ?>
                    <p class="text-center text-muted">Belum ada data aktivitas</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($summary['by_type'] as $type): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= esc($type['activity_type']) ?>
                                <span class="badge badge-primary badge-pill"><?= $type['count'] ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- PCL Performance -->
    <div class="col-md-6">
        <div class="card card-outline card-warning">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user-friends"></i> Kinerja PCL yang Dibina</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nama PCL</th>
                            <th>Wilayah</th>
                            <th>Dokumen</th>
                            <th>Error</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pclStats)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">Tidak ada PCL yang dibina</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pclStats as $stat): ?>
                                <tr>
                                    <td><?= esc($stat['pcl']['fullname']) ?></td>
                                    <td><small><?= esc($stat['pcl']['wilayah_kerja']) ?></small></td>
                                    <td><?= $stat['total_docs'] ?></td>
                                    <td>
                                        <span class="badge badge-<?= $stat['error_count'] > 0 ? 'danger' : 'success' ?>">
                                            <?= $stat['error_count'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-<?= $stat['error_rate'] > 10 ? 'danger' : ($stat['error_rate'] > 5 ? 'warning' : 'success') ?>">
                                            <?= $stat['error_rate'] ?>%
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-md-6">
        <div class="card card-outline card-secondary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history"></i> Aktivitas Terakhir</h3>
            </div>
            <div class="card-body p-0" style="max-height: 350px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    <?php if (empty($recentActivities)): ?>
                        <li class="list-group-item text-center text-muted">Belum ada aktivitas</li>
                    <?php else: ?>
                        <?php foreach ($recentActivities as $activity): ?>
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <strong><?= esc($activity['activity_type']) ?></strong>
                                    <small class="text-muted">
                                        <?= date('d M Y H:i', strtotime($activity['created_at'])) ?>
                                    </small>
                                </div>
                                <small><?= esc($activity['description']) ?></small>
                                <?php if ($activity['location_lat'] && $activity['location_long']): ?>
                                    <br><small class="text-info">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        <?= $activity['location_lat'] ?>, <?= $activity['location_long'] ?>
                                    </small>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Log New Activity Form -->
<div class="card card-outline card-success">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-plus-circle"></i> Catat Aktivitas Baru</h3>
    </div>
    <div class="card-body">
        <form action="<?= base_url('admin/pml/log-activity') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="pml_id" value="<?= $pml['id_user'] ?>">
            
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Jenis Aktivitas</label>
                        <select name="activity_type" class="form-control" required>
                            <option value="">-- Pilih --</option>
                            <option value="Supervisi Lapangan">Supervisi Lapangan</option>
                            <option value="Verifikasi Data">Verifikasi Data</option>
                            <option value="Pembinaan PCL">Pembinaan PCL</option>
                            <option value="Spot Check">Spot Check</option>
                            <option value="Quality Control">Quality Control</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Deskripsi</label>
                        <input type="text" name="description" class="form-control" placeholder="Deskripsi aktivitas..." required>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Koordinat GPS (Opsional)</label>
                        <div class="input-group">
                            <input type="text" name="location_lat" class="form-control form-control-sm" placeholder="Lat" id="lat">
                            <input type="text" name="location_long" class="form-control form-control-sm" placeholder="Long" id="long">
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-outline-info btn-block" onclick="getLocation()">
                            <i class="fas fa-crosshairs"></i> GPS
                        </button>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Simpan Aktivitas
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly Chart
const ctx = document.getElementById('monthlyChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($monthlyStats, 'month')) ?>,
        datasets: [{
            label: 'Jumlah Aktivitas',
            data: <?= json_encode(array_column($monthlyStats, 'count')) ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});

// Get GPS Location
function getLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            document.getElementById('lat').value = position.coords.latitude.toFixed(6);
            document.getElementById('long').value = position.coords.longitude.toFixed(6);
        }, function(error) {
            alert('Gagal mendapatkan lokasi: ' + error.message);
        });
    } else {
        alert('Browser tidak mendukung Geolocation');
    }
}
</script>
<?= $this->endSection() ?>
