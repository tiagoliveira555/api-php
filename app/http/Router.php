<?php

namespace app\http;

use app\http\middlewares\Queue;

use Exception;

class Router
{
    private static array $routes = [];

    public static function get(string $path, array $actions, array $middlewares = [])
    {
        self::addRoute('GET', $path, $actions, $middlewares);
    }

    public static function post(string $path, array $actions, array $middlewares = [])
    {
        self::addRoute('POST', $path, $actions, $middlewares);
    }

    public static function put(string $path, array $actions, array $middlewares = [])
    {
        self::addRoute('PUT', $path, $actions, $middlewares);
    }

    public static function patch(string $path, array $actions, array $middlewares = [])
    {
        self::addRoute('PATCH', $path, $actions, $middlewares);
    }

    public static function delete(string $path, array $actions, array $middlewares = [])
    {
        self::addRoute('DELETE', $path, $actions, $middlewares);
    }

    private static function addRoute(string $method, string $route, array $controller, array $middlewares)
    {
        self::$routes[$route][$method] = [
            'controller' => $controller,
            'middlewares' => $middlewares
        ];
    }

    public static function run()
    {
        try {
            $data = self::getRoute();

            if (empty($data)) {
                throw new Exception('route not found', 404);
            }

            $response = self::getController($data);
            $response->send();
        } catch (Exception $e) {
            return (new Response(['error' => $e->getMessage()], $e->getCode()))->send();
        }
    }

    private static function getRoute()
    {
        $uri = uri();
        $httpMethod = httpMethod();

        foreach (self::$routes as $path => $route) {
            $pathRegex = preg_replace('/{[\w\-]+}/', '[\w\-]+', $path);
            $patternRoute = '#^' . $pathRegex . '$#';

            if (preg_match($patternRoute, $uri) && !empty($route[$httpMethod])) {
                return [
                    'path'        => $path,
                    'uri'         => $uri,
                    'controller'  => $route[$httpMethod]['controller'][0],
                    'method'      => $route[$httpMethod]['controller'][1] ?? '',
                    'middlewares' => $route[$httpMethod]['middlewares']
                ];
            }
        }
    }

    private static function getController(array $data)
    {
        $params = null;
        if (str_contains($data['path'], '{')) {
            $params = self::getParams($data['path'], $data['uri']);
        }

        return (new Queue($data['middlewares'], $data['controller'], $data['method'], $params))->next();
    }

    private static function getParams($path, $uri)
    {
        preg_match_all('/{([\w\-]+)}/', $path, $matches);
        $keys = $matches[1];

        $explodePath = explode('/', trim($path, '/'));
        $explodeUri = explode('/', trim($uri, '/'));

        $value = [];
        foreach ($explodePath as $index => $value) {
            if ($explodeUri[$index] !== $value) {
                $values[] = $explodeUri[$index];
            }
        }
        return (object)array_combine($keys, $values);
    }
}
