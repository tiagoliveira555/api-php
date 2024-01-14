<?php

use app\http\Router;

$router = new Router;

$router->get('/', ['HomeController:index'], ['auth']);
$router->get('/users', ['UserController:index']);
$router->post('/users', ['UserController:create']);
$router->get('/users/{id}', ['UserController:show'], ['auth']);
$router->get('/users/{id}/name/{name}', ['UserController:show']);
$router->post('/', ['HomeController:create']);
$router->put('/', ['HomeController:edit']);

$router->run();
