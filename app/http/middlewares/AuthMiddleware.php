<?php

namespace app\http\middlewares;

use Exception;

class AuthMiddleware
{
    public function handle($next)
    {
        $auth = false;

        if (!$auth) {
            throw new Exception('Unauthorized');
        }

        return $next();
    }
}
