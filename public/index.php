<?php

/*
 * This file is part of the Semantic MVC Framework.
 *
 * (c) George Tsiros <tsirosgeorge@pm.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

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
