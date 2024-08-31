<?php

namespace App\core;

class Auth
{
    public static function check()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    public static function login($user)
    {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['username'];
    }

    public static function logout()
    {
        session_start();
        session_destroy();
        header('Location: /login');
    }
}
