<?php

namespace App\Controllers;

use App\core\Controller;
use App\core\Auth;
use App\Models\UserModel;
use App\lib\PasswordUtils;
use App\core\View;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        View::render('login', [], 'auth');
    }

    public function login()
    {
        header('Content-Type: application/json'); // Set content type to JSON
        $_POST = json_decode(file_get_contents('php://input'), true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->getUserByUsername($username);

            if ($user && PasswordUtils::verifyPassword($password, $user[0]['decrypted_password'])) {
                Auth::login($user[0]);
                $this->jsonResponse(['success' => true, 'message' => 'Login successful']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Invalid credentials', 'data' => $user]);
            }
        }
    }

    public function register()
    {
        header('Content-Type: application/json'); // Set content type to JSON
        $_POST = json_decode(file_get_contents('php://input'), true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $userModel = new UserModel();
            $user = $userModel->createUser($username, $password);

            if ($user) {
                $this->jsonResponse(['success' => true, 'message' => 'Register successful']);
            } else {
                $this->jsonResponse(['success' => false, 'message' => 'Something wen wrong', 'data' => $user]);
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        $this->jsonResponse(['success' => true, 'message' => 'Logged out successfully']);
    }

    public function refreshSession()
    {

        if (!Auth::checkIfLoggedIn()) {
            header('HTTP/1.1 401 Unauthorized');
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
            exit;
        }
        Auth::refreshSession(); // Refresh the session activity timestamp

        // Respond with a success message or JSON response
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Session refreshed.']);
    }
}