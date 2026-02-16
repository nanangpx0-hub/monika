<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id_user';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'fullname', 'username', 'email', 'password',
        'nik_ktp', 'sobat_id', 'id_role', 'id_supervisor', 'is_active',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // ── Query Helpers ────────────────────────────────────────────

    /**
     * Get all users with their role name via join.
     */
    public function getWithRole(?int $filterRole = null): array
    {
        $builder = $this->db->table('users u')
            ->select('u.*, r.role_name')
            ->join('roles r', 'r.id_role = u.id_role', 'left');

        if ($filterRole) {
            $builder->where('u.id_role', $filterRole);
        }

        return $builder->orderBy('u.id_user', 'ASC')->get()->getResultArray();
    }

    /**
     * Search users by keyword (fullname, username, email).
     */
    public function search(string $keyword, ?int $filterRole = null): array
    {
        $builder = $this->db->table('users u')
            ->select('u.*, r.role_name')
            ->join('roles r', 'r.id_role = u.id_role', 'left')
            ->groupStart()
                ->like('u.fullname', $keyword)
                ->orLike('u.username', $keyword)
                ->orLike('u.email', $keyword)
            ->groupEnd();

        if ($filterRole) {
            $builder->where('u.id_role', $filterRole);
        }

        return $builder->orderBy('u.id_user', 'ASC')->get()->getResultArray();
    }

    /**
     * Get all available roles for dropdowns.
     */
    public function getRoles(): array
    {
        if (! $this->db->tableExists('roles')) {
            return [];
        }
        return $this->db->table('roles')->orderBy('id_role', 'ASC')->get()->getResultArray();
    }

    /**
     * Get users that can be supervisors (PML, role 5).
     */
    public function getSupervisors(): array
    {
        return $this->where('id_role', 5)->where('is_active', 1)->findAll();
    }
}
