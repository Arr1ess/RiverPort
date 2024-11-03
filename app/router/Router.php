<?php

namespace app\router;

use app\lib\cArray;

class Router
{
    private static ?cArray $routes = null;

    private static function initRoutes(): void
    {
        if (self::$routes === null) {
            self::$routes = new cArray('routes', [
                'GET' => new cArray('routes/GET'),
                'POST' => new cArray('routes/POST'),
                'PUT' => new cArray('routes/PUT'),
                'DELETE' => new cArray('routes/DELETE'),
                'PATCH' => new cArray('routes/PATCH'),
                'OPTIONS' => new cArray('routes/OPTIONS'),
            ]);
        }
    }

    private static function addRoute(string $method, string $url, callable $handle): Route
    {
        self::initRoutes();
        $route = new Route($handle);
        // var_dump(self::$routes);
        self::$routes[$method][$url] = $route;
        return $route;
    }

    public static function __callStatic($name, $arguments)
    {
        self::initRoutes();
        $method = strtoupper($name);
        if (in_array($method, array_keys(self::$routes->toArray()))) {
            return self::addRoute($method, ...$arguments);
        }
        throw new \BadMethodCallException("Method $name does not exist");
    }

    public static function match(array $methods, string $url, callable $handle): Route
    {
        self::initRoutes();
        $route = new Route($handle);
        foreach ($methods as $method) {
            if (self::$routes->offsetExists($method)) {
                self::$routes[$method][$url] = $route;
            }
        }
        return $route;
    }

    public static function any(string $url, callable $handle): Route
    {
        self::initRoutes();
        $route = new Route($handle);
        foreach (self::$routes->toArray() as $method => $routes) {
            self::$routes[$method][$url] = $route;
        }
        return $route;
    }

    public static function dispatch()
    {
        self::initRoutes();
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];

        $urlPath = strtok($url, '?');
        $urlPath = strtok($urlPath, '#');

        // echo self::$routes->render();
        // var_dump(self::$routes);

        if (!self::$routes->offsetExists($method))
            self::$routes['GET']['/404']->execute();
        if (!isset(self::$routes[$method][$urlPath]))
            self::$routes['GET']['/404']->execute();
        else
            self::$routes[$method][$urlPath]->execute();
    }
}
