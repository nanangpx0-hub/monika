<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="row mb-3">
    <div class="col-12">
        <h1 class="m-0">Presensi Harian</h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Kamera Presensi</h3>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <video id="cameraPreview" class="w-100 rounded" autoplay playsinline style="max-height: 360px; background: #111;"></video>
                    <canvas id="snapshotCanvas" class="d-none"></canvas>
                </div>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    <button type="button" id="btnAmbilFoto" class="btn btn-info">
                        <i class="fas fa-camera"></i> Ambil Foto
                    </button>
                    <button type="button" id="btnUlangFoto" class="btn btn-secondary d-none">
                        <i class="fas fa-sync"></i> Ambil Ulang
                    </button>
                </div>

                <div class="border rounded p-2 bg-light">
                    <img id="fotoPreview" src="" alt="Preview Foto" class="img-fluid d-none">
                    <small id="cameraStatus" class="text-muted d-block mt-2">Menyiapkan kamera...</small>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card card-success card-outline">
            <div class="card-header">
                <h3 class="card-title">Status Hari Ini</h3>
            </div>
            <div class="card-body">
                <p class="mb-2">
                    <strong>Absen Masuk:</strong>
                    <?php if ($has_masuk): ?>
                        <span class="badge badge-success"><?= esc($presensi_hari_ini['jam_masuk'] ?? '-'); ?></span>
                    <?php else: ?>
                        <span class="badge badge-secondary">Belum</span>
                    <?php endif; ?>
                </p>
                <p class="mb-3">
                    <strong>Absen Pulang:</strong>
                    <?php if ($has_pulang): ?>
                        <span class="badge badge-success"><?= esc($presensi_hari_ini['jam_pulang'] ?? '-'); ?></span>
                    <?php else: ?>
                        <span class="badge badge-secondary">Belum</span>
                    <?php endif; ?>
                </p>

                <form id="presensiForm">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="image" id="imageData">
                    <input type="hidden" name="lat" id="latValue">
                    <input type="hidden" name="long" id="longValue">

                    <div class="form-group">
                        <label>Latitude</label>
                        <input type="text" id="latDisplay" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label>Longitude</label>
                        <input type="text" id="longDisplay" class="form-control" readonly>
                    </div>

                    <div class="alert alert-info">
                        Radius presensi kantor maksimal <strong><?= esc((string) $max_distance); ?> meter</strong>.
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button
                            type="button"
                            id="btnMasuk"
                            class="btn btn-primary"
                            <?= $has_masuk ? 'disabled' : ''; ?>
                        >
                            <i class="fas fa-sign-in-alt"></i> Presensi Masuk
                        </button>
                        <button
                            type="button"
                            id="btnPulang"
                            class="btn btn-warning"
                            <?= (! $has_masuk || $has_pulang) ? 'disabled' : ''; ?>
                        >
                            <i class="fas fa-sign-out-alt"></i> Presensi Pulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(() => {
    const submitUrl = "<?= base_url('presensi/submit'); ?>";

    const video = document.getElementById('cameraPreview');
    const canvas = document.getElementById('snapshotCanvas');
    const fotoPreview = document.getElementById('fotoPreview');
    const imageData = document.getElementById('imageData');
    const btnAmbilFoto = document.getElementById('btnAmbilFoto');
    const btnUlangFoto = document.getElementById('btnUlangFoto');
    const btnMasuk = document.getElementById('btnMasuk');
    const btnPulang = document.getElementById('btnPulang');
    const cameraStatus = document.getElementById('cameraStatus');
    const form = document.getElementById('presensiForm');
    const csrfInput = form.querySelector('input[name="<?= csrf_token() ?>"]');

    const latValue = document.getElementById('latValue');
    const longValue = document.getElementById('longValue');
    const latDisplay = document.getElementById('latDisplay');
    const longDisplay = document.getElementById('longDisplay');

    let streamRef = null;
    const defaultMasukDisabled = btnMasuk.disabled;
    const defaultPulangDisabled = btnPulang.disabled;

    function setButtonLoading(isLoading) {
        if (isLoading) {
            btnMasuk.disabled = true;
            btnPulang.disabled = true;
            return;
        }

        btnMasuk.disabled = defaultMasukDisabled;
        btnPulang.disabled = defaultPulangDisabled;
    }

    async function initCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            cameraStatus.textContent = 'Browser tidak mendukung akses kamera.';
            return;
        }

        try {
            streamRef = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user' },
                audio: false
            });
            video.srcObject = streamRef;
            cameraStatus.textContent = 'Kamera aktif. Ambil foto sebelum presensi.';
        } catch (error) {
            cameraStatus.textContent = 'Gagal mengakses kamera. Pastikan izin kamera sudah diberikan.';
        }
    }

    function initLocation() {
        if (!navigator.geolocation) {
            Swal.fire('Gagal', 'Browser tidak mendukung geolokasi.', 'error');
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (position) => {
                const lat = position.coords.latitude.toFixed(7);
                const lon = position.coords.longitude.toFixed(7);
                latValue.value = lat;
                longValue.value = lon;
                latDisplay.value = lat;
                longDisplay.value = lon;
            },
            () => {
                Swal.fire('Lokasi Tidak Tersedia', 'Aktifkan GPS/lokasi agar dapat melakukan presensi.', 'warning');
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    }

    function capturePhoto() {
        if (!video.videoWidth || !video.videoHeight) {
            Swal.fire('Gagal', 'Kamera belum siap.', 'error');
            return;
        }

        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const context = canvas.getContext('2d');
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        imageData.value = dataUrl;
        fotoPreview.src = dataUrl;
        fotoPreview.classList.remove('d-none');
        btnUlangFoto.classList.remove('d-none');
        cameraStatus.textContent = 'Foto berhasil diambil.';
    }

    function refreshCsrf(payload) {
        if (payload && payload.csrf_hash) {
            csrfInput.value = payload.csrf_hash;
        }
    }

    async function submitPresensi(type) {
        if (!imageData.value) {
            Swal.fire('Foto Belum Ada', 'Ambil foto terlebih dahulu.', 'warning');
            return;
        }

        if (!latValue.value || !longValue.value) {
            Swal.fire('Lokasi Belum Ada', 'Pastikan lokasi sudah terdeteksi.', 'warning');
            return;
        }

        const formData = new FormData(form);
        formData.append('type', type);

        setButtonLoading(true);

        try {
            const response = await fetch(submitUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfInput.value
                },
                body: formData
            });

            const payload = await response.json();
            refreshCsrf(payload);

            if (!response.ok || payload.status !== 'success') {
                throw new Error(payload.message || 'Terjadi kesalahan saat menyimpan presensi.');
            }

            await Swal.fire('Berhasil', payload.message, 'success');
            window.location.reload();
        } catch (error) {
            Swal.fire('Gagal', error.message, 'error');
            setButtonLoading(false);
        }
    }

    btnAmbilFoto.addEventListener('click', capturePhoto);
    btnUlangFoto.addEventListener('click', capturePhoto);
    btnMasuk.addEventListener('click', () => submitPresensi('masuk'));
    btnPulang.addEventListener('click', () => submitPresensi('pulang'));

    initCamera();
    initLocation();

    window.addEventListener('beforeunload', () => {
        if (streamRef) {
            streamRef.getTracks().forEach(track => track.stop());
        }
    });
})();
</script>
<?= $this->endSection(); ?>
