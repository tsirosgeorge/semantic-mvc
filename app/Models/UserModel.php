<?php

namespace App\Models;

use App\core\Model;

class UserModel extends Model
{
    public function getUserByUsername($username)
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $values = ["s", $username];
        return $this->SelectSql($sql, $values);
    }
}
