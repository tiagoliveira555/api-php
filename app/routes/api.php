<?php

use app\http\Router;
use app\controllers\UserController;
use app\controllers\HomeController;

#region Home
Router::get('/', [HomeController::class, 'index']);
#endregion

#region User
Router::get('/users', [UserController::class, 'index'], ['auth']);
Router::get('/users/{id}', [UserController::class, 'show'], ['auth']);
Router::post('/users', [UserController::class, 'create'], ['auth']);
Router::put('/users/{id}', [UserController::class, 'update'], ['auth']);
Router::delete('/users/{id}', [UserController::class, 'delete'], ['auth']);
#endregion

Router::run();
