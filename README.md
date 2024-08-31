# Semantic MVC Framework

Welcome to the Semantic MVC Framework! This project is a custom MVC framework using FastRoute for routing and is intended for internal use. It includes features like a `View` class for rendering HTML views and basic authentication.

## Installation

To install the Semantic MVC Framework, use Composer:

```bash
composer require tsirosgeorge/semantic
```

## How to Use

1. **Configuration**

   After installation, rename the file `app/config/config.example.php` to `app/config/config.php` and add your configuration settings.

2. **Rendering Views**

   To render data in your view pages, use the `View::render` method. For example:

   ```php
   View::render('login', $data, 'auth');
   ```

- The first parameter is the view page name.
- The second parameter is the data to pass to the view.
- The third parameter is the layout to use.

Inside your view files, you can use placeholders like `{{your_value}}`. These placeholders will be replaced with the corresponding values from the data array you provide. Ensure that the placeholders match the keys in your data array.

## Routing

When defining routes, use the following conventions:

- **API Routes**: Prefix API routes with `api/`.
- **Page Routes**: Do not use a prefix for regular page routes.

### Example

Here is an example of how routes are defined in `public/index.php`:

To add protected routes, simply include `'auth'` as the last parameter in the route definition.

```php
$routes = [
    // Pages Routes
    ['GET', '/', 'AuthController@showLoginForm'],
    ['GET', '/dashboard', 'DashboardController@index', 'auth'],

    // API Routes
    ['GET', '/api/logout', 'AuthController@logout'],
    ['POST', '/api/login', 'AuthController@login'],
    ['POST', '/api/register', 'AuthController@register'],
    ['GET', '/api/refresh-session', 'AuthController@refreshSession'],
    ['GET', '/api/dashboard/data', 'DashboardController@loadData', 'auth'],
];

In this example:

- Routes without the `api/` prefix are used for regular page requests.
- Routes with the `api/` prefix are designated for API requests.
- To protect a route, add `'auth'` as the third parameter. This indicates that the route requires authentication.

```

## License

This project is licensed under a custom license agreement. The full terms of the license can be found in the [LICENSE](LICENSE) file.

## Changelog

All notable changes to this project will be documented in the [CHANGELOG.md](CHANGELOG.md) file.
