<?php

namespace App\core;

use FastRoute;

class Router
{
    private $dispatcher;

    public function __construct($routes)
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        });
    }

    public function dispatch()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // 404 Not Found
                http_response_code(404);
                echo '404 Not Found';
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                // 405 Method Not Allowed
                $allowedMethods = $routeInfo[1];
                http_response_code(405);
                echo '405 Method Not Allowed';
                break;
            case FastRoute\Dispatcher::FOUND:
                // Call the handler
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                list($controller, $method) = explode('@', $handler);
                $controller = "App\\Controllers\\" . $controller;
                call_user_func_array([new $controller, $method], $vars);
                break;
        }
    }
}
