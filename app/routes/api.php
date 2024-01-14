<?php

use app\http\Router;

$router = new Router;

$router->get('/', ['HomeController:index']);
$router->get('/users', ['UserController:index']);
$router->post('/users', ['UserController:create']);
$router->get('/users/{id}', ['UserController:show']);
$router->get('/users/{id}/name/{name}', ['UserController:show']);
$router->post('/', ['HomeController:create']);
$router->put('/', ['HomeController:edit']);

$router->get('/teste', function () {
    echo 'teste';
});

$router->run();
