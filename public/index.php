<?php

require_once '../vendor/autoload.php';
require_once '../app/config/config.php';

use App\core\Router;

$routes = [
    ['GET', '/', 'LoginController@showLoginForm'],
    ['POST', '/login', 'LoginController@login'],
    ['GET', '/logout', 'LoginController@logout'],
    ['GET', '/dashboard', 'DashboardController@index'],
    ['GET', '/dashboard/data', 'DashboardController@loadData'],
];

$router = new Router($routes);
$router->dispatch();
