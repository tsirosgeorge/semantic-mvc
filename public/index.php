<?php

require_once '../vendor/autoload.php';
require_once '../app/config/config.php';

use App\core\Router;


$_POST = json_decode(file_get_contents('php://input'), true);

// Define your routes with an optional authentication flag
$routes = [
    // Pages Routes
    ['GET', '/', 'AuthController@showLoginForm'],
    ['GET', '/dashboard', 'DashboardController@index', 'auth'],
    ['GET', '/dashboard/members', 'DashboardController@members', 'auth'],
    ['GET', '/dashboard/b2binterest', 'DashboardController@b2binterest', 'auth'],

    // API Routes

    //Auth Routes
    ['GET', '/api/logout', 'AuthController@logout'],
    ['POST', '/api/login', 'AuthController@login'],
    ['POST', '/api/register', 'AuthController@register'],
    ['GET', '/api/refresh-session', 'AuthController@refreshSession'],


    ['GET', '/api/dashboard/data', 'DashboardController@loadData', 'auth'],
    ['POST', '/api/b2binterest', 'api\B2BInterestController@create', 'auth'],
    ['PUT', '/api/b2binterest/{id}', 'api\B2BInterestController@update', 'auth']
];

$router = new Router($routes);
$router->dispatch();
