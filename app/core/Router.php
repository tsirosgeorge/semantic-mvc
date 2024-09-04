<?php

namespace App\core;

use FastRoute;
use App\core\Auth;
use App\core\View;

class Router
{
    private $routes = [];
    private $authRoutes = [];
    private $roleRoutes = [];
    private $currentGroupPrefix = '';
    private $currentGroupMiddleware = [];
    private $permissionRoutes = [];

    private $middlewareRoutes = []; // Add this property



    public function group(array $attributes, callable $callback)
    {
        $previousGroupPrefix = $this->currentGroupPrefix;
        $previousGroupMiddleware = $this->currentGroupMiddleware;

        if (isset($attributes['prefix'])) {
            $this->currentGroupPrefix .= $attributes['prefix'];
        }

        if (isset($attributes['middleware'])) {
            $this->currentGroupMiddleware[] = $attributes['middleware'];
        }

        if (isset($attributes['role'])) {
            $this->currentGroupMiddleware[] = 'role:' . $attributes['role'];
        }

        $callback($this);

        $this->currentGroupPrefix = $previousGroupPrefix;
        $this->currentGroupMiddleware = $previousGroupMiddleware;
    }
    public function addRoute($method, $route, $handler, $middleware = null, $permission = null)
    {
        $route = $this->currentGroupPrefix . $route;
        $this->routes[] = [$method, $route, $handler];

        if (in_array('auth', $this->currentGroupMiddleware) || $middleware === 'auth') {
            $this->authRoutes[] = $route;
        }

        if ($middleware) {
            $this->middlewareRoutes[$route] = $middleware; // Store middleware
        }

        if ($permission) {
            $this->permissionRoutes[$route] = $permission;
        }
    }
    public function dispatch()
    {
        $dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route[0], $route[1], $route[2]);
            }
        });

        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $isLoggedIn = Auth::checkIfLoggedIn();
        $userRoles = $isLoggedIn ? Auth::userRoles() : [];

        $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                http_response_code(404);
                View::render('errors/404', [], 'error');
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                http_response_code(405);
                View::render('errors/405', [], 'error');
                break;
            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                // Check for authentication
                if (in_array($uri, $this->authRoutes) && !$isLoggedIn) {
                    header('Location: /');
                    exit;
                }

                // Apply middleware
                if (isset($this->middlewareRoutes[$uri])) {
                    $middleware = $this->middlewareRoutes[$uri];
                    list($middlewareClass, $role) = explode(':', $middleware);
                    $middlewareInstance = new $middlewareClass();

                    // Define a closure to be used as a "next" function
                    $next = function () use ($handler, $vars) {
                        list($controller, $method) = explode('@', $handler);
                        $controller = "App\\Controllers\\" . $controller;
                        call_user_func_array([new $controller, $method], $vars);
                    };

                    // Execute middleware
                    $middlewareInstance->handle([], $next, $role);
                } else {
                    // Call the handler if no middleware is applied
                    list($controller, $method) = explode('@', $handler);
                    $controller = "App\\Controllers\\" . $controller;
                    call_user_func_array([new $controller, $method], $vars);
                }
                break;
        }
    }
    private function redirectBasedOnRole($role)
    {
        $redirectUrl = $_ENV['REDIRECT_AFTER_LOGIN']; // Default redirect URL

        // Customize redirects based on user roles        
        switch ($role) {
            case 'admin':
                $redirectUrl = '/admin' . $_ENV['REDIRECT_AFTER_LOGIN'];
                break;
            case 'softhouse':
                $redirectUrl = '/softhouse' . $_ENV['REDIRECT_AFTER_LOGIN'];
                break;
            case 'viewer':
                $redirectUrl = '/viewer' . $_ENV['REDIRECT_AFTER_LOGIN'];
                break;
            default:
                $redirectUrl = '/';
                break;
        }

        header('Location: ' . $redirectUrl);
        exit;
    }
}
