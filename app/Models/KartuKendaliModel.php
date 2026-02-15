<?php

namespace App\Models;

use CodeIgniter\Model;

class KartuKendaliModel extends Model
{
    protected $table            = 'kartu_kendali';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nks', 'no_ruta', 'status_entry', 'tgl_entry'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
}
