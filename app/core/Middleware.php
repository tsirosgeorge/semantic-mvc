<?php

namespace App\core;

use App\core\Auth;
use App\core\View;

class Middleware
{
    public function handle($request, $next, $role)
    {
        if (!Auth::hasRole($role)) {
            http_response_code(403);
            View::render('errors/403', [], 'error');
            exit;
        }

        return $next($request);
    }
}
