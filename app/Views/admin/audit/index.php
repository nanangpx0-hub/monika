<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>
<div class="card card-outline card-info">
    <div class="card-header">
        <h3 class="card-title">Log Aktivitas</h3>
    </div>
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>User ID</th>
                    <th>Aksi</th>
                    <th>Detail</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($logs)): ?>
                    <tr>
                        <td colspan="5" class="text-center">Belum ada aktivitas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($logs as $log): ?>
                        <tr>
                            <td>
                                <?= $log['created_at'] ?>
                            </td>
                            <td>
                                <?= $log['user_id'] ? "ID: " . $log['user_id'] : 'System/Guest' ?>
                            </td>
                            <td><span class="badge badge-info">
                                    <?= $log['action'] ?>
                                </span></td>
                            <td>
                                <?= $log['details'] ?>
                            </td>
                            <td>
                                <?= $log['ip_address'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer clearfix">
        <div class="float-right">
            <?= $pager->links() ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>