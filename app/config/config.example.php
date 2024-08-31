<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'semantic');
define('DB_PASS', 'j3rzy@99');
define('DB_NAME', 'semantic');
define('DB_PORT', '3307');


// Session Configuration
define('SESSION_TIMEOUT', 1800); // Session timeout in seconds (30 minutes)
define('SESSION_COOKIE_LIFETIME', 1800); // Session cookie lifetime
define('SESSION_COOKIE_SECURE', 0); // 1 to enable secure cookies
define('SESSION_COOKIE_HTTPONLY', 0); // 1 to enable HTTP-only cookies
define('SESSION_COOKIE_SAMESITE', ''); // SameSite attribute Strict, Lax, None


// Set default timezone
date_default_timezone_set('Europe/Athens');
