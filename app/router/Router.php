<?php

namespace app\router;

// include_once __DIR__ . "/Route.php";
// include_once __DIR__ . "/Response.php";


class Router
{
    private static array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'DELETE' => [],
        'PATCH' => [],
        'OPTIONS' => [],
    ];

    private static function addRoute(string $method, string $url, callable $handle): Route
    {
        $route = new Route($handle);
        self::$routes[$method][$url] = $route;
        return $route;
    }

    public static function __callStatic($name, $arguments)
    {
        $method = strtoupper($name);
        if (in_array($method, array_keys(self::$routes))) {
            return self::addRoute($method, ...$arguments);
        }
        throw new \BadMethodCallException("Method $name does not exist");
    }

    public static function match(array $methods, string $url, callable $handle): Route
    {
        $route = new Route($handle);
        foreach ($methods as $method) {
            if (array_key_exists($method, self::$routes)) {
                self::$routes[$method][$url] = $route;
            }
        }
        return $route;
    }

    public static function any(string $url, callable $handle): Route
    {
        $route = new Route($handle);
        foreach (array_keys(self::$routes) as $method) {
            self::$routes[$method][$url] = $route;
        }
        return $route;
    }

    public static function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        $urlPath = strtok($url, '?');
        $urlPath = strtok($urlPath, '#');

        if (isset(self::$routes[$method][$urlPath])) {
            self::$routes[$method][$urlPath]->execute();
        } else {
            self::$routes['GET']['/404']->execute();
        }
    }
}
