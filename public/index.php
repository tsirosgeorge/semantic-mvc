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
        $router->addRoute('GET', '/softhouses', 'Admin\DashboardController@softhouses');

        $router->addRoute('GET', '/users', 'Admin\UserController@index');
    });

    // Softhouse routes
    $router->group(['prefix' => '/softhouse', 'role' => 'softhouse'], function ($router) {
        // $router->addRoute('GET', '/dashboard', 'Admin\DashboardController@index');
        $router->addRoute('GET', '/dashboard', 'Softhouse\DashboardController@index', 'App\core\Middleware:admin');

        $router->addRoute('GET', '/all-customers', 'Softhouse\DashboardController@allCustomers');
        $router->addRoute('GET', '/unauthorized-customers', 'Softhouse\DashboardController@unauthorizedCustomers');
        $router->addRoute('GET', '/customers-for-contract', 'Softhouse\DashboardController@customersForContract');
        $router->addRoute('GET', '/signed-contracts', 'Softhouse\DashboardController@signedContracts');
        $router->addRoute('GET', '/customers-for-register', 'Softhouse\DashboardController@customersForRegister');
        $router->addRoute('GET', '/active-customers', 'Softhouse\DashboardController@activeCustomers');
        $router->addRoute('GET', '/resellers', 'Softhouse\DashboardController@resellers');

        $router->addRoute('GET', '/users', 'Softhouse\UserController@index');
    });

    // Reseller routes
    $router->group(['prefix' => '/reseller', 'role' => 'reseller'], function ($router) {
        $router->addRoute('GET', '/dashboard', 'Reseller\DashboardController@index', 'App\core\Middleware:reseller');
        $router->addRoute('GET', '/unauthorized-customers', 'Reseller\DashboardController@unauthorizedCustomers', 'App\core\Middleware:reseller');
        $router->addRoute('GET', '/authorized-customers', 'Reseller\DashboardController@authorizedCustomers', 'App\core\Middleware:reseller');
        $router->addRoute('GET', '/customers-with-contract', 'Reseller\DashboardController@customersWithContract', 'App\core\Middleware:reseller');
        $router->addRoute('GET', '/ready-customers', 'Reseller\DashboardController@readyCustomers', 'App\core\Middleware:reseller');
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


    // Admin Routes
    $router->group(['prefix' => '/admin'], function ($router) {
        $router->addRoute('GET', '/', 'Api\Admin\CustomersController@index');
        $router->addRoute('GET', '/dashboard', 'Api\Admin\DashboardController@dashboard');
        $router->addRoute('GET', '/{id:\d+}', 'Api\Admin\CustomersController@show');
        $router->addRoute('GET', '/authorized', 'Api\Admin\CustomersController@authorized');
        $router->addRoute('GET', '/unauthorized', 'Api\Admin\CustomersController@unauthorized');
        $router->addRoute('GET', '/customersAuthAndSigned', 'Api\Admin\CustomersController@customersAuthAndSigned');
        $router->addRoute('GET', '/allButNotAactive', 'Api\Admin\CustomersController@allButNotAactive');
        $router->addRoute('GET', '/active', 'Api\Admin\CustomersController@getActiveCustomers');


        // $router->addRoute('POST', '/', 'Api\Admin\CustomersController@store');
        $router->addRoute('PUT', '/{id:\d+}', 'Api\Admin\CustomersController@update');
        $router->addRoute('PUT', '/activate/{id:\d+}', 'Api\Admin\CustomersController@activate');
        $router->addRoute('PUT', '/authorize/{id:\d+}', 'Api\Admin\CustomersController@authorize');
        $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Admin\CustomersController@destroy');

        $router->group(['prefix' => '/resellers'], function ($router) {
            $router->addRoute('GET', '/', 'Api\Admin\ResellersController@index');
            $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Admin\ResellersController@destroy');
            $router->addRoute('POST', '/store', 'Api\Admin\ResellersController@store');
        });

        $router->group(['prefix' => '/softhouses'], function ($router) {
            $router->addRoute('GET', '/', 'Api\Admin\SofthousesController@index');
            $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Admin\SofthousesController@destroy');
            $router->addRoute('POST', '/store', 'Api\Admin\SofthousesController@store');
        });
    });

    //Softhouse API Routes
    $router->group(['prefix' => '/softhouse'], function ($router) {
        $router->addRoute('GET', '/', 'Api\Admin\CustomersController@index');
        $router->addRoute('GET', '/{id:\d+}', 'Api\Admin\CustomersController@show');
        $router->addRoute('GET', '/authorized', 'Api\Admin\CustomersController@authorized');
        $router->addRoute('GET', '/unauthorized', 'Api\Admin\CustomersController@unauthorized');
        $router->addRoute('GET', '/customersAuthAndSigned', 'Api\Admin\CustomersController@customersAuthAndSigned');
        $router->addRoute('GET', '/allButNotAactive', 'Api\Admin\CustomersController@allButNotAactive');
        $router->addRoute('GET', '/active', 'Api\Admin\CustomersController@getActiveCustomers');


        // $router->addRoute('POST', '/', 'Api\Admin\CustomersController@store');
        $router->addRoute('PUT', '/{id:\d+}', 'Api\Admin\CustomersController@update');
        $router->addRoute('PUT', '/activate/{id:\d+}', 'Api\Admin\CustomersController@activate');
        $router->addRoute('PUT', '/authorize/{id:\d+}', 'Api\Admin\CustomersController@authorize');
        $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Admin\CustomersController@destroy');

        $router->group(['prefix' => '/resellers'], function ($router) {
            $router->addRoute('GET', '/', 'Api\Admin\ResellersController@index');
            $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Admin\ResellersController@destroy');
            $router->addRoute('POST', '/store', 'Api\Admin\ResellersController@store');
        });
    });



    //Reseller API Routes
    $router->group(['prefix' => '/reseller'], function ($router) {
        $router->addRoute('GET', '/unauthorized', 'Api\Reseller\CustomersController@unauthorized');
        $router->addRoute('GET', '/authorized', 'Api\Reseller\CustomersController@authorized');
        $router->addRoute('GET', '/customerWithContract', 'Api\Reseller\CustomersController@customerWithContract');
        $router->addRoute('GET', '/readyCustomers', 'Api\Reseller\CustomersController@readyCustomers');
    });
});



$router->group(['prefix' => '/check'], function ($router) {
    $router->addRoute('GET', '/test', 'CheckController@test');
});

$router->dispatch();
