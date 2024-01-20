<?php

namespace app\http\middlewares;

use app\database\models\User;
use Exception;

class AuthMiddleware
{
    public function handle($next)
    {
        $email = $_SERVER['PHP_AUTH_USER'];
        $password = $_SERVER['PHP_AUTH_PW'];

        if (empty($email) || empty($password)) {
            throw new Exception('Unauthorized', 401);
        }

        $user = (new User)->findBy(['email' => $email]);

        if (!$user || !password_verify($password, $user->password)) {
            throw new Exception('email or password invalid!', 401);
        }

        return $next();
    }
}
