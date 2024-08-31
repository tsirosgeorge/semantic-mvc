<?php

namespace App\core;

class View
{
    /**
     * Render an HTML view with a layout
     *
     * @param string $view The name of the view file (e.g., 'login' for 'login.html')
     * @param array $data An associative array of data to pass to the layout
     * @param string|null $layout The layout file to use, if any (e.g., 'layouts/dashboard_layout')
     */
    public static function render($view, $data = [], $layout = null)
    {

        $viewPath = __DIR__ . "/../Views/{$view}.html";

        if (file_exists($viewPath)) {
            // Extract data to variables for the layout
            extract($data);

            // Read the HTML content from the view file
            $content = file_get_contents($viewPath);

            // If a layout is provided, include the content in the layout
            if ($layout) {
                $layoutPath = __DIR__ . "/../Views/layouts/{$layout}.php";
                if (file_exists($layoutPath)) {
                    include($layoutPath);
                } else {
                    echo "Layout not found!";
                }
            } else {
                // Output the raw HTML content if no layout is used
                echo $content;
            }
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "404 Not Found";
        }
    }
}
