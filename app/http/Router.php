<?php

namespace app\http;

use Closure;
use Exception;

class Router
{
    private Request $request;
    private array $routes = [];

    public function __construct()
    {
        $this->request = new Request;
    }

    public function get(string $path, mixed $controller)
    {
        $this->addRoute('GET', $path, $controller);
    }
    public function post(string $path, mixed $controller)
    {
        $this->addRoute('POST', $path, $controller);
    }
    public function put(string $path, mixed $controller)
    {
        $this->addRoute('PUT', $path, $controller);
    }
    public function patch(string $path, mixed $controller)
    {
        $this->addRoute('PATCH', $path, $controller);
    }
    public function delete(string $path, mixed $controller)
    {
        $this->addRoute('DELETE', $path, $controller);
    }

    private function addRoute($method, $route, $controller)
    {
        $this->routes[$route][$method] = $controller;
    }

    public function run()
    {
        try {
            $data = $this->getRoute();

            if (empty($data)) {
                throw new Exception('route not found', 400);
            }
            if ($data['actions'] instanceof Closure) {
                return $data['actions']();
            }

            $this->getController($data);
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
                    'actions' => $route[$httpMethod],
                    'path' => $method,
                    'uri' => $uri
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

        $response = $controller->$method($this->request);

        $response->send();
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
