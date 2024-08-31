<?php

namespace App\Controllers;

use App\core\Controller;
use App\core\Auth;
use App\Models\UserModel;
use App\core\View;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        View::render('login', [], 'layouts');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->getUserByUsername($username);

            if ($user && password_verify($password, $user[0]['password'])) {
                Auth::login($user[0]);
                $this->jsonResponse(['success' => true, 'message' => 'Login successful']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid credentials']);
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        $this->jsonResponse(['success' => true, 'message' => 'Logged out successfully']);
    }
}
