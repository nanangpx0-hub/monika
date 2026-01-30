<?= $this->extend('layout/dashboard') ?>

<?= $this->section('header_actions') ?>
<div class="float-sm-right">
    <a href="<?= base_url('admin/audit/export') . '?' . http_build_query($filters) ?>" class="btn btn-success btn-sm">
        <i class="fas fa-file-export"></i> Ekspor CSV
    </a>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Statistics Cards -->
<div class="row">
    <div class="col-lg-4 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $stats['today'] ?></h3>
                <p>Aktivitas Hari Ini</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-day"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $stats['week'] ?></h3>
                <p>Aktivitas 7 Hari Terakhir</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-week"></i></div>
        </div>
    </div>
    <div class="col-lg-4 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3><?= number_format($stats['total']) ?></h3>
                <p>Total Aktivitas</p>
            </div>
            <div class="icon"><i class="fas fa-history"></i></div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card card-outline card-primary collapsed-card">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-filter"></i> Filter & Pencarian</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form action="" method="get">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>User</label>
                        <select name="user_id" class="form-control form-control-sm">
                            <option value="">-- Semua User --</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id_user'] ?>" <?= $filters['user_id'] == $user['id_user'] ? 'selected' : '' ?>>
                                    <?= esc($user['fullname']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Aksi</label>
                        <select name="action" class="form-control form-control-sm">
                            <option value="">-- Semua Aksi --</option>
                            <?php foreach ($actions as $act): ?>
                                <option value="<?= esc($act) ?>" <?= $filters['action'] == $act ? 'selected' : '' ?>>
                                    <?= esc($act) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Dari Tanggal</label>
                        <input type="date" name="date_from" class="form-control form-control-sm" 
                               value="<?= $filters['date_from'] ?>">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="date_to" class="form-control form-control-sm" 
                               value="<?= $filters['date_to'] ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cari</label>
                        <input type="text" name="search" class="form-control form-control-sm" 
                               placeholder="Cari aksi, detail, IP..." value="<?= esc($filters['search']) ?>">
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-sm btn-block">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Audit Log Table -->
<div class="card card-outline card-secondary">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-list"></i> Audit Trail Log</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover table-striped">
            <thead class="thead-light">
                <tr>
                    <th style="width: 50px">#</th>
                    <th style="width: 160px">Waktu</th>
                    <th>User</th>
                    <th>Aksi</th>
                    <th>Detail</th>
                    <th style="width: 120px">IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-3x mb-2"></i><br>
                            Tidak ada data log
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $userMap = [];
                    foreach ($users as $u) {
                        $userMap[$u['id_user']] = $u['fullname'];
                    }
                    ?>
                    <?php foreach ($logs as $index => $log): ?>
                        <tr>
                            <td><?= $pager->getCurrentPage() * $pager->getPerPage() - $pager->getPerPage() + $index + 1 ?></td>
                            <td>
                                <small>
                                    <?= date('d M Y', strtotime($log['created_at'])) ?><br>
                                    <span class="text-muted"><?= date('H:i:s', strtotime($log['created_at'])) ?></span>
                                </small>
                            </td>
                            <td>
                                <?php if ($log['user_id'] && isset($userMap[$log['user_id']])): ?>
                                    <span class="badge badge-info"><?= esc($userMap[$log['user_id']]) ?></span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">System</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                    $actionClass = 'badge-secondary';
                                    if (strpos($log['action'], 'Created') !== false || strpos($log['action'], 'Import') !== false) {
                                        $actionClass = 'badge-success';
                                    } elseif (strpos($log['action'], 'Updated') !== false || strpos($log['action'], 'Changed') !== false) {
                                        $actionClass = 'badge-warning';
                                    } elseif (strpos($log['action'], 'Deleted') !== false) {
                                        $actionClass = 'badge-danger';
                                    } elseif (strpos($log['action'], 'Login') !== false) {
                                        $actionClass = 'badge-primary';
                                    } elseif (strpos($log['action'], 'Reset') !== false) {
                                        $actionClass = 'badge-info';
                                    }
                                ?>
                                <span class="badge <?= $actionClass ?>"><?= esc($log['action']) ?></span>
                            </td>
                            <td>
                                <small><?= esc(strlen($log['details']) > 80 ? substr($log['details'], 0, 80) . '...' : $log['details']) ?></small>
                            </td>
                            <td><code><?= esc($log['ip_address']) ?></code></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <?= $pager->links() ?>
    </div>
</div>
<?= $this->endSection() ?>