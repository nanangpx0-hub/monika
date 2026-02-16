<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Comprehensive Dummy Data Seeder for MONIKA
 * ─────────────────────────────────────────────
 * Populates ALL tables with realistic, interlinked test data.
 * Run: php spark db:seed ComprehensiveDummySeeder
 */
class ComprehensiveDummySeeder extends Seeder
{
    private array $userIds   = [];
    private array $nksList   = [];
    private array $kegIds    = [];
    private array $dokIds    = [];

    public function run(): void
    {
        $this->seedUsers();
        $this->seedKegiatan();
        $this->seedNksMaster();
        $this->seedLogistik();
        $this->seedTandaTerima();
        $this->seedDokumenSurvei();
        $this->seedAnomaliLog();
        $this->seedKartuKendali();
        $this->seedPresensi();
        $this->seedUjiPetik();
        $this->seedLoginAttempts();
        $this->seedPenyetoranDokumen();

        echo "\n✅ ALL DUMMY DATA SEEDED SUCCESSFULLY!\n";
    }

    // ── USERS (50 records) ───────────────────────────────────────
    private function seedUsers(): void
    {
        $db = \Config\Database::connect();

        // Check if already seeded
        if ($db->table('users')->countAllResults() >= 20) {
            echo "⏭ users already populated, skipping.\n";
            $this->userIds = array_column($db->table('users')->select('id_user')->get()->getResultArray(), 'id_user');
            return;
        }

        $password = password_hash('Monika@2026!', PASSWORD_BCRYPT, ['cost' => 12]);

        $kecamatans = ['Patrang', 'Sumbersari', 'Kaliwates', 'Ajung', 'Ambulu', 'Balung', 'Bangsalsari', 'Gumukmas', 'Jelbuk', 'Jenggawah', 'Kalisat', 'Kencong', 'Ledokombo', 'Mayang', 'Mumbulsari', 'Panti', 'Pakusari', 'Rambipuji', 'Semboro', 'Silo', 'Sukorambi', 'Sukowono', 'Tanggul', 'Tempurejo', 'Wuluhan'];

        $roles = [
            1 => 3,   // Admin: 3
            3 => 15,  // PCL: 15
            4 => 10,  // Pengolahan: 10
            5 => 10,  // PML: 10
            6 => 12,  // Pengawas Pengolahan: 12
        ];

        $counter = 0;
        $insertData = [];

        foreach ($roles as $roleId => $count) {
            $prefix = match ($roleId) {
                1 => 'admin',
                3 => 'pcl',
                4 => 'pengolah',
                5 => 'pml',
                6 => 'pengawas',
                default => 'user'
            };

            for ($i = 1; $i <= $count; $i++) {
                $counter++;
                $nama = $this->generateName($counter);
                $kec = $kecamatans[array_rand($kecamatans)];
                $insertData[] = [
                    'fullname'   => $nama,
                    'username'   => $prefix . '_' . str_pad((string)$i, 2, '0', STR_PAD_LEFT),
                    'email'      => $prefix . $i . '@bps-jember.go.id',
                    'password'   => $password,
                    'nik_ktp'    => '3509' . str_pad((string)mt_rand(1, 25), 2, '0', STR_PAD_LEFT) . date('dmy', strtotime('-' . mt_rand(20, 55) . ' years')) . str_pad((string)mt_rand(1, 9999), 4, '0', STR_PAD_LEFT),
                    'sobat_id'   => 'SBT' . str_pad((string)$counter, 5, '0', STR_PAD_LEFT),
                    'id_role'    => $roleId,
                    'id_supervisor' => null,
                    'is_active'  => ($counter % 12 === 0) ? 0 : 1, // ~8% inactive
                    'created_at' => date('Y-m-d H:i:s', strtotime('-' . mt_rand(30, 365) . ' days')),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }

        $db->table('users')->insertBatch($insertData);
        $this->userIds = array_column($db->table('users')->select('id_user')->get()->getResultArray(), 'id_user');

        // Set supervisors: PCL → PML, Pengolah → Pengawas
        $pmls = array_column($db->table('users')->where('id_role', 5)->get()->getResultArray(), 'id_user');
        $pengawas = array_column($db->table('users')->where('id_role', 6)->get()->getResultArray(), 'id_user');

        if (!empty($pmls)) {
            $pcls = $db->table('users')->where('id_role', 3)->get()->getResultArray();
            foreach ($pcls as $pcl) {
                $db->table('users')->where('id_user', $pcl['id_user'])->update(['id_supervisor' => $pmls[array_rand($pmls)]]);
            }
        }
        if (!empty($pengawas)) {
            $olah = $db->table('users')->where('id_role', 4)->get()->getResultArray();
            foreach ($olah as $o) {
                $db->table('users')->where('id_user', $o['id_user'])->update(['id_supervisor' => $pengawas[array_rand($pengawas)]]);
            }
        }

        echo "✅ Seeded " . count($insertData) . " users\n";
    }

    // ── MASTER KEGIATAN (10 records) ─────────────────────────────
    private function seedKegiatan(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('master_kegiatan')->countAllResults() >= 8) {
            echo "⏭ master_kegiatan already populated, skipping.\n";
            $this->kegIds = array_column($db->table('master_kegiatan')->select('id_kegiatan')->get()->getResultArray(), 'id_kegiatan');
            return;
        }

        $kegiatan = [
            ['Sakernas Februari 2026', 'SAK26FEB', '2026-02-01', '2026-02-28', 'Aktif'],
            ['Susenas Maret 2026', 'SSN26MAR', '2026-03-01', '2026-03-31', 'Aktif'],
            ['Survei Sosek Nasional 2026', 'SOSEK26', '2026-01-15', '2026-06-30', 'Aktif'],
            ['Survei Biaya Hidup 2026', 'SBH26', '2026-01-01', '2026-12-31', 'Aktif'],
            ['VIMK Triwulan 1', 'VIMK26Q1', '2026-01-01', '2026-03-31', 'Aktif'],
            ['Sensus Pertanian 2026', 'ST26', '2026-05-01', '2026-08-31', 'Aktif'],
            ['Sakernas Agustus 2025', 'SAK25AUG', '2025-08-01', '2025-08-31', 'Selesai'],
            ['Susenas September 2025', 'SSN25SEP', '2025-09-01', '2025-09-30', 'Selesai'],
            ['VIMK Triwulan 4 2025', 'VIMK25Q4', '2025-10-01', '2025-12-31', 'Selesai'],
            ['Sakernas Februari 2025', 'SAK25FEB', '2025-02-01', '2025-02-28', 'Selesai'],
        ];

        $existing = array_column($db->table('master_kegiatan')->select('kode_kegiatan')->get()->getResultArray(), 'kode_kegiatan');
        $inserted = 0;

        foreach ($kegiatan as $k) {
            if (in_array($k[1], $existing)) continue;
            $db->table('master_kegiatan')->insert([
                'nama_kegiatan'   => $k[0],
                'kode_kegiatan'   => $k[1],
                'tanggal_mulai'   => $k[2],
                'tanggal_selesai' => $k[3],
                'status'          => $k[4],
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
            $inserted++;
        }

        $this->kegIds = array_column($db->table('master_kegiatan')->select('id_kegiatan')->get()->getResultArray(), 'id_kegiatan');
        echo "✅ Seeded {$inserted} kegiatan (skipped " . (count($kegiatan) - $inserted) . " existing)\n";
    }

    // ── NKS MASTER (50 records) ──────────────────────────────────
    private function seedNksMaster(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('nks_master')->countAllResults() >= 20) {
            echo "⏭ nks_master already populated, skipping.\n";
            $this->nksList = array_column($db->table('nks_master')->select('nks')->get()->getResultArray(), 'nks');
            return;
        }

        $wilayah = [
            ['Patrang', 'Patrang'],
            ['Patrang', 'Bintoro'],
            ['Patrang', 'Jember Lor'],
            ['Sumbersari', 'Antirogo'],
            ['Sumbersari', 'Wirolegi'],
            ['Sumbersari', 'Kebonsari'],
            ['Kaliwates', 'Jember Kidul'],
            ['Kaliwates', 'Sempusari'],
            ['Kaliwates', 'Mangli'],
            ['Ajung', 'Ajung'],
            ['Ajung', 'Klompangan'],
            ['Ambulu', 'Ambulu'],
            ['Ambulu', 'Sabrang'],
            ['Balung', 'Balung Lor'],
            ['Balung', 'Balung Kidul'],
            ['Bangsalsari', 'Bangsalsari'],
            ['Bangsalsari', 'Sukorejo'],
            ['Gumukmas', 'Gumukmas'],
            ['Gumukmas', 'Kepanjen'],
            ['Jelbuk', 'Jelbuk'],
            ['Jelbuk', 'Panduman'],
            ['Jenggawah', 'Jenggawah'],
            ['Jenggawah', 'Jatisari'],
            ['Kalisat', 'Kalisat'],
            ['Kalisat', 'Sumberkalong'],
            ['Kencong', 'Kencong'],
            ['Kencong', 'Wonorejo'],
            ['Ledokombo', 'Ledokombo'],
            ['Ledokombo', 'Suren'],
            ['Mayang', 'Mayang'],
            ['Mayang', 'Tegalrejo'],
            ['Mumbulsari', 'Mumbulsari'],
            ['Panti', 'Panti'],
            ['Panti', 'Suci'],
            ['Pakusari', 'Pakusari'],
            ['Pakusari', 'Patempuran'],
            ['Rambipuji', 'Rambipuji'],
            ['Rambipuji', 'Pecoro'],
            ['Semboro', 'Semboro'],
            ['Silo', 'Silo'],
            ['Silo', 'Garahan'],
            ['Sukorambi', 'Sukorambi'],
            ['Sukorambi', 'Dukuhmencek'],
            ['Sukowono', 'Sukowono'],
            ['Tanggul', 'Tanggul Kulon'],
            ['Tanggul', 'Tanggul Wetan'],
            ['Tempurejo', 'Tempurejo'],
            ['Tempurejo', 'Pondokrejo'],
            ['Wuluhan', 'Wuluhan'],
            ['Wuluhan', 'Tanjungrejo'],
        ];

        $nksCodes = [];
        foreach ($wilayah as $i => $w) {
            $nksCode = str_pad((string)($i * 100 + mt_rand(1, 99)), 5, '0', STR_PAD_LEFT);
            $nksCodes[] = $nksCode;
            $db->table('nks_master')->insert([
                'nks'         => $nksCode,
                'kd_bs'       => '3509' . str_pad((string)($i + 1), 3, '0', STR_PAD_LEFT),
                'kecamatan'   => $w[0],
                'desa'        => $w[1],
                'target_ruta' => mt_rand(5, 10),
            ]);
        }

        $this->nksList = $nksCodes;
        echo "✅ Seeded " . count($wilayah) . " NKS\n";
    }

    // ── LOGISTIK (60 records) ────────────────────────────────────
    private function seedLogistik(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('logistik')->countAllResults() >= 20) {
            echo "⏭ logistik already populated, skipping.\n";
            return;
        }

        $items = [
            ['Kuesioner VSEN26.RT', 'Kuesioner', 'lembar', 'Baik', 'Gudang Utama'],
            ['Kuesioner VSEN26.KOR', 'Kuesioner', 'lembar', 'Baik', 'Gudang Utama'],
            ['Kuesioner SAK26.AK', 'Kuesioner', 'lembar', 'Baik', 'Gudang Utama'],
            ['Kuesioner SSN26.MSBP', 'Kuesioner', 'lembar', 'Baik', 'Gudang A'],
            ['Kuesioner SSN26.DSRT', 'Kuesioner', 'lembar', 'Baik', 'Gudang A'],
            ['Buku Pedoman PCL', 'Buku', 'buku', 'Baik', 'Gudang Utama'],
            ['Buku Pedoman PML', 'Buku', 'buku', 'Baik', 'Gudang Utama'],
            ['Buku Pedoman Pengolahan', 'Buku', 'buku', 'Baik', 'Gudang B'],
            ['Peta Wilkerstat Kec. Patrang', 'Peta', 'lembar', 'Baik', 'Rak Peta'],
            ['Peta Wilkerstat Kec. Sumbersari', 'Peta', 'lembar', 'Baik', 'Rak Peta'],
            ['Peta Wilkerstat Kec. Kaliwates', 'Peta', 'lembar', 'Baik', 'Rak Peta'],
            ['Pensil 2B', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Penghapus', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Clipboard A4', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Amplop Coklat A4', 'ATK', 'lembar', 'Baik', 'Lemari ATK'],
            ['Tali Rafia', 'ATK', 'gulung', 'Baik', 'Gudang B'],
            ['Map Ordner', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Ballpoint Hitam', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Ballpoint Merah', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Spidol Marker', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Stapler Besar', 'Peralatan', 'buah', 'Baik', 'Lemari ATK'],
            ['Isi Stapler No.10', 'ATK', 'kotak', 'Baik', 'Lemari ATK'],
            ['Paper Clip', 'ATK', 'kotak', 'Baik', 'Lemari ATK'],
            ['Tinta Printer Canon', 'Peralatan', 'botol', 'Baik', 'Gudang B'],
            ['Kertas HVS A4 70gsm', 'ATK', 'rim', 'Baik', 'Gudang Utama'],
            ['Kertas HVS F4 70gsm', 'ATK', 'rim', 'Baik', 'Gudang Utama'],
            ['Kardus Arsip', 'ATK', 'buah', 'Baik', 'Gudang B'],
            ['Tablet Samsung Tab A7', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
            ['Tablet Samsung Tab A8', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
            ['Tablet Lenovo Tab M10', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
            ['Powerbank 10000mAh', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
            ['Senter LED', 'Peralatan', 'buah', 'Baik', 'Gudang B'],
            ['Rompi Lapangan', 'Peralatan', 'buah', 'Baik', 'Gudang Utama'],
            ['Topi Lapangan', 'Peralatan', 'buah', 'Baik', 'Gudang Utama'],
            ['Tas Ransel Lapangan', 'Peralatan', 'buah', 'Baik', 'Gudang Utama'],
            ['Payung Lipat', 'Peralatan', 'buah', 'Baik', 'Gudang Utama'],
            ['Jaket Hujan', 'Peralatan', 'buah', 'Rusak Ringan', 'Gudang B'],
            ['Kabel Data USB-C', 'Peralatan', 'buah', 'Baik', 'Ruang IT'],
            ['Kalkulator Scientific', 'Peralatan', 'buah', 'Baik', 'Lemari ATK'],
            ['Label Stiker', 'ATK', 'lembar', 'Baik', 'Lemari ATK'],
            ['Box File Plastik', 'ATK', 'buah', 'Baik', 'Gudang B'],
            ['Gunting Besar', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Cutter', 'ATK', 'buah', 'Baik', 'Lemari ATK'],
            ['Lakban Bening', 'ATK', 'roll', 'Baik', 'Lemari ATK'],
            ['Lakban Coklat', 'ATK', 'roll', 'Baik', 'Lemari ATK'],
            ['Kartu Tanda Petugas', 'Kuesioner', 'lembar', 'Baik', 'Gudang Utama'],
            ['Surat Tugas PCL', 'Kuesioner', 'lembar', 'Baik', 'Gudang Utama'],
            ['Surat Tugas PML', 'Kuesioner', 'lembar', 'Baik', 'Gudang Utama'],
            ['Formulir SP2D', 'Kuesioner', 'lembar', 'Baik', 'Gudang A'],
            ['Formulir SPJ', 'Kuesioner', 'lembar', 'Baik', 'Gudang A'],
            ['Laptop Dell Latitude', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
            ['Laptop Lenovo ThinkPad', 'Peralatan', 'unit', 'Rusak Ringan', 'Ruang IT'],
            ['Mouse Wireless', 'Peralatan', 'buah', 'Baik', 'Ruang IT'],
            ['Flash Disk 32GB', 'Peralatan', 'buah', 'Baik', 'Ruang IT'],
            ['Hard Disk Eksternal 1TB', 'Peralatan', 'buah', 'Baik', 'Ruang IT'],
            ['UPS 600VA', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
            ['Printer Canon G3010', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
            ['Scanner Epson', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
            ['Kabel LAN 5m', 'Peralatan', 'buah', 'Baik', 'Ruang IT'],
            ['Switch HUB 8 Port', 'Peralatan', 'unit', 'Baik', 'Ruang IT'],
        ];

        $insertData = [];
        foreach ($items as $i => $item) {
            $insertData[] = [
                'kode_barang'  => 'BRG-' . str_pad((string)($i + 1), 4, '0', STR_PAD_LEFT),
                'nama_barang'  => $item[0],
                'kategori'     => $item[1],
                'satuan'       => $item[2],
                'stok'         => mt_rand(5, 500),
                'kondisi'      => $item[3],
                'lokasi'       => $item[4],
                'keterangan'   => ($i % 5 === 0) ? 'Perlu restok bulan depan' : null,
                'created_at'   => date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 180) . ' days')),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
        }

        $db->table('logistik')->insertBatch($insertData);
        echo "✅ Seeded " . count($insertData) . " logistik items\n";
    }

    // ── TANDA TERIMA (80 records) ────────────────────────────────
    private function seedTandaTerima(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('tanda_terima')->countAllResults() >= 20) {
            echo "⏭ tanda_terima already populated, skipping.\n";
            return;
        }
        if (empty($this->nksList)) return;

        $insertData = [];
        foreach ($this->nksList as $nks) {
            // 1–2 records per NKS
            $count = mt_rand(1, 2);
            for ($j = 0; $j < $count; $j++) {
                $insertData[] = [
                    'nks'             => $nks,
                    'jml_ruta_terima' => mt_rand(1, 10),
                    'tgl_terima'      => date('Y-m-d', strtotime('-' . mt_rand(1, 60) . ' days')),
                    'created_at'      => date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 60) . ' days')),
                ];
            }
        }

        $db->table('tanda_terima')->insertBatch($insertData);
        echo "✅ Seeded " . count($insertData) . " tanda_terima records\n";
    }

    // ── DOKUMEN SURVEI (100 records) ─────────────────────────────
    private function seedDokumenSurvei(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('dokumen_survei')->countAllResults() >= 20) {
            echo "⏭ dokumen_survei already populated, skipping.\n";
            $this->dokIds = array_column($db->table('dokumen_survei')->select('id_dokumen')->get()->getResultArray(), 'id_dokumen');
            return;
        }
        if (empty($this->kegIds) || empty($this->userIds)) return;

        $pclIds = array_column($db->table('users')->where('id_role', 3)->select('id_user')->get()->getResultArray(), 'id_user');
        $procIds = array_column($db->table('users')->where('id_role', 4)->select('id_user')->get()->getResultArray(), 'id_user');

        $statuses = ['Uploaded', 'Sudah Entry', 'Error', 'Valid'];
        $wilayahCodes = ['3509010001', '3509010002', '3509020001', '3509020003', '3509030001', '3509030004', '3509040002', '3509050001', '3509050003', '3509060001', '3509070001', '3509070002', '3509080001', '3509090001', '3509100001', '3509110001', '3509120001', '3509130001', '3509140001', '3509150001'];

        $insertData = [];
        for ($i = 0; $i < 100; $i++) {
            $status = $statuses[array_rand($statuses)];
            $procBy = in_array($status, ['Sudah Entry', 'Valid']) && !empty($procIds) ? $procIds[array_rand($procIds)] : null;
            $insertData[] = [
                'id_kegiatan'         => $this->kegIds[array_rand($this->kegIds)],
                'kode_wilayah'        => $wilayahCodes[array_rand($wilayahCodes)] . str_pad((string)mt_rand(0, 99), 2, '0', STR_PAD_LEFT),
                'id_petugas_pendataan'=> !empty($pclIds) ? $pclIds[array_rand($pclIds)] : $this->userIds[0],
                'processed_by'        => $procBy,
                'status'              => $status,
                'pernah_error'        => ($status === 'Error' || mt_rand(0, 5) === 0) ? 1 : 0,
                'tanggal_setor'       => date('Y-m-d', strtotime('-' . mt_rand(1, 90) . ' days')),
                'created_at'          => date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 90) . ' days')),
                'updated_at'          => date('Y-m-d H:i:s'),
            ];
        }

        $db->table('dokumen_survei')->insertBatch($insertData);
        $this->dokIds = array_column($db->table('dokumen_survei')->select('id_dokumen')->get()->getResultArray(), 'id_dokumen');
        echo "✅ Seeded " . count($insertData) . " dokumen_survei records\n";
    }

    // ── ANOMALI LOG (60 records) ─────────────────────────────────
    private function seedAnomaliLog(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('anomali_log')->countAllResults() >= 10) {
            echo "⏭ anomali_log already populated, skipping.\n";
            return;
        }
        if (empty($this->dokIds)) return;

        $procIds = array_column($db->table('users')->where('id_role', 4)->select('id_user')->get()->getResultArray(), 'id_user');
        $jenisErrors = ['Duplikat Data', 'Inkonsistensi Jawaban', 'Data Kosong', 'Kode Wilayah Salah', 'Nomor Ruta Tidak Valid', 'Isian di Luar Range', 'Variabel Wajib Kosong', 'Skip Pattern Error', 'Jawaban Ganda', 'Format Tanggal Salah'];
        $keterangans = [
            'Ditemukan duplikasi pada blok IV Ruta',
            'Jawaban B5K2 tidak konsisten dengan B3K1',
            'Blok III tidak terisi sama sekali',
            'Kode kecamatan tidak ada di masterfile',
            'Nomor urut ruta melebihi target',
            'Pendapatan bernilai negatif',
            'Variabel R401 wajib diisi tetapi kosong',
            'Seharusnya R303 langsung ke R310',
            'Jawaban multi-kode di pertanyaan single-code',
            'Format tanggal bukan dd/mm/yyyy',
            'Isian umur 0 pada ART status kawin',
            'Jumlah ART tidak sesuai daftar',
        ];

        $insertData = [];
        for ($i = 0; $i < 60; $i++) {
            $insertData[] = [
                'id_dokumen'            => $this->dokIds[array_rand($this->dokIds)],
                'id_petugas_pengolahan' => !empty($procIds) ? $procIds[array_rand($procIds)] : null,
                'jenis_error'           => $jenisErrors[array_rand($jenisErrors)],
                'keterangan'            => $keterangans[array_rand($keterangans)],
                'created_at'            => date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 60) . ' days')),
            ];
        }

        $db->table('anomali_log')->insertBatch($insertData);
        echo "✅ Seeded " . count($insertData) . " anomali_log records\n";
    }

    // ── KARTU KENDALI (100 records) ──────────────────────────────
    private function seedKartuKendali(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('kartu_kendali')->countAllResults() >= 20) {
            echo "⏭ kartu_kendali already populated, skipping.\n";
            return;
        }
        if (empty($this->nksList) || empty($this->userIds)) return;

        $procIds = array_column($db->table('users')->where('id_role', 4)->select('id_user')->get()->getResultArray(), 'id_user');
        if (empty($procIds)) $procIds = $this->userIds;

        $insertData = [];
        foreach ($this->nksList as $nks) {
            $targetRuta = mt_rand(5, 10);
            for ($r = 1; $r <= $targetRuta; $r++) {
                $status = (mt_rand(0, 4) < 3) ? 'Clean' : 'Error';
                $insertData[] = [
                    'nks'           => $nks,
                    'no_ruta'       => $r,
                    'user_id'       => $procIds[array_rand($procIds)],
                    'status_entry'  => $status,
                    'is_patch_issue'=> ($status === 'Error' && mt_rand(0, 2) === 0) ? 1 : 0,
                    'tgl_entry'     => date('Y-m-d', strtotime('-' . mt_rand(1, 45) . ' days')),
                    'created_at'    => date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 45) . ' days')),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
            }
        }

        // Batch insert in chunks to avoid packet size issues
        foreach (array_chunk($insertData, 100) as $chunk) {
            $db->table('kartu_kendali')->insertBatch($chunk);
        }
        echo "✅ Seeded " . count($insertData) . " kartu_kendali records\n";
    }

    // ── PRESENSI (100 records) ───────────────────────────────────
    private function seedPresensi(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('presensi')->countAllResults() >= 20) {
            echo "⏭ presensi already populated, skipping.\n";
            return;
        }

        $procIds = array_column($db->table('users')->where('id_role', 4)->select('id_user')->get()->getResultArray(), 'id_user');
        if (empty($procIds)) $procIds = $this->userIds;

        $insertData = [];
        for ($day = 0; $day < 14; $day++) {
            $tgl = date('Y-m-d', strtotime("-{$day} days"));
            // Weekday check
            if (in_array(date('N', strtotime($tgl)), ['6', '7'])) continue;

            foreach ($procIds as $uid) {
                if (mt_rand(0, 9) < 8) { // 80% attendance
                    $masuk  = sprintf('%02d:%02d:00', mt_rand(7, 8), mt_rand(0, 59));
                    $pulang = (mt_rand(0, 4) < 3) ? sprintf('%02d:%02d:00', mt_rand(15, 17), mt_rand(0, 59)) : null;
                    $insertData[] = [
                        'user_id'       => $uid,
                        'tgl'           => $tgl,
                        'jam_masuk'     => $masuk,
                        'jam_pulang'    => $pulang,
                        'foto_masuk'    => null,
                        'foto_pulang'   => null,
                        'lokasi_masuk'  => '-8.17' . mt_rand(10, 99) . ',113.7' . mt_rand(10, 99),
                        'lokasi_pulang' => $pulang ? '-8.17' . mt_rand(10, 99) . ',113.7' . mt_rand(10, 99) : null,
                        'ip_address'    => '192.168.1.' . mt_rand(1, 254),
                        'user_agent'    => 'Mozilla/5.0 Chrome/12' . mt_rand(0, 9),
                    ];
                }
            }
        }

        foreach (array_chunk($insertData, 100) as $chunk) {
            $db->table('presensi')->insertBatch($chunk);
        }
        echo "✅ Seeded " . count($insertData) . " presensi records\n";
    }

    // ── UJI PETIK (80 records) ───────────────────────────────────
    private function seedUjiPetik(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('uji_petik')->countAllResults() >= 10) {
            echo "⏭ uji_petik already populated, skipping.\n";
            return;
        }
        if (empty($this->nksList)) return;

        $variabels = ['R401 Pendapatan', 'R301 Jenis Kelamin', 'R302 Umur', 'R303 Status Kawin', 'R310 Pendidikan', 'R401 Pengeluaran Pangan', 'R402 Pengeluaran Non-Pangan', 'R501 Status Pekerjaan', 'R502 Lapangan Usaha', 'R503 Jam Kerja'];
        $alasans = ['Salah Ketik', 'Salah Baca', 'Terlewat', 'Salah Kode', 'Lainnya'];

        $insertData = [];
        for ($i = 0; $i < 80; $i++) {
            $insertData[] = [
                'nks'              => $this->nksList[array_rand($this->nksList)],
                'no_ruta'          => mt_rand(1, 10),
                'variabel'         => $variabels[array_rand($variabels)],
                'isian_k'          => 'Kuesioner: ' . mt_rand(100, 9999),
                'isian_c'          => 'Corrected: ' . mt_rand(100, 9999),
                'alasan_kesalahan' => $alasans[array_rand($alasans)],
                'catatan'          => ($i % 4 === 0) ? 'Sudah dikonfirmasi ke responden' : null,
                'created_at'       => date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 45) . ' days')),
                'updated_at'       => date('Y-m-d H:i:s'),
            ];
        }

        $db->table('uji_petik')->insertBatch($insertData);
        echo "✅ Seeded " . count($insertData) . " uji_petik records\n";
    }

    // ── LOGIN ATTEMPTS (50 records) ──────────────────────────────
    private function seedLoginAttempts(): void
    {
        $db = \Config\Database::connect();
        if (!$db->tableExists('login_attempts')) {
            echo "⏭ login_attempts table does not exist, skipping.\n";
            return;
        }
        if ($db->table('login_attempts')->countAllResults() >= 10) {
            echo "⏭ login_attempts already populated, skipping.\n";
            return;
        }

        $usernames = ['admin_01', 'pcl_01', 'pcl_03', 'pcl_05', 'pengolah_02', 'unknown_user', 'hacker', 'test123', 'admin', 'pml_01'];
        $agents = ['Chrome/120', 'Firefox/119', 'Safari/17', 'Edge/120', 'PostmanRuntime/7'];

        $insertData = [];
        for ($i = 0; $i < 50; $i++) {
            $success = mt_rand(0, 3) > 0; // 75% success
            $insertData[] = [
                'ip_address'    => '192.168.' . mt_rand(0, 5) . '.' . mt_rand(1, 254),
                'username'      => $usernames[array_rand($usernames)],
                'attempt_time'  => date('Y-m-d H:i:s', strtotime('-' . mt_rand(0, 720) . ' minutes')),
                'success'       => $success ? 1 : 0,
                'user_agent'    => 'Mozilla/5.0 ' . $agents[array_rand($agents)],
                'error_message' => $success ? null : 'Password salah',
            ];
        }

        $db->table('login_attempts')->insertBatch($insertData);
        echo "✅ Seeded " . count($insertData) . " login_attempts records\n";
    }

    // ── PENYETORAN DOKUMEN (15 headers + ~100 details) ───────────
    private function seedPenyetoranDokumen(): void
    {
        $db = \Config\Database::connect();
        if ($db->table('penyetoran_dokumen')->countAllResults() >= 5) {
            echo "⏭ penyetoran_dokumen already populated, skipping.\n";
            return;
        }
        if (empty($this->kegIds) || empty($this->userIds)) return;

        $pclIds = array_column($db->table('users')->where('id_role', 3)->select('id_user')->get()->getResultArray(), 'id_user');
        $procIds = array_column($db->table('users')->where('id_role', 4)->select('id_user')->get()->getResultArray(), 'id_user');
        if (empty($pclIds)) $pclIds = $this->userIds;

        $pengirimNames = ['Andi Prasetyo', 'Budi Santoso', 'Citra Dewi', 'Diana Sari', 'Eko Wahyudi', 'Fitri Handayani', 'Galang Putra', 'Hesti Rahayu', 'Irfan Maulana', 'Joko Widodo', 'Kartika Sari', 'Lukman Hakim', 'Maya Putri', 'Nanda Pratama', 'Oki Firmansyah'];
        $jenisDoks = ['Kuesioner Susenas', 'Kuesioner Sakernas', 'Kuesioner VIMK', 'Daftar SP2D', 'Kuesioner SBH'];
        $statuses = ['Diserahkan', 'Diserahkan', 'Diserahkan', 'Diterima', 'Diterima', 'Diterima', 'Diterima', 'Ditolak'];

        for ($h = 0; $h < 15; $h++) {
            $status = $statuses[array_rand($statuses)];
            $procId = ($status !== 'Diserahkan' && !empty($procIds)) ? $procIds[array_rand($procIds)] : null;

            $headerData = [
                'id_kegiatan'        => $this->kegIds[array_rand($this->kegIds)],
                'nama_pengirim'      => $pengirimNames[$h],
                'tanggal_penyerahan' => date('Y-m-d', strtotime('-' . mt_rand(1, 45) . ' days')),
                'jenis_dokumen'      => $jenisDoks[array_rand($jenisDoks)],
                'keterangan'         => ($h % 3 === 0) ? 'Batch ke-' . ($h + 1) . ' dari tim sosial' : null,
                'file_pendukung'     => null,
                'id_penyerah'        => $pclIds[array_rand($pclIds)],
                'id_penerima'        => $procId,
                'status'             => $status,
                'tanggal_konfirmasi' => ($status !== 'Diserahkan') ? date('Y-m-d H:i:s', strtotime('-' . mt_rand(0, 30) . ' days')) : null,
                'catatan_penerima'   => ($status === 'Ditolak') ? 'Dokumen tidak lengkap, harap lengkapi' : (($status === 'Diterima') ? 'Diterima dengan baik' : null),
                'created_at'         => date('Y-m-d H:i:s', strtotime('-' . mt_rand(1, 45) . ' days')),
                'updated_at'         => date('Y-m-d H:i:s'),
            ];

            $db->table('penyetoran_dokumen')->insert($headerData);
            $idPenyetoran = $db->insertID();

            // Generate 5–10 detail rows per header
            $numRuta = mt_rand(5, 10);
            $nksCode = str_pad((string)mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $detailRows = [];

            for ($r = 1; $r <= $numRuta; $r++) {
                $sudah = ($status === 'Diterima' || mt_rand(0, 2) < 2) ? 'sudah' : 'belum';
                $detailRows[] = [
                    'id_penyetoran'  => $idPenyetoran,
                    'kode_prop'      => '35',
                    'kode_kab'       => '09',
                    'kode_nks'       => $nksCode,
                    'no_urut_ruta'   => $r,
                    'status_selesai' => $sudah,
                    'tgl_penerimaan' => ($sudah === 'sudah') ? date('Y-m-d', strtotime('-' . mt_rand(0, 30) . ' days')) : null,
                    'created_at'     => date('Y-m-d H:i:s'),
                ];
            }

            $db->table('penyetoran_dokumen_detail')->insertBatch($detailRows);
        }

        echo "✅ Seeded 15 penyetoran_dokumen headers + details\n";
    }

    // ── HELPERS ──────────────────────────────────────────────────

    private function generateName(int $seed): string
    {
        $first = ['Ahmad', 'Budi', 'Candra', 'Dewi', 'Eka', 'Fitri', 'Galih', 'Hendra', 'Indra', 'Joko', 'Kartika', 'Lukman', 'Maya', 'Nanda', 'Oki', 'Putri', 'Rahmat', 'Siti', 'Tono', 'Umi', 'Vina', 'Wahyu', 'Yanto', 'Zahra', 'Agus', 'Bambang', 'Dian', 'Endang', 'Fajar', 'Gunawan', 'Hani', 'Imam', 'Jihan', 'Kurnia', 'Lina', 'Mira', 'Nur', 'Oki', 'Puji', 'Rina', 'Sri', 'Tia', 'Udin', 'Vera', 'Widi', 'Yuni', 'Zaki', 'Arif', 'Bayu', 'Cindy'];
        $last = ['Prasetyo', 'Santoso', 'Wijaya', 'Kusuma', 'Hidayat', 'Rahayu', 'Permana', 'Saputra', 'Handayani', 'Wibowo', 'Nugroho', 'Putra', 'Sari', 'Utami', 'Lestari', 'Firmansyah', 'Cahyono', 'Setiawan', 'Purnomo', 'Susanto', 'Hartono', 'Budiman', 'Suryadi', 'Maulana', 'Hakim'];
        return $first[$seed % count($first)] . ' ' . $last[$seed % count($last)];
    }
}
