<?php

namespace App\Controllers\Api\Admin;

use App\core\Controller;

use App\Models\Admin\ResellersModel;
use App\core\Auth;


class ResellersController extends Controller
{

    public function __construct()
    {
        Auth::check();
    }

    public function index()
    {
        $resellerModel = new ResellersModel();
        $resellers = $resellerModel->getAllResellers();
        $this->jsonResponse($resellers);
    }


    public function destroy($id)
    {
        $resellerModel = new ResellersModel();
        $reseller = $resellerModel->deleteReseller($id);
        $this->jsonResponse($reseller);
    }

    public function store()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);

        $resellerModel = new ResellersModel();
        $reseller = $resellerModel->storeReseller($_POST);
        $this->jsonResponse($reseller);
    }
}
