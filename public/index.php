<?php

require_once '../vendor/autoload.php';
require_once '../app/config/config.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

use App\core\Middleware;
use App\core\Router;
use Tracy\Debugger;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();


Debugger::enable();
debugger::$showBar = false;
Debugger::$strictMode = true;



$_POST = json_decode(file_get_contents('php://input'), true);

$router = new Router();


// Define your routes with grouping

// Group for Pages Routes
$router->addRoute('GET', '/', 'AuthController@showLoginForm');
$router->group(['middleware' => 'auth'], function ($router) {
    // Admin routes
    $router->group(['prefix' => '/admin', 'role' => 'admin'], function ($router) {
        // $router->addRoute('GET', '/dashboard', 'Admin\DashboardController@index');
        $router->addRoute('GET', '/dashboard', 'Admin\DashboardController@index', 'App\core\Middleware:admin');

        $router->addRoute('GET', '/all-customers', 'Admin\DashboardController@allCustomers');
        $router->addRoute('GET', '/unauthorized-customers', 'Admin\DashboardController@unauthorizedCustomers');
        $router->addRoute('GET', '/customers-for-contract', 'Admin\DashboardController@customersForContract');
        $router->addRoute('GET', '/signed-contracts', 'Admin\DashboardController@signedContracts');
        $router->addRoute('GET', '/customers-for-register', 'Admin\DashboardController@customersForRegister');
        $router->addRoute('GET', '/active-customers', 'Admin\DashboardController@activeCustomers');
        $router->addRoute('GET', '/resellers', 'Admin\DashboardController@resellers');

        $router->addRoute('GET', '/users', 'Admin\UserController@index');
    });

    // Editor routes
    $router->group(['prefix' => '/softhouse', 'middleware' => 'role:softhouse'], function ($router) {
        $router->addRoute('GET', '/content', 'Editor\ContentController@index');
    });

    // Viewer routes
    $router->group(['prefix' => '/viewer', 'role' => 'viewer'], function ($router) {
        $router->addRoute('GET', '/view', 'Viewer\ViewController@index', 'App\core\Middleware:admin');
    });
});


// Group for API Routes
$router->group(['prefix' => '/api'], function ($router) {
    // Auth Routes
    $router->addRoute('GET', '/logout', 'AuthController@logout');
    $router->addRoute('POST', '/login', 'AuthController@login');
    $router->addRoute('POST', '/register', 'AuthController@register');
    $router->addRoute('GET', '/refresh-session', 'AuthController@refreshSession');


    // Customers Routes
    $router->group(['prefix' => '/customers'], function ($router) {
        $router->addRoute('GET', '/', 'Api\CustomersController@index');
        $router->addRoute('GET', '/{id:\d+}', 'Api\CustomersController@show');
        $router->addRoute('GET', '/authorized', 'Api\CustomersController@authorized');
        $router->addRoute('GET', '/unauthorized', 'Api\CustomersController@unauthorized');
        $router->addRoute('GET', '/customersAuthAndSigned', 'Api\CustomersController@customersAuthAndSigned');
        $router->addRoute('GET', '/allButNotAactive', 'Api\CustomersController@allButNotAactive');
        $router->addRoute('GET', '/active', 'Api\CustomersController@getActiveCustomers');


        // $router->addRoute('POST', '/', 'Api\CustomersController@store');
        $router->addRoute('PUT', '/{id:\d+}', 'Api\CustomersController@update');
        $router->addRoute('PUT', '/activate/{id:\d+}', 'Api\CustomersController@activate');
        $router->addRoute('PUT', '/authorize/{id:\d+}', 'Api\CustomersController@authorize');
        $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\CustomersController@destroy');
    });

    $router->group(['prefix' => '/resellers'], function ($router) {
        $router->addRoute('GET', '/', 'Api\ResellersController@index');
        $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\ResellersController@destroy');
        $router->addRoute('POST', '/store', 'Api\ResellersController@store');
    });
});


$router->group(['prefix' => '/check'], function ($router) {
    $router->addRoute('GET', '/test', 'CheckController@test');
});

$router->dispatch();
