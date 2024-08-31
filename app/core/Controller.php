<?php

namespace App\core;

class Controller
{
    protected function view($view, $data = [])
    {
        require_once "../app/Views/$view.php";
    }

    protected function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
