<?php

namespace App\Models\Admin;

use App\core\Model;

class ResellersModel extends Model
{
    // Retrieves a user by email and decrypts the password
    public function getAllResellers()
    {
        // Query to get all resellers
        $sql = "SELECT 
                    users.id,
                    users.name,
                    users.email,
                    users.afm,
                    users.created_at,
                    users.updated_at
                FROM users 
                LEFT JOIN user_roles ON user_roles.user_id = users.id 
                LEFT JOIN roles ON roles.id = user_roles.role_id  
                WHERE roles.name = ?;";
        $values = ["s", 'reseller'];
        $resellers = $this->SelectSql($sql, $values);

        // Query to get customer count per reseller
        $sql2 = "SELECT users.id AS resellerid, COUNT(customers.id) AS customer_count 
             FROM users
             LEFT JOIN user_roles ON user_roles.user_id = users.id 
             LEFT JOIN roles ON roles.id = user_roles.role_id 
             LEFT JOIN customers ON customers.resellerid = users.id
             WHERE roles.name = ?
             GROUP BY users.id";
        $values2 = ["s", 'reseller'];
        $totalCustomersPerReseller = $this->SelectSql($sql2, $values2);

        return ['resellers' => $resellers, 'totalCustomersPerReseller' => $totalCustomersPerReseller];
    }


    public function deleteReseller($id)
    {
        // Check if the reseller has associated customers
        $reseller = $this->selectSql("SELECT id FROM users WHERE id = ?", ["i", $id]);

        if (!$reseller) {
            return ['success' => false, 'message' => 'Reseller not found', 'status_code' => 404];
        }

        $resellerID = $reseller[0]['id'];
        $customers = $this->selectSql("SELECT * FROM customers WHERE resellerId = ?", ["i", $resellerID]);

        if (count($customers) > 0) {
            return ['success' => false, 'message' => 'Reseller was not deleted successfully', 'status_code' => 401];
        }

        // Delete reseller and associated user account        
        $this->executeSql("DELETE FROM user_roles WHERE user_id = ?", ["i", $resellerID]);
        $this->executeSql("DELETE FROM users WHERE id = ?", ["i", $resellerID]);

        return ['success' => true, 'message' => 'Reseller deleted successfully'];
    }

    public function storeReseller($data)
    {
        $email = $data['email'];
        $name = $data['name'];
        $afm = $data['afm'];
        $password = $data['pass'];
        $encryptionKey = 'StoKeli33'; // Consider moving this to a config file or environment variable

        // Check if the user's email already exists
        $existingUser = $this->selectSql("SELECT id FROM users WHERE email = ?", ["s", $email]);

        if ($existingUser) {
            $userId = $existingUser[0]['id'];
        } else {
            // Insert new user
            $this->executeSql(
                "INSERT INTO users (email, password, name, afm, parent_id) VALUES (?, AES_ENCRYPT(?, ?), ?, ?, ?)",
                ["sssssi", $email, $password, $encryptionKey, $name, $afm, 1]
            );

            // Retrieve the new user's ID
            $newUser = $this->selectSql("SELECT id FROM users WHERE email = ?", ["s", $email]);
            if (!$newUser) {
                return ['success' => false, 'message' => 'User creation failed', 'status_code' => 500];
            }
            $userId = $newUser[0]['id'];
        }

        // Check if the reseller role already exists for this user
        $existingReseller = $this->selectSql("SELECT * FROM user_roles WHERE user_id = ? AND role_id = ?", ["ii", $userId, 3]);

        if ($existingReseller) {
            return ['success' => false, 'message' => 'Reseller already exists', 'status_code' => 401];
        }

        // Assign the reseller role
        $roleAssignment = $this->executeSql(
            "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)",
            ["ii", $userId, 3] // 3 represents the reseller role_id
        );

        if (!$roleAssignment) {
            return ['success' => false, 'message' => 'Role assignment failed', 'status_code' => 500];
        }

        return ['success' => true, 'message' => 'Reseller added successfully', 'status_code' => 200];
    }
}
