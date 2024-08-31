<?php
session_start();

require_once '../app/config/config.php';

// Set the timezone to Europe/Athens
date_default_timezone_set('Europe/Athens');

// Display current server time
echo 'Current Time: ' . date('Y-m-d H:i:s') . '<br>';

// Display last activity time
if (isset($_SESSION['LAST_ACTIVITY'])) {
    echo 'Last Activity: ' . date('Y-m-d H:i:s', $_SESSION['LAST_ACTIVITY']) . '<br>';
} else {
    echo 'No session activity yet.<br>';
}


// Display updated last activity time
echo 'Updated Last Activity: ' . date('Y-m-d H:i:s', $_SESSION['LAST_ACTIVITY']) . '<br>';

// Calculate session expiration time
$sessionTimeout = SESSION_TIMEOUT; // Get session timeout from config
$expirationTime = $_SESSION['LAST_ACTIVITY'] + $sessionTimeout;

// Display session expiration time
echo 'Session Expiration Time: ' . date('Y-m-d H:i:s', $expirationTime) . '<br>';

// Check if session is expired
$currentTime = time();
if ($currentTime > $expirationTime) {
    echo 'Session has expired.<br>';
} else {
    echo 'Session is still active.<br>';
}
