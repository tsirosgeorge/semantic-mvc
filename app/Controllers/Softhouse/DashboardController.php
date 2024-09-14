<?php

namespace App\Controllers\Softhouse;

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
            'email' => $_SESSION['email'],
            'title' => 'Dashboard',
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'modals' => [],
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'currentPage' => 'dashboard',
            'scripts' => [
                '<script src="{{base_url}}assets/js/scripts/dashboard.js"></script>',
            ],
        ];
        View::render('Softhouse/dashboard/dashboard', $data, 'softhouse');
    }

    public function allCustomers()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'All Customers',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'all-customers',
            'modals' => [
                'Modals/Softhouse/edit-customer',
            ],
            'scripts' => [
                '<script src="{{base_url}}assets/js/softhouse/allCustomers.js"></script>',
            ],
        ];
        View::render('Softhouse/dashboard/all-customers', $data, 'softhouse');
    }
    public function unauthorizedCustomers()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Unauthorized Customers',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'unauthorized-customers',
            'modals' => [],
            'scripts' => [
                '<script src="{{base_url}}assets/js/softhouse/unauthorizedCustomers.js"></script>',
            ],
        ];
        View::render('Softhouse/dashboard/unauthorized-customers', $data, 'softhouse');
    }

    public function customersForContract()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Customers For Contract',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'customers-for-contract',
            'modals' => [
                'Modals/Softhouse/upload-contract',
            ],
            'scripts' => [
                '<script src="{{base_url}}assets/js/softhouse/customersForContract.js"></script>',
            ],
        ];
        View::render('Softhouse/dashboard/customers-for-contract', $data, 'softhouse');
    }

    public function signedContracts()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Signed Contracts',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'signed-contracts',
            'modals' => [],
            'scripts' => [
                '<script src="{{base_url}}assets/js/softhouse/signedContracts.js"></script>',
            ],
        ];
        View::render('Softhouse/dashboard/signed-contracts', $data, 'softhouse');
    }

    public function customersForRegister()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Resellers',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'customers-for-register',
            'modals' => [],
            'scripts' => [
                '<script src="{{base_url}}assets/js/softhouse/customersForRegister.js"></script>',
            ],
        ];
        View::render('Softhouse/dashboard/customers-for-register', $data, 'softhouse');
    }

    public function activeCustomers()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Active Customers',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'active-customers',
            'modals' => [],
            'scripts' => [
                '<script src="{{base_url}}assets/js/softhouse/activeCustomers.js"></script>',
            ],
        ];
        View::render('Softhouse/dashboard/active-customers', $data, 'softhouse');
    }

    public function resellers()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Resellers',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'resellers',
            'modals' => [
                'Modals/Softhouse/add-reseller',
            ],
            'scripts' => [
                '<script src="{{base_url}}assets/js/softhouse/resellers.js"></script>',
            ],
        ];
        View::render('Softhouse/dashboard/resellers', $data, 'softhouse');
    }
}
