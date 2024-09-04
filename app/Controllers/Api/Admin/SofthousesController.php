<?php

namespace App\Controllers\Api\Admin;

use App\core\Controller;

use App\Models\Admin\SofthousesModel;
use App\core\Auth;


class SofthousesController extends Controller
{

    public function __construct()
    {
        Auth::check();
    }

    public function index()
    {
        $resellerModel = new SofthousesModel();
        $resellers = $resellerModel->getAllSofthouses();
        $this->jsonResponse($resellers);
    }


    public function destroy($id)
    {
        $resellerModel = new SofthousesModel();
        $reseller = $resellerModel->deleteSofthouse($id);
        $this->jsonResponse($reseller);
    }

    public function store()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);

        $resellerModel = new SofthousesModel();
        $reseller = $resellerModel->storeSofthouse($_POST);
        $this->jsonResponse($reseller);
    }
}
