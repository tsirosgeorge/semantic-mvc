<?php

namespace App\Models\Admin;

use App\core\Model;


class CustomersModel extends Model
{
    public function getAllCustomers()
    {
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerId where 1 = ? order by customers.id desc";
        $values = ["i", 1];
        return $this->SelectSql($sql, $values);
    }

    public function getCustomerById($id)
    {
        $sql = "SELECT customers.*,resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerId WHERE customers.id = ?";
        $values = ["i", $id];
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
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where 1 = ? and authorized = 0 and contract = 0 and signed = 0 order by id desc";
        $values = ["i", 1];
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
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where authorized = ? and contract = 0 and signed = 0 order by id desc";
        $values = ["i", 1];
        return $this->SelectSql($sql, $values);
    }

    public function getCustomersAuthAndSigned()
    {
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where authorized = ? and contract = 0 and signed = 1 order by id desc";
        $values = ["i", 1];
        return $this->SelectSql($sql, $values);
    }

    public function getAllButNotAactive()
    {
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where authorized = 1 and contract = 1 and signed = 1 and activate = ? order by id desc";
        $values = ["i", 0];
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
        $sql = "SELECT customers.*, resellers.fullname rfullname FROM customers left join resellers on resellers.id = customers.resellerid where authorized = 1 and contract = 1 and signed = 1 and activate = ? order by id desc";
        $values = ["i", 1];
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

    public function registerEskap($data)
    {
        $postData = array(
            'email' => $data['email'],
            'pass' => $data['pass'],
            'company' => $data['company'],
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'afm' => $data['afm'],
            'address' => $data['address'],
            'postcode' => $data['postcode'],
            'city' => $data['city'],
            'username' => 'semantic',
            'password' => 'Sem@nt!cEsk@p2024'
        );
        $jsonData = json_encode($postData);


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.eskap.gr/v2/api.php?op=addCustomer',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        if ($response === false) {
            $error = curl_error($curl);

            return ['success' => false, 'message' => 'Customer not inserted successfully', 'status_code' => 404];
        } else {
            $this->executeSql("UPDATE customers set eskap = 1 where id = ?", array("i", $data["id"]));
            return ['success' => true, 'msg' => $response, 'status_code' => 200];
        }
    }
}
