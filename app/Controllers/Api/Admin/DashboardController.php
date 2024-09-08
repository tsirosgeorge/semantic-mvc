<?php

namespace App\Controllers\Api\Admin;

use App\core\Controller;

use App\Models\Admin\DashboardModel;
use App\core\Auth;


class DashboardController extends Controller
{

    public function __construct()
    {
        Auth::check();
    }

    public function dashboard()
    {
        $dashboardModel = new DashboardModel();
        $totals = $dashboardModel->getTotals();
        $this->jsonResponse($totals);
    }
}
