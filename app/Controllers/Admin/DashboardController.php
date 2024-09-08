<?php

namespace App\Controllers\Admin;

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
                '<script src="{{base_url}}assets/js/admin/dashboard.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/dashboard', $data, 'admin');
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
                'Modals/Admin/edit-customer',
            ],
            'scripts' => [
                '<script src="{{base_url}}assets/js/admin/allCustomers.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/all-customers', $data, 'admin');
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
                '<script src="{{base_url}}assets/js/admin/unauthorizedCustomers.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/unauthorized-customers', $data, 'admin');
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
                'Modals/Admin/upload-contract',
            ],
            'scripts' => [
                '<script src="{{base_url}}assets/js/admin/customersForContract.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/customers-for-contract', $data, 'admin');
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
                '<script src="{{base_url}}assets/js/admin/signedContracts.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/signed-contracts', $data, 'admin');
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
                '<script src="{{base_url}}assets/js/admin/customersForRegister.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/customers-for-register', $data, 'admin');
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
                '<script src="{{base_url}}assets/js/admin/activeCustomers.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/active-customers', $data, 'admin');
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
                'Modals/Admin/add-reseller',
            ],
            'scripts' => [
                '<script src="{{base_url}}assets/js/admin/resellers.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/resellers', $data, 'admin');
    }

    public function softhouses()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Resellers',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'softhouses',
            'modals' => [
                'Modals/Admin/add-softhouse',
            ],
            'scripts' => [
                '<script src="{{base_url}}assets/js/admin/softhouses.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/softhouses', $data, 'admin');
    }

    public function seminars()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Seminars',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'seminars',
            'modals' => [
                'Modals/Admin/add-seminars',
            ],
            'scripts' => [
                '<script src="{{base_url}}assets/js/admin/seminars.js"></script>',
            ],
        ];
        View::render('Admin/dashboard/seminars', $data, 'admin');
    }
}
