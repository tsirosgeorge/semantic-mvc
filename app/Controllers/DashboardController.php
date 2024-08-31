<?php

namespace App\Controllers;

use App\core\Controller;
use App\core\Auth;
use App\core\View;

class DashboardController extends Controller
{
    public function index()
    {
        Auth::check(); // Ensure user is authenticated

        // Prepare any data for the layout (not the view itself)
        $data = ['username' => $_SESSION['user_name'], 'title' => 'Dashboard Home'];

        // Render the HTML view with the layout
        View::render('dashboard', $data, 'main');
    }
}
