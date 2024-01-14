<?php

namespace app\http\middlewares;

use Exception;

class Queue
{
    public static array $map = [];

    public function __construct(
        private array $middlewares = [],
        private object $controller,
        private string $method
    ) {
    }

    public static function setMat($map)
    {
        self::$map = $map;
    }

    public function next($request)
    {
        $method = $this->method;
        if (empty($this->middlewares)) {
            return $this->controller->$method();
        }

        $middleware = array_shift($this->middlewares);

        if (empty(self::$map[$middleware])) {
            throw new Exception('problems with the request middleware', 500);
        }

        $queue = $this;
        $next = function ($request) use ($queue) {
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($request, $next);
    }
}
