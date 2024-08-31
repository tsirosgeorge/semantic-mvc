<?php

namespace App\core;

class Auth
{
    public static function startSession()
    {
        // Set session cookie parameters
        session_set_cookie_params([
            'lifetime' => SESSION_COOKIE_LIFETIME,
            'secure' => SESSION_COOKIE_SECURE,
            'httponly' => SESSION_COOKIE_HTTPONLY,
            'samesite' => SESSION_COOKIE_SAMESITE
        ]);

        session_start();

        // Ensure timezone is set
        date_default_timezone_set('Europe/Athens');

        // Check if session is expired
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > SESSION_TIMEOUT) {
            // Session has expired
            self::logout(); // Optionally log the user out or handle the expired session
        }
    }

    public static function refreshSession()
    {
        self::startSession(); // Ensure session is started

        // Refresh the session activity timestamp
        $_SESSION['LAST_ACTIVITY'] = time();
    }

    public static function check()
    {
        self::startSession(); // Ensure session is started and checked

        if (!isset($_SESSION['user_id'])) {
            header('Location: /');
            exit;
        }
    }

    public static function checkIfLoggedIn()
    {
        self::startSession(); // Ensure session is started
        return isset($_SESSION['user_id']);
    }

    public static function login($user)
    {
        self::startSession(); // Ensure session is started
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
    }

    public static function logout()
    {
        self::startSession(); // Ensure session is started
        session_unset();
        session_destroy();
        header('Location: ' . REDIRECT_AFTER_LOGOUT);
    }

    public static function regenerateSessionId()
    {
        if (session_status() == PHP_SESSION_ACTIVE) {
            session_regenerate_id(true); // Regenerate session ID and delete the old one
        }
    }

    public static function getRemainingSessionTime()
    {
        if (isset($_SESSION['LAST_ACTIVITY'])) {
            $expirationTime = $_SESSION['LAST_ACTIVITY'] + SESSION_TIMEOUT;
            $remainingTime = $expirationTime - time();
            return $remainingTime > 0 ? $remainingTime : 0; // Return remaining time in seconds or 0 if expired
        }
        return 0; // No session activity yet
    }
}
