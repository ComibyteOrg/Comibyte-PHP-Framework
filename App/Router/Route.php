<?php

namespace App\Router;

use App\Helper\TwigManager;

class Route
{

    private static $routes = [];

    private static $viewPath = '';

    private static $currentRouteIndex = null;

    private static $middlewareAliases = [];

    // --- Configuration ---

    public static function setViewPath(string $path): void
    {

        self::$viewPath = rtrim($path, '/');

    }

    public static function registerMiddleware(string $alias, callable|string $handler): void
    {

        self::$middlewareAliases[$alias] = $handler;

    }

    public static function getTwig()
    {
        return TwigManager::getInstance();
    }

    // --- Route Definition ---

    private static function addRoute(string $method, string $path, $handler): self
    {

        self::$routes[] = [

            'method' => strtoupper($method),

            'path' => $path,

            'handler' => $handler,

            'middleware' => []

        ];

        self::$currentRouteIndex = count(self::$routes) - 1;

        return new self();

    }

    public static function get(string $path, $handler): self
    {

        return self::addRoute('GET', $path, $handler);

    }

    public static function post(string $path, $handler): self
    {

        return self::addRoute('POST', $path, $handler);

    }

    // --- Middleware Chaining ---

    public function middleware($middleware): self
    {

        if (self::$currentRouteIndex !== null && isset(self::$routes[self::$currentRouteIndex])) {

            if (is_array($middleware)) {

                self::$routes[self::$currentRouteIndex]['middleware'] = array_merge(

                    self::$routes[self::$currentRouteIndex]['middleware'],

                    $middleware

                );

            } else {

                self::$routes[self::$currentRouteIndex]['middleware'][] = $middleware;

            }

        }

        return $this;

    }

    // --- Dispatch ---

    public static function dispatch()
    {

        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';

        $matchedRoute = null;

        $params = [];

        foreach (self::$routes as $route) {

            if ($route['method'] !== $requestMethod && $requestMethod !== 'HEAD')
                continue;

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);

            $pattern = "#^" . $pattern . "$#";

            if (preg_match($pattern, $requestUri, $matches)) {

                foreach ($matches as $key => $value) {

                    if (is_string($key))
                        $params[$key] = $value;

                }

                $matchedRoute = $route;

                break;

            }

        }

        if (!$matchedRoute) {

            self::errorResponse(404, "Page Not Found");

            return;

        }

        $handler = $matchedRoute['handler'];

        $middlewareStack = [];

        foreach ($matchedRoute['middleware'] as $alias) {

            if (isset(self::$middlewareAliases[$alias])) {

                $middlewareStack[] = self::$middlewareAliases[$alias];

            } else {

                error_log("Middleware alias '{$alias}' not registered.");

            }

        }

        $finalHandler = function ($params) use ($handler) {

            if (is_callable($handler)) {

                return call_user_func_array($handler, $params);

            } elseif (is_array($handler) && count($handler) === 2) {

                [$controller, $method] = $handler;

                if (class_exists($controller)) {

                    $instance = new $controller();

                    if (method_exists($instance, $method)) {

                        return call_user_func_array([$instance, $method], $params);

                    } else {

                        self::errorResponse(500, "Method {$method} not found in {$controller}");

                    }

                } else {

                    self::errorResponse(500, "Controller {$controller} not found");

                }

            } else {

                self::errorResponse(500, "Invalid route handler");

            }

        };

        $next = $finalHandler;

        foreach (array_reverse($middlewareStack) as $middlewareHandler) {

            $next = function ($params) use ($middlewareHandler, $next) {

                if (is_string($middlewareHandler) && class_exists($middlewareHandler)) {

                    $middleware = new $middlewareHandler();

                    if (method_exists($middleware, 'handle')) {

                        return $middleware->handle($params, $next);

                    }

                } elseif (is_callable($middlewareHandler)) {

                    return $middlewareHandler($params, $next);

                }

                return $next($params);

            };

        }

        $next($params);

    }

    // --- Error Responses ---

    private static function errorResponse(int $code, string $message): void
    {

        http_response_code($code);

        echo "<div style='font-family: Arial; display: flex; height: 100vh; align-items: center; justify-content: center;'>

                <div><h2>{$code}</h2><p>{$message}</p></div>

              </div>";

    }

    public static function getViewPath(): string
    {

        return self::$viewPath;

    }

}

// --- Global View Helper ---

if (!function_exists('view')) {
    /**
     * Render a view file.
     *
     * @param string $viewName dot-notated view name (e.g. 'admin/login' or '_posts-list')
     * @param array $data variables to extract into the view
     * @param bool $return when true the rendered HTML is returned instead of echoed
     * @return void|string
     */
    function view(string $viewName, array $data = [], bool $return = false)
    {
        // Check if we should use Twig (based on file extension or automatic detection)
        $useTwig = false;
        $templateName = $viewName;

        // If view name already has twig extension, use Twig
        if (strpos($viewName, '.twig') !== false) {
            $useTwig = true;
            $templateName = str_replace('.', '/', $viewName);
        } else {
            // Check if a Twig template exists for this view
            $viewPath = Route::getViewPath();
            $twigTemplate = $viewPath . '/' . str_replace('.', '/', $viewName) . '.twig';

            if (file_exists($twigTemplate)) {
                $useTwig = true;
                $templateName = str_replace('.', '/', $viewName) . '.twig';
            }
        }

        if ($useTwig) {
            // Use Twig template
            try {
                $output = \App\Helper\TwigManager::render($templateName, $data);
                if ($return)
                    return $output;
                echo $output;
                return;
            } catch (\Exception $e) {
                $msg = "<b>Error:</b> Twig template error: " . $e->getMessage();
                if ($return)
                    return $msg;
                echo $msg;
                return;
            }
        } else {
            // Use traditional PHP templates
            $viewPath = Route::getViewPath();

            if (empty($viewPath)) {
                $msg = "<b>Error:</b> View path not configured.";
                if ($return)
                    return $msg;
                echo $msg;
                return;
            }

            $file = $viewPath . '/' . str_replace('.', '/', $viewName) . '.php';

            if (file_exists($file)) {
                extract($data);
                ob_start();
                require $file;
                $output = ob_get_clean();
                if ($return)
                    return $output;
                echo $output;
                return;
            } else {
                $msg = "<b>Error:</b> View '{$viewName}' not found at {$file}.";
                if ($return)
                    return $msg;
                echo $msg;
            }
        }
    }
}
