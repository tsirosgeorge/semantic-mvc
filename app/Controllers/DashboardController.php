<?php

namespace App\Controllers;

use App\core\Controller;
use App\core\Auth;
use App\core\View;
use App\lib\GeneralUtils;

class DashboardController extends Controller
{
    public function index()
    {
        Auth::check();
        $data = [
            'username' => $_SESSION['user_name'],
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['user_name']),
            'title' => 'Contacts',
            'base_url' => $_SESSION['BASE_URL'],
            'scripts' => [
                '<script src="{{base_url}}assets/js/scripts/tables.js"></script>',
            ],
            'currentPage' => 'contacts'
        ];
        View::render('dashboard/dashboard', $data, 'main');
    }

    public function members()
    {
        Auth::check();
        $data = [
            'username' => $_SESSION['user_name'],
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['user_name']),
            'title' => 'Members',
            'base_url' => $_SESSION['BASE_URL'],
            'currentPage' => 'members'
        ];
        View::render('dashboard/members', $data, 'main');
    }

    public function b2bInterest()
    {
        Auth::check();
        $data = [
            'username' => $_SESSION['user_name'],
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['user_name']),
            'title' => 'B2B Interest',
            'base_url' => $_SESSION['BASE_URL'],
            'currentPage' => 'b2binterest'
        ];
        View::render('dashboard/b2binterest', $data, 'main');
    }
}
