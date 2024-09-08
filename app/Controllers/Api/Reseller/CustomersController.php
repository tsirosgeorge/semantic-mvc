<?php

namespace App\Controllers\Api\Reseller;

use App\core\Controller;

use App\Models\Reseller\CustomersModel;
use App\core\Auth;


class CustomersController extends Controller
{

    public function __construct()
    {
        Auth::check();
    }


    public function unauthorized()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getUnauthorizedCustomers();
        $this->jsonResponse($customers);
    }
    public function authorized()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getAuthorizedCustomers();
        $this->jsonResponse($customers);
    }

    public function customerWithContract()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getCustomersWithContract();
        $this->jsonResponse($customers);
    }

    public function readyCustomers()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getReadyCustomers();
        $this->jsonResponse($customers);
    }

    public function createCustomer()
    {
        $customerModel = new CustomersModel();
        $data = json_decode(file_get_contents('php://input'), true);
        $customer = $customerModel->createCustomer($data);
        $this->jsonResponse($customer);
    }

    public function deleteCustomer($id)
    {
        $customerModel = new CustomersModel();
        $data = json_decode(file_get_contents('php://input'), true);
        $customer = $customerModel->deleteCustomer($id);
        $this->jsonResponse($customer);
    }

    public function setSigned($id)
    {
        $customerModel = new CustomersModel();
        $customer = $customerModel->setSigned($id);
        $this->jsonResponse($customer);
    }

    public function registerEskap()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $customerModel = new CustomersModel();
        $customer = $customerModel->registerEskap($_POST);
        $this->jsonResponse($customer);
    }
}
