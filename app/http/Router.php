<?php

namespace app\http;

use app\http\middlewares\Queue;
use Exception;

class Router
{
    private Request $request;
    private array $routes = [];

    public function __construct()
    {
        $this->request = new Request;
    }

    public function get(string $path, array $actions, array $middlewares = [])
    {
        $this->addRoute('GET', $path, $actions, $middlewares);
    }

    public function post(string $path, array $actions, array $middlewares = [])
    {
        $this->addRoute('POST', $path, $actions, $middlewares);
    }

    public function put(string $path, array $actions, array $middlewares = [])
    {
        $this->addRoute('PUT', $path, $actions, $middlewares);
    }

    public function patch(string $path, array $actions, array $middlewares = [])
    {
        $this->addRoute('PATCH', $path, $actions, $middlewares);
    }

    public function delete(string $path, array $actions, array $middlewares = [])
    {
        $this->addRoute('DELETE', $path, $actions, $middlewares);
    }

    private function addRoute(string $method, string $route, array $actions, array $middlewares)
    {
        $this->routes[$route][$method] = [
            'actions' => $actions,
            'middlewares' => $middlewares
        ];
    }

    public function run()
    {
        try {
            $data = $this->getRoute();

            if (empty($data)) {
                throw new Exception('route not found', 404);
            }

            $response = $this->getController($data);
            $response->send();
        } catch (Exception $e) {
            return (new Response(['error' => $e->getMessage()], $e->getCode()))->send();
        }
    }

    private function getRoute()
    {
        $uri = $this->request->getUri();
        $httpMethod = $this->request->getHttpMethod();

        foreach ($this->routes as $method => $route) {
            $pathRegex = preg_replace('/{[\w\-]+}/', '[\w\-]+', $method);
            $patternRoute = '#^' . $pathRegex . '$#';

            if (preg_match($patternRoute, $uri) && !empty($route[$httpMethod])) {
                return [
                    'actions'     => $route[$httpMethod]['actions'],
                    'middlewares' => $route[$httpMethod]['middlewares'],
                    'path'        => $method,
                    'uri'         => $uri
                ];
            }
        }
    }

    private function getController(array $data)
    {
        $actions = implode($data['actions']);

        if (!str_contains($actions, ':')) {
            throw new Exception('actions with wrong format', 400);
        }

        [$controller, $method] = explode(':', $actions);

        $controllerNamespace = 'app\\controllers\\' . $controller;

        if (!class_exists($controllerNamespace)) {
            throw new Exception($controller . ' not exists', 400);
        }
        $controller = new $controllerNamespace;

        if (!method_exists($controller, $method)) {
            throw new Exception('method ' . $method . ' not exists in ' . $controllerNamespace, 400);
        }

        $params = [];
        if (str_contains($data['path'], '{')) {
            $params = $this->getParams($data['path'], $data['uri']);
            $this->request->setParams($params);
        }

        return (new Queue($data['middlewares'], $controller, $method))->next($this->request);
    }

    private function getParams($path, $uri)
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
        return array_combine($keys, $values);
    }
}
