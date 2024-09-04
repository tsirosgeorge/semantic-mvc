<?php

namespace App\Models\Reseller;

use App\core\Model;


class CustomersModel extends Model
{

    public function getUnauthorizedCustomers()
    {
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where authorized = 0 and contract = 0 and signed = 0 and customers.resellerid = ? order by id desc";
        $values = ["i", $_SESSION['user_id']];
        return $this->SelectSql($sql, $values);
    }

    public function getAuthorizedCustomers()
    {
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where authorized = 1 and contract = 1 and signed = 1 and customers.resellerid = ? order by id desc";
        $values = ["i", $_SESSION['user_id']];
        return $this->SelectSql($sql, $values);
    }

    public function getCustomersWithContract()
    {
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where authorized = 1 and contract = 0 and signed = 1 and customers.resellerid = ? order by id desc";
        $values = ["i", $_SESSION['user_id']];
        return $this->SelectSql($sql, $values);
    }

    public function getReadyCustomers()
    {
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where authorized = 1 and contract = 1 and signed = 1 and customers.resellerid = ? order by id desc";
        $values = ["i", $_SESSION['user_id']];
        return $this->SelectSql($sql, $values);
    }
}
