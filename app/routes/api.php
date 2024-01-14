<?php

use app\controllers\HomeController;
use app\controllers\UserController;
use app\http\Router;

Router::get('/', [HomeController::class, 'index']);
Router::get('/users', [UserController::class, 'index']);
Router::post('/users', [UserController::class, 'create']);
Router::get('/users/{id}', [UserController::class, 'show']);
Router::get('/users/{id}/name/{name}', [UserController::class, 'edit']);
Router::post('/', [HomeController::class, 'create']);
Router::put('/', [HomeController::class, 'edit']);

Router::run();
