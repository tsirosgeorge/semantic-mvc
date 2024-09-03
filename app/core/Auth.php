<?php

namespace App\core;

use App\Models\UserModel;

class Auth
{
    // Initialize session settings
    private static function initializeSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Set session cookie parameters
            session_set_cookie_params([
                'lifetime' => (int)$_ENV['SESSION_COOKIE_LIFETIME'],
                'secure' => (bool)$_ENV['SESSION_COOKIE_SECURE'],
                'httponly' => (bool)$_ENV['SESSION_COOKIE_HTTPONLY'],
                'samesite' => $_ENV['SESSION_COOKIE_SAMESITE']
            ]);

            // Start the session
            session_start();
        }
    }

    public static function startSession()
    {
        self::initializeSession(); // Ensure session is initialized

        // Ensure timezone is set
        date_default_timezone_set($_ENV['TIMEZONE']);

        // Check if session is expired
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > (int)$_ENV['SESSION_TIMEOUT']) {
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
        self::startSession();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['BASE_URL'] = $_ENV['BASE_URL'];
        $_SESSION['API_URL'] = $_ENV['API_URL'];

        $userModel = new UserModel();
        $roles = $userModel->getUserRoles($user['id']);
        $_SESSION['user_roles'] = $roles; // Ensure roles are correctly assigned

        $permissions = $userModel->getUserPermissions($user['id']);
        $_SESSION['user_permissions'] = array_unique(array_column($permissions, 'name')); // Ensure no duplicates
    }




    public static function logout()
    {
        self::startSession(); // Ensure session is started
        session_unset();
        session_destroy();
        header('Location: ' . $_ENV['REDIRECT_AFTER_LOGOUT']);
        exit;
    }

    public static function regenerateSessionId()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true); // Regenerate session ID and delete the old one
        }
    }

    public static function getRemainingSessionTime()
    {
        if (isset($_SESSION['LAST_ACTIVITY'])) {
            $expirationTime = $_SESSION['LAST_ACTIVITY'] + (int)$_ENV['SESSION_TIMEOUT'];
            $remainingTime = $expirationTime - time();
            return $remainingTime > 0 ? $remainingTime : 0; // Return remaining time in seconds or 0 if expired
        }
        return 0; // No session activity yet
    }

    public static function userRoles()
    {
        self::startSession();
        if (isset($_SESSION['user_id'])) {
            $userModel = new UserModel();
            return $userModel->getUserRoles($_SESSION['user_id']);
        }
        return [];
    }

    public static function userPermissions()
    {
        self::startSession();
        if (isset($_SESSION['user_id'])) {
            $userModel = new UserModel();
            return $userModel->getUserPermissions($_SESSION['user_id']);
        }
        return [];
    }

    public static function hasRole($role)
    {
        self::startSession();
        return isset($_SESSION['user_roles']) && in_array($role, array_column($_SESSION['user_roles'], 'name'));
    }


    public static function hasPermission($permission)
    {
        self::startSession();
        return in_array($permission, $_SESSION['user_permissions']);
    }


    public static function setPermissions($permissions)
    {
        self::startSession();
        $_SESSION['permissions'] = $permissions;
    }


    public static function authorize($permission)
    {
        self::startSession();

        if (!self::hasPermission($permission)) {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Forbidden']);
            exit;
        }
    }
}
