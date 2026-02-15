<?php

namespace App\Models;

use CodeIgniter\Model;

class TandaTerimaModel extends Model
{
    protected $table            = 'tanda_terima';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nks', 'jml_ruta_terima', 'tgl_terima', 'created_at'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
}
