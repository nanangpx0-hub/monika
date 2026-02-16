<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0"><i class="fas fa-chart-line mr-2"></i>Dashboard Laporan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card card-outline card-primary mb-3">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter mr-1"></i>Filter Data</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <form id="filterForm" method="get" action="<?= base_url('laporan') ?>">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label><i class="fas fa-calendar-alt mr-1"></i>Kegiatan</label>
                        <select name="kegiatan" id="filterKegiatan" class="form-control">
                            <option value="">-- Semua Kegiatan --</option>
                            <?php foreach ($listKegiatan as $k): ?>
                                <option value="<?= $k['id_kegiatan'] ?>" <?= ($filters['kegiatan'] == $k['id_kegiatan']) ? 'selected' : '' ?>>
                                    <?= esc($k['nama_kegiatan']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-calendar mr-1"></i>Dari Tanggal</label>
                        <input type="date" name="date_from" id="dateFrom" class="form-control" 
                               value="<?= esc($filters['date_from'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label><i class="fas fa-calendar mr-1"></i>Sampai Tanggal</label>
                        <input type="date" name="date_to" id="dateTo" class="form-control" 
                               value="<?= esc($filters['date_to'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search mr-1"></i>Filter
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Info Boxes -->
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= number_format($stats['total'] ?? 0) ?></h3>
                <p>Total Dokumen</p>
            </div>
            <div class="icon"><i class="fas fa-file-alt"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= number_format($stats['sudah_entry'] ?? 0) ?></h3>
                <p>Sudah Entry</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= number_format($stats['error'] ?? 0) ?></h3>
                <p>Error</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= number_format($stats['valid'] ?? 0) ?></h3>
                <p>Valid</p>
            </div>
            <div class="icon"><i class="fas fa-clipboard-check"></i></div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Bar Chart: Target vs Realisasi -->
    <div class="col-lg-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i>Target vs Realisasi per Kecamatan</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 350px;">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Pie Chart: Status Dokumen -->
    <div class="col-lg-4">
        <div class="card card-outline card-success">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i>Status Dokumen</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 350px;">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-table mr-1"></i>Daftar Dokumen Survei</h3>
        <div class="card-tools">
            <?php
                $exportQuery = http_build_query(array_filter($filters));
            ?>
            <a href="<?= base_url('laporan/export-excel?' . $exportQuery) ?>" class="btn btn-sm btn-success mr-1">
                <i class="fas fa-file-excel mr-1"></i>Export Excel
            </a>
            <a href="<?= base_url('laporan/export-pdf?' . $exportQuery) ?>" class="btn btn-sm btn-danger mr-1">
                <i class="fas fa-file-pdf mr-1"></i>Export PDF
            </a>
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
        </div>
    </div>
    <div class="card-body">
        <table id="tableDokumen" class="table table-bordered table-striped table-hover" style="width:100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Wilayah</th>
                    <th>Nama PCL</th>
                    <th>Sobat ID</th>
                    <th>Kegiatan</th>
                    <th>Status</th>
                    <th>Tgl Setor</th>
                    <th>Error</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dokumenList as $i => $doc): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($doc['kode_wilayah']) ?></td>
                    <td><?= esc($doc['nama_pcl'] ?? '-') ?></td>
                    <td><code><?= esc($doc['sobat_id'] ?? '-') ?></code></td>
                    <td><?= esc($doc['nama_kegiatan'] ?? '-') ?></td>
                    <td>
                        <?php
                            $badgeClass = match($doc['status']) {
                                'Valid'       => 'badge-success',
                                'Sudah Entry' => 'badge-primary',
                                'Error'       => 'badge-danger',
                                default       => 'badge-secondary',
                            };
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= esc($doc['status']) ?></span>
                    </td>
                    <td><?= esc($doc['tanggal_setor'] ?? '-') ?></td>
                    <td>
                        <?php if ($doc['pernah_error']): ?>
                            <span class="badge badge-warning">Ya</span>
                        <?php else: ?>
                            <span class="badge badge-light">Tidak</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Links to Sub-Reports -->
<div class="row mb-3">
    <div class="col-md-6">
        <a href="<?= base_url('laporan/pcl') ?>" class="btn btn-outline-primary btn-block">
            <i class="fas fa-user-check mr-1"></i>Laporan Kinerja PCL
        </a>
    </div>
    <div class="col-md-6">
        <a href="<?= base_url('laporan/pengolahan') ?>" class="btn btn-outline-info btn-block">
            <i class="fas fa-cogs mr-1"></i>Laporan Kinerja Pengolahan
        </a>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<!-- Chart.js -->
<script src="<?= base_url('assets/adminlte/plugins/chart.js/Chart.min.js') ?>"></script>

<script>
$(function () {
    // ── DataTable ──
    $('#tableDokumen').DataTable({
        responsive: true,
        autoWidth: false,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
            paginate: { first: "Pertama", last: "Terakhir", next: "»", previous: "«" }
        }
    });

    // ── Bar Chart: Target vs Realisasi ──
    var barCtx = document.getElementById('barChart').getContext('2d');
    var barData = <?= json_encode($targetRealisasi) ?>;

    var barLabels = barData.map(function(r) { return 'Kec. ' + r.kode_kec; });
    var targetValues = barData.map(function(r) { return parseInt(r.target_ruta) || 0; });
    var realisasiValues = barData.map(function(r) { return parseInt(r.realisasi) || 0; });

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: barLabels,
            datasets: [
                {
                    label: 'Target (Ruta)',
                    backgroundColor: 'rgba(0, 153, 216, 0.7)',
                    borderColor: '#0099d8',
                    borderWidth: 1,
                    data: targetValues
                },
                {
                    label: 'Realisasi',
                    backgroundColor: 'rgba(140, 198, 63, 0.7)',
                    borderColor: '#8cc63f',
                    borderWidth: 1,
                    data: realisasiValues
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: { beginAtZero: true }
                }]
            },
            legend: { position: 'top' },
            tooltips: {
                callbacks: {
                    label: function(item, data) {
                        return data.datasets[item.datasetIndex].label + ': ' + item.yLabel.toLocaleString();
                    }
                }
            }
        }
    });

    // ── Pie Chart: Status Distribution ──
    var pieCtx = document.getElementById('pieChart').getContext('2d');
    var pieData = <?= json_encode($statusSummary) ?>;

    var statusColors = {
        'Uploaded': '#6c757d',
        'Sudah Entry': '#007bff',
        'Error': '#dc3545',
        'Valid': '#28a745'
    };

    var pieLabels = pieData.map(function(r) { return r.status; });
    var pieValues = pieData.map(function(r) { return parseInt(r.total); });
    var pieBgColors = pieLabels.map(function(s) { return statusColors[s] || '#ffc107'; });

    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: pieLabels,
            datasets: [{
                data: pieValues,
                backgroundColor: pieBgColors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: { padding: 15 }
            },
            tooltips: {
                callbacks: {
                    label: function(item, data) {
                        var value = data.datasets[0].data[item.index];
                        var total = data.datasets[0].data.reduce(function(a, b) { return a + b; }, 0);
                        var pct = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                        return data.labels[item.index] + ': ' + value + ' (' + pct + '%)';
                    }
                }
            }
        }
    });
});
</script>
<?= $this->endSection(); ?>
