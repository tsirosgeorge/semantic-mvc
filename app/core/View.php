<?php

namespace App\core;

class View
{
    /**
     * Render an HTML view with a layout
     *
     * @param string $view The name of the view file (e.g., 'login' for 'login.html')
     * @param array $data An associative array of data to pass to the layout and view
     * @param string|null $layout The layout file to use, if any (e.g., 'layouts/main')
     */
    public static function render($view, $data = [], $layout = null)
    {
        $viewPath = __DIR__ . "/../Views/{$view}.html";
        $content = '';

        if (file_exists($viewPath)) {
            extract($data);
            $content = file_get_contents($viewPath);

            // Replace placeholders in the view content with data
            foreach ($data as $key => $value) {
                $content = str_replace("{{{$key}}}", htmlspecialchars($value), $content);
            }
        }

        if ($layout) {
            $layoutPath = __DIR__ . "/../Views/layouts/{$layout}.php";
            if (file_exists($layoutPath)) {
                ob_start();
                include($layoutPath);
                $layoutContent = ob_get_clean();

                // Replace placeholders in the layout content with data
                foreach ($data as $key => $value) {
                    $layoutContent = str_replace("{{{$key}}}", htmlspecialchars($value), $layoutContent);
                }

                // Replace the {{content}} placeholder in the layout with the view content
                $layoutContent = str_replace('{{content}}', $content, $layoutContent);

                echo $layoutContent;
            } else {
                echo "Layout not found!";
            }
        } else {
            echo $content;
        }
    }
}
