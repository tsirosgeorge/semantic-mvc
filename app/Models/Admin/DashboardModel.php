<?php

namespace App\Models\Admin;

use App\core\Model;

class DashboardModel extends Model
{
    public function getTotals()
    {
        // Fetch total customers
        $sql = "SELECT count(*) as total_customers FROM customers WHERE 1 = ?";
        $values = ["i", 1];
        $customers = $this->SelectSql($sql, $values);

        // Fetch totals for resellers and softhouse in a single query
        $sql = "SELECT 
                    SUM(CASE WHEN roles.name = 'reseller' THEN 1 ELSE 0 END) as total_resellers,
                    SUM(CASE WHEN roles.name = 'softhouse' THEN 1 ELSE 0 END) as total_softhouse
                FROM users 
                LEFT JOIN user_roles ON user_roles.user_id = users.id 
                LEFT JOIN roles ON roles.id = user_roles.role_id";
        $values = [];
        $roles = $this->SelectSql($sql, $values);

        return [
            'total_customers' => $customers[0]['total_customers'],
            'total_resellers' => $roles[0]['total_resellers'],
            'total_softhouse' => $roles[0]['total_softhouse']
        ];
    }
}
