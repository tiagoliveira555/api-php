<?php

namespace app\http\middlewares;

use Exception;

class Queue
{
    public static array $map = [];

    private array $middlewares = [];
    private object $controller;
    private string $method;
    private ?object $params;

    public function __construct($middlewares, $controller, $method, $params)
    {
        $this->middlewares = $middlewares;
        $this->controller = new $controller;
        $this->method = $method;
        $this->params = $params;
    }

    public static function setMat($map)
    {
        self::$map = $map;
    }

    public function next()
    {
        if (!method_exists($this->controller, $this->method)) {
            throw new Exception('method ' . $this->method . ' not exists in ' . $this->controller::class, 400);
        }

        $method = $this->method;
        if (empty($this->middlewares)) {
            return $this->controller->$method($this->params);
        }

        $middleware = array_shift($this->middlewares);

        if (empty(self::$map[$middleware])) {
            throw new Exception('problems with the request middleware', 500);
        }

        $queue = $this;
        $next = function ($request) use ($queue) {
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($next);
    }
}
