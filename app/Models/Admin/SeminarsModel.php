<?php

namespace App\Models\Admin;

use App\core\Model;



class SeminarsModel extends Model
{
    public function fetchSeminars()
    {
        $seminars = $this->SelectSql("SELECT * FROM seminars where 1 = ?", array("i", 1));
        return $seminars;
    }

    public function createSeminar($data)
    {
        $sdate = $data["sdate"];
        $sstart = $data["sstart"];
        $send = $data["send"];
        $sdescr = $data["sdescr"];
        $surl = $data["surl"];
        $r = $this->executeSql(
            "INSERT INTO seminars (date,starttime,endtime,descr,link) values (?,?,?,?,?)",
            array('sssss', $sdate, $sstart, $send, $sdescr, $surl)
        );
        return $r;
    }
    public function deleteSeminar($id)
    {
        $delete = $this->executeSql("DELETE FROM seminars WHERE id = ?", array("i", $id));
        return $delete;
    }
}
