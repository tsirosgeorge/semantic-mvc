<?php

namespace App\Controllers;

use App\core\Controller;
use App\core\Auth;
use App\core\View;
use App\Models\DashboardModel;

class DashboardController extends Controller
{
    public function index()
    {
        Auth::check();
        $contacts = (new DashboardModel())->indexContacts();
        $firstTwoLetters = substr($_SESSION['user_name'], 0, 2);
        $data = [
            'username' => $_SESSION['user_name'],
            'firstTwoLetters' => $firstTwoLetters,
            'title' => 'Contacts',
            'contacts' => json_encode($contacts), // Encode to JSON
            'base_url' => $_SESSION['BASE_URL'],
            'currentPage' => 'contacts'
        ];
        View::render('dashboard/dashboard', $data, 'main');
    }

    public function members()
    {
        Auth::check();
        $members = (new DashboardModel())->indexMembers();
        $firstTwoLetters = substr($_SESSION['user_name'], 0, 2);
        $data = [
            'username' => $_SESSION['user_name'],
            'firstTwoLetters' => $firstTwoLetters,
            'title' => 'Members',
            'members' => json_encode($members), // Encode to JSON
            'base_url' => $_SESSION['BASE_URL'],
            'currentPage' => 'members'
        ];
        View::render('dashboard/members', $data, 'main');
    }

    public function b2bInterest()
    {
        Auth::check();
        $b2binterest = (new DashboardModel())->indexB2BInterest();
        $firstTwoLetters = substr($_SESSION['user_name'], 0, 2);
        $dataFromModel = json_encode($b2binterest, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

        $data = [
            'username' => $_SESSION['user_name'],
            'firstTwoLetters' => $firstTwoLetters,
            'title' => 'B2B Interest',
            'dataFromModel' => $dataFromModel,
            'base_url' => $_SESSION['BASE_URL'],
            'currentPage' => 'b2binterest'
        ];
        View::render('dashboard/b2binterest', $data, 'main');
    }
}
