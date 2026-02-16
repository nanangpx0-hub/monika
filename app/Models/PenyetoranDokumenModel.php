<?php

namespace App\Models;

use CodeIgniter\Model;

class PenyetoranDokumenModel extends Model
{
    protected $table            = 'penyetoran_dokumen';
    protected $primaryKey       = 'id_penyetoran';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_kegiatan', 'nama_pengirim', 'tanggal_penyerahan',
        'jenis_dokumen', 'keterangan', 'file_pendukung',
        'id_penyerah', 'id_penerima', 'status',
        'tanggal_konfirmasi', 'catatan_penerima',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * List all submissions with kegiatan + user names.
     */
    public function getAll(?string $filterStatus = null): array
    {
        $builder = $this->db->table('penyetoran_dokumen p')
            ->select('p.*, mk.nama_kegiatan,
                      u1.fullname AS nama_penyerah,
                      u2.fullname AS nama_penerima,
                      (SELECT COUNT(*) FROM penyetoran_dokumen_detail d WHERE d.id_penyetoran = p.id_penyetoran) AS jumlah_dokumen')
            ->join('master_kegiatan mk', 'mk.id_kegiatan = p.id_kegiatan', 'left')
            ->join('users u1', 'u1.id_user = p.id_penyerah', 'left')
            ->join('users u2', 'u2.id_user = p.id_penerima', 'left');

        if ($filterStatus && in_array($filterStatus, ['Diserahkan', 'Diterima', 'Ditolak'])) {
            $builder->where('p.status', $filterStatus);
        }

        return $builder->orderBy('p.created_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Get single submission with all detail rows.
     */
    public function getWithDetails(int $id): ?array
    {
        // Header
        $header = $this->db->table('penyetoran_dokumen p')
            ->select('p.*, mk.nama_kegiatan,
                      u1.fullname AS nama_penyerah,
                      u2.fullname AS nama_penerima')
            ->join('master_kegiatan mk', 'mk.id_kegiatan = p.id_kegiatan', 'left')
            ->join('users u1', 'u1.id_user = p.id_penyerah', 'left')
            ->join('users u2', 'u2.id_user = p.id_penerima', 'left')
            ->where('p.id_penyetoran', $id)
            ->get()->getRowArray();

        if (!$header) return null;

        // Details
        $header['details'] = $this->db->table('penyetoran_dokumen_detail')
            ->where('id_penyetoran', $id)
            ->orderBy('kode_nks', 'ASC')
            ->orderBy('no_urut_ruta', 'ASC')
            ->get()->getResultArray();

        return $header;
    }

    /**
     * Insert batch of detail rows.
     */
    public function insertBatchDetail(int $idPenyetoran, array $rows): int
    {
        $inserted = 0;
        $detailTable = $this->db->table('penyetoran_dokumen_detail');

        foreach ($rows as $row) {
            $detailTable->insert([
                'id_penyetoran'  => $idPenyetoran,
                'kode_prop'      => $row['kode_prop'] ?? '',
                'kode_kab'       => $row['kode_kab'] ?? '',
                'kode_nks'       => $row['kode_nks'] ?? '',
                'no_urut_ruta'   => (int) ($row['no_urut_ruta'] ?? 0),
                'status_selesai' => ($row['status_selesai'] ?? 'belum') === 'sudah' ? 'sudah' : 'belum',
                'tgl_penerimaan' => !empty($row['tgl_penerimaan']) ? $row['tgl_penerimaan'] : null,
                'created_at'     => date('Y-m-d H:i:s'),
            ]);
            $inserted++;
        }

        return $inserted;
    }

    /**
     * Confirm or reject receipt by PLS team.
     */
    public function confirmReceipt(int $id, int $userId, string $status, ?string $catatan = null): bool
    {
        return $this->update($id, [
            'id_penerima'        => $userId,
            'status'             => $status,
            'tanggal_konfirmasi' => date('Y-m-d H:i:s'),
            'catatan_penerima'   => $catatan,
        ]);
    }
}
