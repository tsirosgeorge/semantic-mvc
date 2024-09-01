<?php

namespace App\Models;

use App\core\Model;

class DashboardModel extends Model
{
    // Retrieves a user by username and decrypts the password
    public function indexContacts()
    {
        $sql = "SELECT contacts.*, products.descr from contacts
                left join products on products.id = contacts.`idproduct` where 1 = ? order by id desc";
        $values = ["i", 1];
        return $this->SelectSql($sql, $values);
    }
    public function indexMembers()
    {
        $sql = "SELECT members.* from members where 1 = ? order by id desc";
        $values = ["i", 1];
        return $this->SelectSql($sql, $values);
    }
    public function indexB2BInterest()
    {
        $sql = "SELECT b2b_interested.* from b2b_interested where 1 = ? order by id desc";
        $values = ["i", 1];
        return $this->SelectSql($sql, $values);
    }
}
