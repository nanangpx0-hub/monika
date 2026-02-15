<?php

namespace App\Models;

use CodeIgniter\Model;

class NksModel extends Model
{
    protected $table            = 'nks_master';
    protected $primaryKey       = 'nks';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nks', 'kd_bs', 'kecamatan', 'desa', 'target_ruta'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
}
