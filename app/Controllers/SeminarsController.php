<?php

namespace App\Controllers;

use App\core\Controller;
use App\core\View;
use App\Models\SeminarsModel;

class SeminarsController extends Controller
{
    public function showSeminars()
    {
        $data = [
            'title' => 'Seminars',
            'modals' => [],
            'api_url' => 'http://semantic.local/api/',
            'scripts' => [
                '<script src="http://semantic.local/assets/js/seminars/main.js"></script>',
            ],
        ];
        View::render('Seminars/index', $data, 'seminars');
    }

    public function fetchSeminars()
    {
        $seminarModel = new SeminarsModel();
        $seminars = $seminarModel->fetchSeminars();
        $this->jsonResponse($seminars);
    }
}
