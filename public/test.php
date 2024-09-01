<?php

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Replace with your database credentials
$host = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$database = getenv('DB_NAME');

// Establish a database connection
print("Connecting to database...\n");

echo "host: $host\n";
echo "username: $username\n";
echo "password: $password\n";
echo "database: $database\n";
