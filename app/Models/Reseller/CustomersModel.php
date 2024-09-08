<?php

namespace App\Models\Reseller;

use App\core\Model;
use GrahamCampbell\ResultType\Success;

class CustomersModel extends Model
{

    public function getUnauthorizedCustomers()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where authorized = 0 and contract = 0 and signed = 0 and customers.resellerid = ? order by id desc";
        $values = ["i", $_SESSION['user_id']];
        return $this->SelectSql($sql, $values);
    }

    public function getAuthorizedCustomers()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where authorized = 1 and contract = 0 and signed = 0 and customers.resellerid = ? order by id desc";
        $values = ["i", $_SESSION['user_id']];
        return $this->SelectSql($sql, $values);
    }

    public function getCustomersWithContract()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where authorized = 1 and contract = 0 and signed = 1 and customers.resellerid = ? order by id desc";
        $values = ["i", $_SESSION['user_id']];
        return $this->SelectSql($sql, $values);
    }

    public function getReadyCustomers()
    {
        $sql = "SELECT customers.*, users.name rfullname FROM customers left join users on users.id = customers.resellerid where authorized = 1 and contract = 1 and signed = 1 and customers.resellerid = ? order by id desc";
        $values = ["i", $_SESSION['user_id']];
        return $this->SelectSql($sql, $values);
    }

    public function createCustomer($data)
    {
        $cemail = $data["cemail"];
        $cpass = $data["cpass"];
        $cname = $data["cname"];
        $cafm = $data["cafm"];
        $ccity = $data["ccity"];
        $caddress = $data["caddress"];
        $ctk = $data["ctk"];
        $ccompany = $data["ccompany"];

        $checkAfm = $this->SelectSql("SELECT * FROM customers where afm = ?", array("s", $cafm));
        if ($checkAfm) {
            return ['message' => 'Customer already exists', 'status' => 400, 'success' => false];
        }

        $checkEmail = $this->SelectSql("SELECT * FROM customers where email = ?", array("s", $cemail));
        if ($checkEmail) {
            return ['message' => 'Customer already exists', 'status' => 400, 'success' => false];
        }


        $r = $this->executeSql("INSERT INTO customers (email,password,fullname,afm,city,address,postalcode,company,resellerId) values (?,?,?,?,?,?,?,?,?)", array('ssssssssi', $cemail, $cpass, $cname, $cafm, $ccity, $caddress, $ctk, $ccompany, $_SESSION["user_id"]));

        if (!$r) {
            return ['message' => 'Failed to add customer', 'status' => 500, 'success' => false];
        }

        return ['success' => true, 'message' => 'Customer added successfully', 'status' => 200];
    }

    public function deleteCustomer($id)
    {
        $r = $this->executeSql("DELETE FROM customers where id = ?", array("i", $id));
        if (!$r) {
            return ['message' => 'Failed to delete customer', 'status' => 500, 'success' => false];
        }

        return ['success' => true, 'status' => 200];
    }

    public function setSigned($id)
    {
        $r = $this->executeSql("UPDATE customers set signed = 1 where id = ?", array("i", $id));
        if (!$r) {
            return ['message' => 'Failed to update customer', 'status' => 500, 'success' => false];
        }

        return ['success' => true, 'status' => 200];
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
