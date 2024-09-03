<?php

namespace App\core;

use App\core\Auth;

class Middleware
{
    public function handle($request, $next, $role)
    {
        if (!Auth::hasRole($role)) {
            http_response_code(403);
            echo '403 Forbidden - You do not have access to this page.';
            exit;
        }

        return $next($request);
    }
}
