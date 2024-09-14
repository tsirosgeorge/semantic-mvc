<?php

namespace App\Controllers\Api\Softhouse;

use App\core\Controller;

use App\Models\Softhouse\CustomersModel;
use App\core\Auth;


class CustomersController extends Controller
{

    public function __construct()
    {
        Auth::check();
    }

    public function index()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getAllCustomers();
        $this->jsonResponse($customers);
    }

    public function show($id)
    {
        $customerModel = new CustomersModel();
        $customer = $customerModel->getCustomerById($id);
        $this->jsonResponse($customer);
    }

    public function store()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $customerModel = new CustomersModel();
        $customer = $customerModel->createCustomer($_POST);
        $this->jsonResponse($customer);
    }

    public function update($id)
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $customerModel = new CustomersModel();

        $customer = $customerModel->updateCustomer($id, $_POST);
        if ($customer == true) {
            $this->jsonResponse(['success' => true]);
        } else {
            $this->jsonResponse(['success' => false]);
        }
    }

    public function destroy($id)
    {
        $customerModel = new CustomersModel();
        $customer = $customerModel->deleteCustomer($id);
        $this->jsonResponse($customer);
    }

    public function unauthorized()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getUnauthorizedCustomers();
        $this->jsonResponse($customers);
    }

    public function authorize($id)
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $customerModel = new CustomersModel();
        $customer = $customerModel->authorizeCustomer($id);
        $this->jsonResponse($customer);
    }

    public function authorized()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getAuthorizedCustomers();
        $this->jsonResponse($customers);
    }

    public function customersAuthAndSigned()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getCustomersAuthAndSigned();
        $this->jsonResponse($customers);
    }

    public function allButNotAactive()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getAllButNotAactive();
        $this->jsonResponse($customers);
    }

    public function activate($id)
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $customerModel = new CustomersModel();
        $customer = $customerModel->activateCustomer($id);
        $this->jsonResponse($customer);
    }

    public function getActiveCustomers()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getActiveCustomers();
        $this->jsonResponse($customers);
    }

    public function resellers()
    {
        $customerModel = new CustomersModel();
        $customers = $customerModel->getResellers();
        $this->jsonResponse($customers);
    }

    public function setContract($id)
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $customerModel = new CustomersModel();
        $customer = $customerModel->setContract($id, $_POST);
        $this->jsonResponse($customer);
    }
}
