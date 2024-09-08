<?php

namespace App\Controllers\Api\Admin;

use App\core\Controller;

use App\Models\Admin\SeminarsModel;
use App\core\Auth;


class SeminarsController extends Controller
{

    public function __construct()
    {
        Auth::check();
    }

    public function fetchSeminars()
    {
        $seminarModel = new SeminarsModel();
        $seminars = $seminarModel->fetchSeminars();
        $this->jsonResponse($seminars);
    }

    public function createSeminar()
    {
        $_POST = json_decode(file_get_contents('php://input'), true);
        $seminarModel = new SeminarsModel();
        $seminarModel->createSeminar($_POST);
        $this->jsonResponse("Seminar created successfully");
    }

    public function deleteSeminar($id)
    {
        $seminarModel = new SeminarsModel();
        $seminarModel->deleteSeminar($id);
        $this->jsonResponse("Seminar updated successfully");
    }
}
