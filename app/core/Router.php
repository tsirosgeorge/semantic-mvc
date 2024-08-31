<?php

namespace App\core;

use FastRoute;
use App\core\Auth;

class Router
{
    private $dispatcher;
    private $authRoutes = [];
    private $guestRoutes = ['/']; // Routes that should not be accessed by logged-in users

    public function __construct($routes)
    {
        $this->dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) use ($routes) {
            foreach ($routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
                // Assuming authentication required routes are identified by a specific pattern or flag
                if (isset($route[3]) && $route[3] === 'auth') {
                    $this->authRoutes[] = $route[1];
                }
            }
        });
    }

    public function dispatch()
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Remove query string
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        // Check if the user is logged in
        $isLoggedIn = Auth::checkIfLoggedIn(); // Add this method to check if the user is logged in

        // Redirect if the user is logged in and trying to access guest routes
        if ($isLoggedIn && in_array($uri, $this->guestRoutes)) {
            header('Location: ' . REDIRECT_AFTER_LOGIN);
            exit;
        }

        // Dispatch the route
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

                // Check if the route requires authentication
                if (in_array($uri, $this->authRoutes)) {
                    Auth::check();
                }

                list($controller, $method) = explode('@', $handler);
                $controller = "App\\Controllers\\" . $controller;
                call_user_func_array([new $controller, $method], $vars);
                break;
        }
    }
}
