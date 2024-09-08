<?php

namespace App\Models\Admin;

use App\core\Model;

class SofthousesModel extends Model
{
    // Retrieves a user by email and decrypts the password
    public function getAllSofthouses()
    {
        // Query to get all softhouses
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
        $values = ["s", 'softhouse'];
        $softhouses = $this->SelectSql($sql, $values);

        // Query to get customer count per reseller
        $sql2 = "SELECT softhouse.id AS softhouseid, COUNT(customer.id) AS customer_count 
                FROM users softhouse
                LEFT JOIN users customer ON customer.parent_id = softhouse.id
                WHERE softhouse.id IN (
                    SELECT u.id 
                    FROM users u
                    LEFT JOIN user_roles ur ON ur.user_id = u.id
                    LEFT JOIN roles r ON r.id = ur.role_id
                    WHERE r.name = ?
                )
                GROUP BY softhouse.id";
        $values2 = ["s", 'softhouse'];
        $totalCustomersPerSofthouse = $this->SelectSql($sql2, $values2);

        return ['softhouses' => $softhouses, 'totalCustomersPerSofthouse' => $totalCustomersPerSofthouse];
    }


    public function deleteSofthouse($id)
    {
        // Check if the softhouse has associated softhouses
        $softhouse = $this->selectSql("SELECT id FROM users WHERE id = ?", ["i", $id]);

        if (!$softhouse) {
            return ['success' => false, 'message' => 'Softhouse not found', 'status_code' => 404];
        }

        $softhouseID = $softhouse[0]['id'];
        $softhouses = $this->selectSql("SELECT * FROM users WHERE parent_id = ?", ["i", $softhouseID]);

        if (count($softhouses) > 0) {
            return ['success' => false, 'message' => 'Softhouse was not deleted successfully', 'status_code' => 401];
        }

        // Delete softhouse and associated user account        
        $this->executeSql("DELETE FROM user_roles WHERE user_id = ?", ["i", $softhouseID]);
        $this->executeSql("DELETE FROM users WHERE id = ?", ["i", $softhouseID]);

        return ['success' => true, 'message' => 'Softhouse deleted successfully'];
    }

    public function storeSofthouse($data)
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
        $existingReseller = $this->selectSql("SELECT * FROM user_roles WHERE user_id = ? AND role_id = ?", ["ii", $userId, 2]);

        if ($existingReseller) {
            return ['success' => false, 'message' => 'Reseller already exists', 'status_code' => 401];
        }

        // Assign the reseller role
        $roleAssignment = $this->executeSql(
            "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)",
            ["ii", $userId, 2] // 2 represents the softhouse role_id
        );

        if (!$roleAssignment) {
            return ['success' => false, 'message' => 'Role assignment failed', 'status_code' => 500];
        }

        return ['success' => true, 'message' => 'Reseller added successfully', 'status_code' => 200];
    }
}
