<?php

namespace App\Controllers\Api\Reseller;

use App\core\Controller;
use App\Models\Reseller\FileModel;
use App\core\Auth;

class FileController extends Controller
{
    public function __construct()
    {
        Auth::check();
    }

    public function store($id)
    {
        // $id is directly retrieved from the route (customer ID)
        if (!$id) {
            $this->jsonResponse(['success' => false, 'message' => 'Customer ID not provided', 'status_code' => 400]);
            return;
        }

        $fileModel = new FileModel();
        // Pass the customerId and file data from $_FILES
        $file = $fileModel->uploadContract($id, $_FILES);
        $this->jsonResponse($file);
    }
    public function storePDF()
    {
        $_POST = json_decode(file_get_contents("php://input"), true);
        $fileModel = new FileModel();
        $pdf = $fileModel->savePDF($_POST);
        $this->jsonResponse($pdf);
    }
}
