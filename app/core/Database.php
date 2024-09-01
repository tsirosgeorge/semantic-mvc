<?php

namespace App\core;

use mysqli;

class Database
{
    private $conn;

    public function __construct()
    {
        // Fetch database configuration from environment variables
        $host = getenv('DB_HOST');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $name = getenv('DB_NAME');
        $port = getenv('DB_PORT');

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
            'DB_HOST' => getenv('DB_HOST'),
            'DB_USER' => getenv('DB_USER'),
            'DB_PASS' => '******', // Masking password for security
            'DB_NAME' => getenv('DB_NAME'),
            'DB_PORT' => getenv('DB_PORT')
        ];

        // Output the error message and configuration details in the browser
        echo "<strong>Error:</strong> " . htmlspecialchars($message) . "<br>";
        echo "<strong>MySQL Configuration:</strong><br>";
        echo "<pre>" . htmlspecialchars(print_r($config, true)) . "</pre>";

        // Optionally terminate the script
        exit();
    }
}
