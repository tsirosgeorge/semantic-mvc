<?php

namespace App\Models;

use App\core\Model;

class UserModel extends Model
{
    // Retrieves a user by email and decrypts the password
    public function getUserByEmail($email)
    {
        $sql = "SELECT users.*, roles.name role , cast(AES_DECRYPT(password, 'StoKeli33')as char) AS decrypted_password 
                FROM users
                left join user_roles on users.id = user_roles.user_id
                left join roles on roles.id = user_roles.role_id
                WHERE email = ?";
        $values = ["s", $email];
        return $this->SelectSql($sql, $values);
    }
    public function createUser($email, $password)
    {
        $sql = "INSERT INTO users (email, password) VALUES (?, AES_ENCRYPT(?, 'StoKeli33'))";
        $values = ["ss", $email, $password];
        return $this->executeSql($sql, $values);
    }

    // New methods for roles and permissions
    public function getUserRoles($userId)
    {
        $sql = "SELECT r.name 
            FROM roles r 
            JOIN user_roles ur ON ur.role_id = r.id 
            WHERE ur.user_id = ?";
        $values = ["i", $userId];
        return $this->SelectSql(
            $sql,
            $values
        ); // Should return an array of associative arrays
    }

    public function getUserPermissions($userId)
    {
        $sql = "SELECT p.name 
            FROM permissions p 
            JOIN role_permissions rp ON rp.permission_id = p.id 
            JOIN user_roles ur ON ur.role_id = rp.role_id 
            WHERE ur.user_id = ?";
        $values = ["i", $userId];
        return $this->SelectSql(
            $sql,
            $values
        ); // Should return an array of associative arrays
    }
}
