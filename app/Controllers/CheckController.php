<?php

namespace App\Controllers;

use App\core\Controller;

class CheckController extends Controller
{
    public function test()
    {
        // Display environment variables using $_ENV
        echo 'DB_HOST: ' . $_ENV['DB_HOST'] . "<br>";
        echo 'DB_USER: ' . $_ENV['DB_USER'] . "<br>";
        echo 'DB_PASS: ' . $_ENV['DB_PASS'] . "<br>";
        echo 'DB_NAME: ' . $_ENV['DB_NAME'] . "<br>";
        echo 'DB_PORT: ' . $_ENV['DB_PORT'] . "<br>";

        // Check if .env file exists
        if (file_exists(__DIR__ . '/../../.env')) {
            echo ".env file exists\n";
        } else {
            echo ".env file does not exist\n";
        }
    }
}
