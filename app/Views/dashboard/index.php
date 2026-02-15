<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-bps-blue elevation-1"><i class="fas fa-map-marked-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total NKS</span>
                <span class="info-box-number"><?= $total_nks ?></span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-bps-orange elevation-1"><i class="fas fa-inbox"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Dokumen Masuk</span>
                <span class="info-box-number"><?= $dok_masuk ?></span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-bps-green elevation-1"><i class="fas fa-chart-line"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Progres</span>
                <span class="info-box-number"><?= $persen_entry ?>%</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-calendar-alt"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Deadline</span>
                <span class="info-box-number"><?= $sisa_hari ?> Hari</span>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
