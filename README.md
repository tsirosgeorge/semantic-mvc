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

## License

This project is licensed under a custom license agreement. The full terms of the license can be found in the [LICENSE](LICENSE) file.

## Changelog

All notable changes to this project will be documented in the [CHANGELOG.md](CHANGELOG.md) file.
