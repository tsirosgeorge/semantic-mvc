<?php

namespace App\core;

use mysqli;

class Model
{
    protected $conn;

    public function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    protected function refValues($arr)
    {
        if (strnatcmp(phpversion(), '5.3') >= 0) {
            $refs = [];
            foreach ($arr as $key => $value) {
                $refs[$key] = &$arr[$key];
            }
            return $refs;
        }
        return $arr;
    }

    protected function SelectSql($q, $values)
    {
        $stmt = $this->conn->prepare($q);
        call_user_func_array([$stmt, "bind_param"], $this->refValues($values));
        $stmt->execute();
        if (!$stmt) echo mysqli_error($this->conn);
        $result = $stmt->get_result();
        $rows = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) $rows[] = $row;
        }
        $stmt->close();
        return $rows;
    }

    protected function otherSql($q, $values)
    {
        $stmt = $this->conn->prepare($q);
        if (!$stmt) echo mysqli_error($this->conn);
        call_user_func_array([$stmt, "bind_param"], $this->refValues($values));
        $result = $stmt->execute();
        $GconnError = mysqli_errno($this->conn);
        $stmt->close();
        return ["result" => $result, "error" => $GconnError];
    }

    public function __destruct()
    {
        $this->conn->close();
    }
}
