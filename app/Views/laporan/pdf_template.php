<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Dokumen Survei</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { font-size: 18px; color: #0099d8; margin-bottom: 4px; }
        .header h2 { font-size: 13px; font-weight: normal; color: #666; }
        .header .date { font-size: 10px; color: #999; margin-top: 4px; }
        
        .stats-row { 
            display: table; 
            width: 100%; 
            margin-bottom: 15px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            text-align: center;
            padding: 8px;
            border: 1px solid #ddd;
        }
        .stat-box .number { font-size: 20px; font-weight: bold; }
        .stat-box .label { font-size: 9px; color: #666; text-transform: uppercase; }
        .stat-total .number { color: #17a2b8; }
        .stat-entry .number { color: #28a745; }
        .stat-error .number { color: #dc3545; }
        .stat-valid .number { color: #ffc107; }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #0099d8;
            color: white;
            padding: 6px 8px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }
        table.data-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }
        table.data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .status-uploaded { background: #6c757d; }
        .status-entry { background: #007bff; }
        .status-error { background: #dc3545; }
        .status-valid { background: #28a745; }

        .filter-info {
            font-size: 10px;
            color: #888;
            text-align: center;
            margin-bottom: 10px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #aaa;
            padding: 10px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DOKUMEN SURVEI</h1>
        <h2>MONIKA — Monitoring Kinerja & Anomali</h2>
        <div class="date">Dicetak: <?= $tanggal ?></div>
    </div>

    <div class="filter-info">
        Kegiatan: <?= $filters['kegiatan'] ? 'ID ' . $filters['kegiatan'] : 'Semua' ?> &bull;
        Periode: <?= $filters['date_from'] ?: 'Awal' ?> s/d <?= $filters['date_to'] ?: 'Akhir' ?>
    </div>

    <div class="stats-row">
        <div class="stat-box stat-total">
            <div class="number"><?= number_format($stats['total'] ?? 0) ?></div>
            <div class="label">Total Dokumen</div>
        </div>
        <div class="stat-box stat-entry">
            <div class="number"><?= number_format($stats['sudah_entry'] ?? 0) ?></div>
            <div class="label">Sudah Entry</div>
        </div>
        <div class="stat-box stat-error">
            <div class="number"><?= number_format($stats['error'] ?? 0) ?></div>
            <div class="label">Error</div>
        </div>
        <div class="stat-box stat-valid">
            <div class="number"><?= number_format($stats['valid'] ?? 0) ?></div>
            <div class="label">Valid</div>
        </div>
    </div>

    <table class="data-table">
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
            <?php if (empty($dokumen)): ?>
            <tr>
                <td colspan="8" style="text-align:center; padding: 20px; color: #999;">Tidak ada data dokumen</td>
            </tr>
            <?php else: ?>
                <?php foreach ($dokumen as $i => $doc): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= esc($doc['kode_wilayah']) ?></td>
                    <td><?= esc($doc['nama_pcl'] ?? '-') ?></td>
                    <td><?= esc($doc['sobat_id'] ?? '-') ?></td>
                    <td><?= esc($doc['nama_kegiatan'] ?? '-') ?></td>
                    <td>
                        <?php
                            $statusClass = match($doc['status']) {
                                'Sudah Entry' => 'status-entry',
                                'Error'       => 'status-error',
                                'Valid'        => 'status-valid',
                                default       => 'status-uploaded',
                            };
                        ?>
                        <span class="status-badge <?= $statusClass ?>"><?= esc($doc['status']) ?></span>
                    </td>
                    <td><?= esc($doc['tanggal_setor'] ?? '-') ?></td>
                    <td><?= $doc['pernah_error'] ? 'Ya' : 'Tidak' ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        MONIKA &copy; <?= date('Y') ?> BPS Kab. Jember — Dokumen ini digenerate secara otomatis
    </div>
</body>
</html>
