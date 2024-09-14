<?php

namespace App\Models\Softhouse;

use App\core\Model;

class ResellersModel extends Model
{
    // Retrieves a user by email and decrypts the password
    public function getAllResellers()
    {
        $sql = "SELECT * FROM users where parent_id = ? order by id desc";
        $values = ["i", $_SESSION["user_id"]];
        $resellers = $this->SelectSql($sql, $values);
        $sql2 = "SELECT resellerid, COUNT(*) AS customer_count FROM customers where 1 = ? GROUP BY resellerid";
        $values2 = ["i", 1];
        $totalCustomersPerReseller = $this->SelectSql($sql2, $values2);

        return ['resellers' => $resellers, 'totalCustomersPerReseller' => $totalCustomersPerReseller];
    }

    public function deleteReseller($id)
    {
        // Check if the reseller has associated customers
        $reseller = $this->selectSql("SELECT id FROM resellers WHERE id = ?", ["i", $id]);

        if (!$reseller) {
            return ['success' => false, 'message' => 'Reseller not found', 'status_code' => 404];
        }

        $resellerID = $reseller[0]['id'];
        $customers = $this->selectSql("SELECT * FROM customers WHERE resellerId = ?", ["i", $resellerID]);

        if (count($customers) > 0) {
            return ['success' => false, 'message' => 'Reseller was not deleted successfully', 'status_code' => 401];
        }

        // Delete reseller and associated user account
        $this->executeSql("DELETE FROM resellers WHERE id = ?", ["i", $id]);
        $this->executeSql("DELETE FROM users WHERE id = ?", ["i", $reseller[0]['user_id']]);

        return ['success' => true, 'message' => 'Reseller deleted successfully'];
    }

    public function storeReseller($data)
    {
        $email = $data['email'];
        $name = $data['name'];
        $afm = $data['afm'];
        $password = $data['pass'];

        // Check if the reseller's user account already exists
        $existingUser = $this->selectSql("SELECT * FROM users WHERE email = ?", ["s", $email]);

        if ($existingUser) {
            $userId = $existingUser[0]['id'];
        } else {
            // Create a new user account
            $this->executeSql(
                "INSERT INTO users (email, password) VALUES (?, AES_ENCRYPT(?, ?))",
                ["sss", $email, $password, 'StoKeli33']
            );

            // Retrieve the new user's ID
            $newUser = $this->selectSql("SELECT * FROM users WHERE email = ?", ["s", $email]);
            $userId = $newUser[0]['id'];
        }

        // Check if the reseller already exists in the resellers table
        $existingReseller = $this->selectSql("SELECT * FROM resellers WHERE user_id = ?", ["i", $userId]);

        if ($existingReseller) {
            return ['success' => false, 'message' => 'Reseller already exists', 'status_code' => 401];
        }

        // Insert the reseller into the resellers table
        $this->executeSql(
            "INSERT INTO resellers (email, fullname, afm, user_id, eskap_id) VALUES (?, ?, ?, ?, ?)",
            ["sssii", $email, $name, $afm, $userId, 0]
        );

        return ['success' => true, 'message' => 'Reseller added successfully'];
    }
}
