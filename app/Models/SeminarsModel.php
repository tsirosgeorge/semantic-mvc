<?php

namespace App\Models;

use App\core\Model;



class SeminarsModel extends Model
{
    public function fetchSeminars()
    {
        $seminars = $this->SelectSql("SELECT * FROM seminars where 1 = ?", array("i", 1));
        return $seminars;
    }
}
