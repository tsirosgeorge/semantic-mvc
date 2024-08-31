<?php

namespace App\Models;

use App\core\Model;

class UserModel extends Model
{
    // Retrieves a user by username and decrypts the password
    public function getUserByUsername($username)
    {
        $sql = "SELECT *, cast(AES_DECRYPT(password, 'StoKeli33')as char) AS decrypted_password 
                FROM users 
                WHERE username = ?";
        $values = ["s", $username];
        return $this->SelectSql($sql, $values);
    }
    public function createUser($username, $password)
    {
        $sql = "INSERT INTO users (username, password) VALUES (?, AES_ENCRYPT(?, 'StoKeli33'))";
        $values = ["ss", $username, $password];
        return $this->executeSql($sql, $values);
    }
}
