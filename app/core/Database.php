<?php

namespace App\core;

use mysqli;

class Database
{
    private $conn;

    public function __construct()
    {
        // Fetch database configuration from environment variables
        $host = $_ENV['DB_HOST'];
        $user = $_ENV['DB_USER'];
        $pass = $_ENV['DB_PASS'];
        $name = $_ENV['DB_NAME'];
        $port = $_ENV['DB_PORT'];

        // Initialize MySQL connection
        $this->conn = new mysqli($host, $user, $pass, $name, $port);

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
            'DB_HOST' => $_ENV['DB_HOST'],
            'DB_USER' => $_ENV['DB_USER'],
            'DB_PASS' => '******', // Masking password for security
            'DB_NAME' => $_ENV['DB_NAME'],
            'DB_PORT' => $_ENV['DB_PORT']
        ];

        // Output the error message and configuration details in the browser
        echo "<strong>Error:</strong> " . htmlspecialchars($message) . "<br>";
        echo "<strong>MySQL Configuration:</strong><br>";
        echo "<pre>" . htmlspecialchars(print_r($config, true)) . "</pre>";

        // Optionally terminate the script
        exit();
    }
}
