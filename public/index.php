<?php

require_once '../vendor/autoload.php';
require_once '../app/config/config.php';

use App\core\Router;

$_POST = json_decode(file_get_contents('php://input'), true);

$router = new Router();

// Define your routes with grouping

// Group for Pages Routes
$router->group([], function ($router) {
    $router->addRoute('GET', '/', 'AuthController@showLoginForm');

    $router->group(['middleware' => 'auth'], function ($router) {
        $router->addRoute('GET', '/dashboard', 'DashboardController@index');
        $router->addRoute('GET', '/dashboard/members', 'DashboardController@members');
        $router->addRoute('GET', '/dashboard/b2binterest', 'DashboardController@b2binterest');
    });
});

// Group for API Routes
$router->group(['prefix' => '/api'], function ($router) {
    // Auth Routes
    $router->addRoute('GET', '/logout', 'AuthController@logout');
    $router->addRoute('POST', '/login', 'AuthController@login');
    $router->addRoute('POST', '/register', 'AuthController@register');
    $router->addRoute('GET', '/refresh-session', 'AuthController@refreshSession');

    $router->group(['middleware' => 'auth'], function ($router) {
        $router->addRoute('GET', '/dashboard/data', 'DashboardController@loadData');
        $router->addRoute('POST', '/b2binterest', 'api\B2BInterestController@create');
        $router->addRoute('PUT', '/b2binterest/{id}', 'api\B2BInterestController@update');
    });
});

$router->dispatch();
