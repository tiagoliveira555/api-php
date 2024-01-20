<?php

use app\config\Environment;
use app\http\middlewares\Queue;

require_once dirname(__DIR__) . '/../vendor/autoload.php';

Environment::load(__DIR__);

Queue::setMat([
    'auth' => \app\http\middlewares\AuthMiddleware::class
]);

require_once dirname(__DIR__) . '/routes/api.php';
