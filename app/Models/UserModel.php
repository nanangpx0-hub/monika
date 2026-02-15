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
        'nik_ktp', 'sobat_id', 'id_role', 'id_supervisor', 'is_active'
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Note: Password hashing is handled explicitly in Auth controller.
    // Do NOT add hashPassword callbacks here to avoid double-hashing risk.
}
