<?= $this->extend('layout/dashboard') ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Aktivitas PML: <strong><?= esc($pml['fullname']) ?></strong></h3>
        <div class="card-tools">
            <a href="<?= base_url('admin/pml') ?>" class="btn btn-default btn-sm">Kembali</a>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="callout callout-info">
                    <h5>Info PML</h5>
                    <p>
                        <strong>Wilayah Supervisi:</strong> <?= esc($pml['wilayah_supervisi']) ?><br>
                        <strong>No Telp:</strong> <?= esc($pml['phone_number']) ?>
                    </p>
                </div>
            </div>
            <div class="col-md-8">
                <h4>Timeline Aktivitas</h4>
                <?php if (empty($activities)): ?>
                    <p class="text-muted">Belum ada aktivitas tercatat.</p>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($activities as $activity): ?>
                            <div>
                                <i class="fas fa-check bg-green"></i>
                                <div class="timeline-item">
                                    <span class="time"><i class="fas fa-clock"></i> <?= date('d M Y H:i', strtotime($activity['created_at'])) ?></span>
                                    <h3 class="timeline-header no-border">
                                        <strong><?= esc($activity['activity_type']) ?></strong>
                                    </h3>
                                    <div class="timeline-body">
                                        <?= esc($activity['description']) ?>
                                        <?php if ($activity['location_lat']): ?>
                                            <div class="mt-2 text-sm text-muted">
                                                <i class="fas fa-map-marker-alt"></i> Lokasi: <?= $activity['location_lat'] ?>, <?= $activity['location_long'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <div>
                            <i class="fas fa-clock bg-gray"></i>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
