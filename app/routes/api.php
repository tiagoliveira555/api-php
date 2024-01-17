<?php

use app\http\Router;
use app\controllers\UserController;
use app\controllers\HomeController;

#region User
Router::get('/users', [UserController::class, 'index']);
Router::get('/users/{id}', [UserController::class, 'show']);
Router::post('/users', [UserController::class, 'create']);
Router::put('/users/{id}', [UserController::class, 'update']);
Router::delete('/users/{id}', [UserController::class, 'delete']);
#endregion

#region Home
Router::get('/', [HomeController::class, 'index']);
Router::post('/', [HomeController::class, 'create']);
Router::put('/', [HomeController::class, 'edit']);
#endregion

Router::run();
