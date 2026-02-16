<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanModel extends Model
{
    protected $table = 'dokumen_survei';
    protected $primaryKey = 'id_dokumen';

    /**
     * Get document status summary (for Pie Chart).
     * Returns: [['status' => 'Uploaded', 'total' => 15], ...]
     */
    public function getStatusSummary(?int $idKegiatan = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $builder = $this->db->table('dokumen_survei');
        $builder->select('status, COUNT(*) as total');

        if ($idKegiatan) {
            $builder->where('id_kegiatan', $idKegiatan);
        }
        if ($dateFrom) {
            $builder->where('tanggal_setor >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('tanggal_setor <=', $dateTo);
        }

        $builder->groupBy('status');
        return $builder->get()->getResultArray();
    }

    /**
     * Get target vs realisasi per kecamatan (for Bar Chart).
     * - Target = jumlah ruta dari tanda_terima per kecamatan
     * - Realisasi = jumlah dokumen yang sudah diproses (status != 'Uploaded')
     */
    public function getTargetVsRealisasi(?int $idKegiatan = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $builder = $this->db->table('nks_master n');
        $builder->select("
            SUBSTRING(n.nks, 1, 6) AS kode_kec,
            COUNT(DISTINCT n.nks) AS total_nks,
            COALESCE(SUM(tt.jml_ruta_terima), 0) AS target_ruta,
            COALESCE(MAX(ds.realisasi), 0) AS realisasi
        ", false);
        $builder->join('tanda_terima tt', 'tt.nks = n.nks', 'left');

        // Subquery for realisasi
        $subquery = $this->db->table('dokumen_survei d2')
            ->select("SUBSTRING(d2.kode_wilayah, 1, 6) COLLATE utf8mb4_general_ci AS kec, COUNT(*) AS realisasi", false)
            ->where('d2.status !=', 'Uploaded');

        if ($idKegiatan) {
            $subquery->where('d2.id_kegiatan', $idKegiatan);
        }
        if ($dateFrom) {
            $subquery->where('d2.tanggal_setor >=', $dateFrom);
        }
        if ($dateTo) {
            $subquery->where('d2.tanggal_setor <=', $dateTo);
        }

        $subquery->groupBy('kec');
        $compiledSub = $subquery->getCompiledSelect();

        $builder->join("($compiledSub) ds", "ds.kec = SUBSTRING(n.nks, 1, 6)", 'left');

        $builder->groupBy('kode_kec');
        $builder->orderBy('kode_kec', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get document list for DataTable.
     * Returns full document list with PCL name, kegiatan name, status.
     */
    public function getDokumenList(?int $idKegiatan = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $builder = $this->db->table('dokumen_survei d');
        $builder->select('
            d.id_dokumen,
            d.kode_wilayah,
            d.status,
            d.tanggal_setor,
            d.pernah_error,
            d.created_at,
            u.fullname AS nama_pcl,
            u.sobat_id,
            mk.nama_kegiatan,
            mk.kode_kegiatan
        ');
        $builder->join('users u', 'u.id_user = d.id_petugas_pendataan', 'left');
        $builder->join('master_kegiatan mk', 'mk.id_kegiatan = d.id_kegiatan', 'left');

        if ($idKegiatan) {
            $builder->where('d.id_kegiatan', $idKegiatan);
        }
        if ($dateFrom) {
            $builder->where('d.tanggal_setor >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('d.tanggal_setor <=', $dateTo);
        }

        $builder->orderBy('d.created_at', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Get summary stats (for info boxes).
     */
    public function getSummaryStats(?int $idKegiatan = null, ?string $dateFrom = null, ?string $dateTo = null): array
    {
        $builder = $this->db->table('dokumen_survei');
        $builder->select("
            COUNT(*) as total,
            SUM(CASE WHEN status = 'Sudah Entry' THEN 1 ELSE 0 END) as sudah_entry,
            SUM(CASE WHEN status = 'Error' THEN 1 ELSE 0 END) as error,
            SUM(CASE WHEN status = 'Valid' THEN 1 ELSE 0 END) as valid,
            SUM(CASE WHEN status = 'Uploaded' THEN 1 ELSE 0 END) as uploaded
        ", false);

        if ($idKegiatan) {
            $builder->where('id_kegiatan', $idKegiatan);
        }
        if ($dateFrom) {
            $builder->where('tanggal_setor >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('tanggal_setor <=', $dateTo);
        }

        $result = $builder->get()->getRowArray();
        return $result ?: ['total' => 0, 'sudah_entry' => 0, 'error' => 0, 'valid' => 0, 'uploaded' => 0];
    }
}
