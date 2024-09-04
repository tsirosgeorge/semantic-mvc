<?php

namespace App\Controllers\Reseller;

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
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'dashboard',
            'modals' => [],
            'scripts' => [
                '<script src="{{base_url}}assets/js/scripts/dashboard.js"></script>',
            ],
        ];
        View::render('Reseller/dashboard/dashboard', $data, 'reseller');
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
            'modals' => ['Modals/Reseller/add-customer'],
            'scripts' => [
                '<script src="{{base_url}}assets/js/reseller/unauthorizedCustomers.js"></script>',
            ],
        ];
        View::render('Reseller/dashboard/unauthorized-customers', $data, 'reseller');
    }

    public function authorizedCustomers()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Authorized Customers',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'authorized-customers',
            'modals' => [],
            'scripts' => [
                '<script src="{{base_url}}assets/js/reseller/authorizedCustomers.js"></script>',
            ],
        ];
        View::render('Reseller/dashboard/authorized-customers', $data, 'reseller');
    }

    public function customersWithContract()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Customers with Contract',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'customers-with-contract',
            'modals' => [],
            'scripts' => [
                '<script src="{{base_url}}assets/js/reseller/customersWithContract.js"></script>',
            ],
        ];
        View::render('Reseller/dashboard/customers-with-contract', $data, 'reseller');
    }

    public function readyCustomers()
    {
        Auth::check();
        $data = [
            'email' => $_SESSION['email'],
            'title' => 'Ready Customers',
            'firstTwoLetters' => GeneralUtils::getInitials($_SESSION['email']),
            'base_url' => $_SESSION['BASE_URL'],
            'api_url' => $_SESSION['API_URL'],
            'currentPage' => 'ready-customers',
            'modals' => [],
            'scripts' => [
                '<script src="{{base_url}}assets/js/reseller/readyCustomers.js"></script>',
            ],
        ];
        View::render('Reseller/dashboard/ready-customers', $data, 'reseller');
    }
}
