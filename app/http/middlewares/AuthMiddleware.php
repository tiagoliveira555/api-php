<?php

namespace app\http\middlewares;

use Exception;

class AuthMiddleware
{
    public function handle($request, $next)
    {
        $auth = false;

        if (!$auth) {
            throw new Exception('Unauthorized');
        }

        return $next($request);
    }
}
