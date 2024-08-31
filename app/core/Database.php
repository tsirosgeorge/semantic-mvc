<?php

namespace App\core;

use mysqli;

class Database
{
    private $conn;

    public function __construct()
    {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        if ($this->conn->connect_error) {
            $this->handleError("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    private function handleError($message)
    {
        // Gather detailed information
        $config = [
            'DB_HOST' => DB_HOST,
            'DB_USER' => DB_USER,
            'DB_PASS' => '******', // Masking password for security
            'DB_NAME' => DB_NAME,
            'DB_PORT' => DB_PORT
        ];

        // Output the error message and configuration details in the browser
        echo "<strong>Error:</strong> " . htmlspecialchars($message) . "<br>";
        echo "<strong>MySQL Configuration:</strong><br>";
        echo "<pre>" . htmlspecialchars(print_r($config, true)) . "</pre>";

        // Optionally terminate the script
        exit();
    }
}
