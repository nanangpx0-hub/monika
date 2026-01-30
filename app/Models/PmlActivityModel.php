<?php

namespace App\Models;

use CodeIgniter\Model;

class PmlActivityModel extends Model
{
    protected $table            = 'pml_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id_pml', 'activity_type', 'description', 'location_lat', 'location_long', 'created_at'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Get monthly statistics for a PML
     */
    public function getMonthlyStats(int $pmlId, int $months = 6): array
    {
        $stats = [];
        $db = \Config\Database::connect();
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $startDate = $date . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));
            
            $count = $this->where('id_pml', $pmlId)
                          ->where('created_at >=', $startDate)
                          ->where('created_at <=', $endDate . ' 23:59:59')
                          ->countAllResults();
            
            $stats[] = [
                'month' => date('M Y', strtotime($startDate)),
                'count' => $count
            ];
        }
        
        return $stats;
    }

    /**
     * Get activity summary for a PML
     */
    public function getActivitySummary(int $pmlId): array
    {
        $total = $this->where('id_pml', $pmlId)->countAllResults();
        
        $byType = $this->select('activity_type, COUNT(*) as count')
                       ->where('id_pml', $pmlId)
                       ->groupBy('activity_type')
                       ->findAll();
        
        $thisMonth = $this->where('id_pml', $pmlId)
                          ->where('MONTH(created_at)', date('m'))
                          ->where('YEAR(created_at)', date('Y'))
                          ->countAllResults();
        
        $lastMonth = $this->where('id_pml', $pmlId)
                          ->where('MONTH(created_at)', date('m', strtotime('-1 month')))
                          ->where('YEAR(created_at)', date('Y', strtotime('-1 month')))
                          ->countAllResults();
        
        return [
            'total' => $total,
            'by_type' => $byType,
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'growth' => $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100, 1) : 0
        ];
    }

    /**
     * Get recent activities for a PML
     */
    public function getRecentActivities(int $pmlId, int $limit = 10): array
    {
        return $this->where('id_pml', $pmlId)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get activities with location data
     */
    public function getActivitiesWithLocation(int $pmlId): array
    {
        return $this->where('id_pml', $pmlId)
                    ->where('location_lat IS NOT NULL')
                    ->where('location_long IS NOT NULL')
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Log a new monitoring activity
     */
    public function logActivity(int $pmlId, string $type, string $description, ?float $lat = null, ?float $long = null): bool|int
    {
        return $this->insert([
            'id_pml' => $pmlId,
            'activity_type' => $type,
            'description' => $description,
            'location_lat' => $lat,
            'location_long' => $long,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}

