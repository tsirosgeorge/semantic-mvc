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

$router->group(['prefix' => '/seminars'], function ($router) {
    $router->addRoute('GET', '/', 'SeminarsController@showSeminars');
    $router->addRoute('GET', '/fetchSeminars', 'SeminarsController@fetchSeminars');
});


$router->group(['middleware' => 'auth'], function ($router) {
    // Admin routes
    $router->group(['prefix' => '/admin', 'role' => 'admin'], function ($router) {
        // $router->addRoute('GET', '/dashboard', 'Admin\DashboardController@index');
        $router->addRoute('GET', '/dashboard', 'Admin\DashboardController@index', 'App\core\Middleware:admin');

        $router->addRoute('GET', '/all-customers', 'Admin\DashboardController@allCustomers', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/unauthorized-customers', 'Admin\DashboardController@unauthorizedCustomers', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/customers-for-contract', 'Admin\DashboardController@customersForContract', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/signed-contracts', 'Admin\DashboardController@signedContracts', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/customers-for-register', 'Admin\DashboardController@customersForRegister', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/active-customers', 'Admin\DashboardController@activeCustomers', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/resellers', 'Admin\DashboardController@resellers', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/softhouses', 'Admin\DashboardController@softhouses', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/seminars', 'Admin\DashboardController@seminars', 'App\core\Middleware:admin');

        $router->addRoute('GET', '/users', 'Admin\UserController@index', 'App\core\Middleware:admin');
    });

    // Softhouse routes
    $router->group(['prefix' => '/softhouse', 'role' => 'softhouse'], function ($router) {
        // $router->addRoute('GET', '/dashboard', 'Admin\DashboardController@index');
        $router->addRoute('GET', '/dashboard', 'Softhouse\DashboardController@index', 'App\core\Middleware:softhouse');

        $router->addRoute('GET', '/all-customers', 'Softhouse\DashboardController@allCustomers', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/unauthorized-customers', 'Softhouse\DashboardController@unauthorizedCustomers', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/customers-for-contract', 'Softhouse\DashboardController@customersForContract', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/signed-contracts', 'Softhouse\DashboardController@signedContracts', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/customers-for-register', 'Softhouse\DashboardController@customersForRegister', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/active-customers', 'Softhouse\DashboardController@activeCustomers', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/resellers', 'Softhouse\DashboardController@resellers', 'App\core\Middleware:softhouse');

        $router->addRoute('GET', '/users', 'Softhouse\UserController@index', 'App\core\Middleware:softhouse');
    });

    // Reseller routes
    $router->group(['prefix' => '/reseller', 'role' => 'reseller'], function ($router) {
        $router->addRoute('GET', '/dashboard', 'Reseller\DashboardController@unauthorizedCustomers', 'App\core\Middleware:reseller');
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
        $router->addRoute('GET', '/', 'Api\Admin\CustomersController@index', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/dashboard', 'Api\Admin\DashboardController@dashboard', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/{id:\d+}', 'Api\Admin\CustomersController@show', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/authorized', 'Api\Admin\CustomersController@authorized', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/unauthorized', 'Api\Admin\CustomersController@unauthorized', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/customersAuthAndSigned', 'Api\Admin\CustomersController@customersAuthAndSigned', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/allButNotAactive', 'Api\Admin\CustomersController@allButNotAactive', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/active', 'Api\Admin\CustomersController@getActiveCustomers', 'App\core\Middleware:admin');
        $router->addRoute('GET', '/fetchSeminars', 'Api\Admin\SeminarsController@fetchSeminars', 'App\core\Middleware:admin');

        $router->addRoute('DELETE', '/deleteSeminar/{id:\d+}', 'Api\Admin\SeminarsController@deleteSeminar', 'App\core\Middleware:admin');

        $router->addRoute('POST', '/createSeminar', 'Api\Admin\SeminarsController@createSeminar', 'App\core\Middleware:admin');

        $router->addRoute('POST', '/fileUpload/{id:\d+}', 'Api\Admin\FileController@store', 'App\core\Middleware:admin');
        $router->addRoute('POST', '/savePdf', 'Api\Admin\FileController@storePDF', 'App\core\Middleware:admin');

        $router->addRoute('POST', '/setContract/{id:\d+}', 'Api\Admin\CustomersController@setContract', 'App\core\Middleware:admin');
        $router->addRoute('POST', '/registerEskap', 'Api\Admin\CustomersController@registerEskap', 'App\core\Middleware:admin');



        // $router->addRoute('POST', '/', 'Api\Admin\CustomersController@store', 'App\core\Middleware:admin');
        $router->addRoute('PUT', '/{id:\d+}', 'Api\Admin\CustomersController@update', 'App\core\Middleware:admin');
        $router->addRoute('PUT', '/activate/{id:\d+}', 'Api\Admin\CustomersController@activate', 'App\core\Middleware:admin');
        $router->addRoute('PUT', '/authorize/{id:\d+}', 'Api\Admin\CustomersController@authorize', 'App\core\Middleware:admin');
        $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Admin\CustomersController@destroy', 'App\core\Middleware:admin');

        $router->group(['prefix' => '/resellers'], function ($router) {
            $router->addRoute('GET', '/', 'Api\Admin\ResellersController@index', 'App\core\Middleware:admin');
            $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Admin\ResellersController@destroy', 'App\core\Middleware:admin');
            $router->addRoute('POST', '/store', 'Api\Admin\ResellersController@store', 'App\core\Middleware:admin');
        });

        $router->group(['prefix' => '/softhouses'], function ($router) {
            $router->addRoute('GET', '/', 'Api\Admin\SofthousesController@index', 'App\core\Middleware:admin');
            $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Admin\SofthousesController@destroy', 'App\core\Middleware:admin');
            $router->addRoute('POST', '/store', 'Api\Admin\SofthousesController@store', 'App\core\Middleware:admin');
        });
    });

    //Softhouse API Routes
    $router->group(['prefix' => '/softhouse'], function ($router) {
        $router->addRoute('GET', '/', 'Api\Softhouse\CustomersController@index', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/{id:\d+}', 'Api\Softhouse\CustomersController@show', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/authorized', 'Api\Softhouse\CustomersController@authorized', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/unauthorized', 'Api\Softhouse\CustomersController@unauthorized', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/customersAuthAndSigned', 'Api\Softhouse\CustomersController@customersAuthAndSigned', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/allButNotAactive', 'Api\Softhouse\CustomersController@allButNotAactive', 'App\core\Middleware:softhouse');
        $router->addRoute('GET', '/active', 'Api\Softhouse\CustomersController@getActiveCustomers', 'App\core\Middleware:softhouse');
        $router->addRoute('POST', '/setContract/{id:\d+}', 'Api\Softhouse\CustomersController@setContract', 'App\core\Middleware:softhouse');


        // $router->addRoute('POST', '/', 'Api\Softhouse\CustomersController@store', 'App\core\Middleware:softhouse');
        $router->addRoute('PUT', '/{id:\d+}', 'Api\Softhouse\CustomersController@update', 'App\core\Middleware:softhouse');
        $router->addRoute('PUT', '/activate/{id:\d+}', 'Api\Softhouse\CustomersController@activate', 'App\core\Middleware:softhouse');
        $router->addRoute('PUT', '/authorize/{id:\d+}', 'Api\Softhouse\CustomersController@authorize', 'App\core\Middleware:softhouse');
        $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Softhouse\CustomersController@destroy', 'App\core\Middleware:softhouse');

        $router->group(['prefix' => '/resellers'], function ($router) {
            $router->addRoute('GET', '/', 'Api\Softhouse\ResellersController@index', 'App\core\Middleware:softhouse');
            $router->addRoute('DELETE', '/delete/{id:\d+}', 'Api\Softhouse\ResellersController@destroy', 'App\core\Middleware:softhouse');
            $router->addRoute('POST', '/store', 'Api\Softhouse\ResellersController@store', 'App\core\Middleware:softhouse');
        });
    });



    //Reseller API Routes
    $router->group(['prefix' => '/reseller'], function ($router) {
        $router->addRoute('GET', '/unauthorized', 'Api\Reseller\CustomersController@unauthorized', 'App\core\Middleware:reseller');
        $router->addRoute('GET', '/authorized', 'Api\Reseller\CustomersController@authorized', 'App\core\Middleware:reseller');
        $router->addRoute('GET', '/customerWithContract', 'Api\Reseller\CustomersController@customerWithContract', 'App\core\Middleware:reseller');
        $router->addRoute('GET', '/readyCustomers', 'Api\Reseller\CustomersController@readyCustomers', 'App\core\Middleware:reseller');

        $router->addRoute('POST', '/createCustomer', 'Api\Reseller\CustomersController@createCustomer', 'App\core\Middleware:reseller');
        $router->addRoute('DELETE', '/deleteCustomer/{id:\d+}', 'Api\Reseller\CustomersController@deleteCustomer', 'App\core\Middleware:reseller');

        $router->addRoute('POST', '/fileUpload/{id:\d+}', 'Api\Reseller\FileController@store', 'App\core\Middleware:reseller');

        $router->addRoute('PUT', '/setSigned/{id:\d+}', 'Api\Reseller\CustomersController@setSigned', 'App\core\Middleware:reseller');

        $router->addRoute('POST', '/registerEskap', 'Api\Reseller\CustomersController@registerEskap', 'App\core\Middleware:reseller');
    });
});



$router->group(['prefix' => '/check'], function ($router) {
    $router->addRoute('GET', '/test', 'CheckController@test');
});

$router->dispatch();
