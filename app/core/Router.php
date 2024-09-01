<?php

namespace App\core;

use FastRoute;
use App\core\Auth;

class Router
{
    private $routes = [];
    private $authRoutes = [];
    private $guestRoutes = ['/']; // Routes that should not be accessed by logged-in users
    private $currentGroupPrefix = '';
    private $currentGroupMiddleware = [];

    public function group(array $attributes, callable $callback)
    {
        // Backup the current group state
        $previousGroupPrefix = $this->currentGroupPrefix;
        $previousGroupMiddleware = $this->currentGroupMiddleware;

        // Update the current group state with the new attributes
        if (isset($attributes['prefix'])) {
            $this->currentGroupPrefix .= $attributes['prefix'];
        }

        if (isset($attributes['middleware'])) {
            $this->currentGroupMiddleware[] = $attributes['middleware'];
        }

        // Call the callback to register routes within this group
        $callback($this);

        // Restore the previous group state
        $this->currentGroupPrefix = $previousGroupPrefix;
        $this->currentGroupMiddleware = $previousGroupMiddleware;
    }

    public function addRoute($method, $route, $handler, $middleware = null)
    {
        // Apply the current group's prefix and middleware to the route
        $route = $this->currentGroupPrefix . $route;

        // Register the route with the internal routes array
        $this->routes[] = [$method, $route, $handler];

        // Check if the route requires authentication
        if (in_array('auth', $this->currentGroupMiddleware) || $middleware === 'auth') {
            $this->authRoutes[] = $route;
        }
    }

    public function dispatch()
    {
        // Initialize the dispatcher with the routes
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Remove the query string if present
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        // Check if the user is logged in
        $isLoggedIn = Auth::checkIfLoggedIn();

        // Redirect if the user is logged in and trying to access guest routes
        if ($isLoggedIn && in_array($uri, $this->guestRoutes)) {
            header('Location: ' . getenv('REDIRECT_AFTER_LOGIN')); // Use getenv for environment variables
            exit;
        }

        // Dispatch the route
        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                http_response_code(404);
                echo '404 Not Found';
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                http_response_code(405);
                echo '405 Method Not Allowed';
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                // Check if the route requires authentication
                if (in_array($uri, $this->authRoutes)) {
                    Auth::check(); // Ensure authentication is enforced
                }

                list($controller, $method) = explode('@', $handler);
                $controller = "App\\Controllers\\" . $controller;
                call_user_func_array([new $controller, $method], $vars);
                break;
        }
    }
}
