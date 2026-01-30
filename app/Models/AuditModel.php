<?php

namespace App\Models;

use CodeIgniter\Model;

class AuditModel extends Model
{
    protected $table = 'audit_logs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'action', 'details', 'ip_address'];
    protected $useTimestamps = false; // Handled by Database default

    public function log($action, $details = null, $userId = null)
    {
        $userId = $userId ?? session()->get('id_user');

        // If still null (e.g. API call without session), try to get from JWT request if possible, 
        // or just log as 0/NULL (System/Guest)

        $data = [
            'user_id' => $userId,
            'action' => $action,
            'details' => $details,
            'ip_address' => service('request')->getIPAddress()
        ];

        return $this->insert($data);
    }
}
