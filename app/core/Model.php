<?php

namespace App\core;

class Model
{
    protected $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();

        // Optional: check connection status
        if ($this->conn->ping() === false) {
            $this->handleError("Connection lost.");
        }
    }

    // Convert an array to references
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

    // Perform a SELECT query
    protected function selectSql($query, $values = [])
    {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            $this->handleError("Prepare failed: " . $this->conn->error);
        }

        if ($values) {
            call_user_func_array([$stmt, 'bind_param'], $this->refValues($values));
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
        }
        $stmt->close();
        return $rows;
    }

    // Perform INSERT, UPDATE, DELETE queries
    protected function executeSql($query, $values = [])
    {
        $stmt = $this->conn->prepare($query);
        if (!$stmt) {
            $this->handleError("Prepare failed: " . $this->conn->error);
        }

        if ($values) {
            call_user_func_array([$stmt, 'bind_param'], $this->refValues($values));
        }

        $result = $stmt->execute();
        $stmt->close();

        if (!$result) {
            $this->handleError("Execution failed: " . $this->conn->error);
        }

        return $result;
    }

    // Handle errors gracefully
    private function handleError($message)
    {
        // Log the error message to a file        

        // Display a generic error message to the user
        echo "<strong>Error:</strong>" . $message . "<br>";

        // Optionally terminate the script
        exit();
    }
}
