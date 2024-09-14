<?php

namespace App\Models\Softhouse;

use App\core\Model;


class CustomersModel extends Model
{
    public function getAllCustomers()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerId  where users.parent_id = ? order by customers.id desc";
        $values = ["i", $_SESSION["user_id"]];
        return $this->SelectSql($sql, $values);
    }

    public function getCustomerById($id)
    {
        $sql = "SELECT customers.*,users.name rfullname FROM customers left join users on users.id = customers.resellerId WHERE customers.id = ? and users.parent_id = ?";
        $values = ["ii", $id, $_SESSION["user_id"]];
        return $this->SelectSql($sql, $values);
    }

    public function createCustomer($data)
    {
        $sql = "INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)";
        $values = ["ssss", $data['name'], $data['email'], $data['phone'], $data['address']];
        return $this->executeSql($sql, $values);
    }

    public function updateCustomer($id, $data)
    {
        $sql = "UPDATE customers SET email = ?, password = ? , afm = ? , city = ? , address = ? , postalcode = ? , company = ? WHERE id = ?";
        $values = ["sssssssi", $data['email'], $data['password'], $data['afm'], $data['city'], $data['address'], $data['postalcode'], $data['company'], $id];
        return $this->executeSql($sql, $values);
    }

    public function deleteCustomer($id)
    {
        $sql = "DELETE FROM customers WHERE id = ?";
        $values = ["i", $id];
        return $this->executeSql($sql, $values);
    }

    public function getUnauthorizedCustomers()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where users.parent_id = ? and authorized = 0 and contract = 0 and signed = 0 order by id desc";
        $values = ["i", $_SESSION["user_id"]];
        return $this->SelectSql($sql, $values);
    }

    public function authorizeCustomer($id)
    {
        $sql = "UPDATE customers SET authorized = 1 WHERE id = ?";
        $values = ["i", $id];
        return $this->executeSql($sql, $values);
    }

    public function getAuthorizedCustomers()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where users.parent_id = ? and authorized = 1 and contract = 0 and signed = 0 order by id desc";
        $values = ["i", $_SESSION["user_id"]];
        return $this->SelectSql($sql, $values);
    }

    public function getCustomersAuthAndSigned()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where users.parent_id = ? and authorized = 1 and contract = 0 and signed = 1 order by id desc";
        $values = ["i", $_SESSION["user_id"]];
        return $this->SelectSql($sql, $values);
    }

    public function getAllButNotAactive()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where authorized = 1 and contract = 1 and signed = 1 and activate = 0 and users.parent_id = ? order by id desc";
        $values = ["i", $_SESSION["user_id"]];
        return $this->SelectSql($sql, $values);
    }

    public function activateCustomer($id)
    {
        $sql = "UPDATE customers SET activate = 1 WHERE id = ?";
        $values = ["i", $id];
        return $this->executeSql($sql, $values);
    }

    public function getActiveCustomers()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where authorized = 1 and contract = 1 and signed = 1 and activate = 1 and users.parent_id = ? order by id desc";
        $values = ["i", $_SESSION["user_id"]];
        return $this->SelectSql($sql, $values);
    }

    public function getResellers()
    {
        $sql = "SELECT * FROM resellers where 1 = ? order by id desc";
        $values = ["i", 1];
        $resellers = $this->SelectSql($sql, $values);
        $sql2 = "SELECT resellerid, COUNT(*) AS customer_count FROM customers where 1 = ? GROUP BY resellerid";
        $values2 = ["i", 1];
        $totalCustomersPerReseller = $this->SelectSql($sql2, $values2);

        return ['resellers' => $resellers, 'totalCustomersPerReseller' => $totalCustomersPerReseller];
    }

    public function setContract($id, $data)
    {

        $sql = "UPDATE customers SET contract = ? WHERE id = ?";
        $values = ["ii", $data['contract'], $id];
        return $this->executeSql($sql, $values);
    }
}
