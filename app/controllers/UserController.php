<?php

namespace app\controllers;

use app\database\models\User;
use app\http\Request;
use app\http\Response;

class UserController
{
    public function index()
    {
        return new Response([
            'query' => Request::query('page')
        ]);
    }

    public function show($params)
    {
        return new Response('USER::SHOW ' . $params->id);
    }

    public function create(): Response
    {
        $request = Request::only(['name', 'email', 'password']);

        $user = new User;
        $userId = $user->insert([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => password_hash($request->password, PASSWORD_DEFAULT)
        ]);

        return new Response([
            'id'    => (int)$userId,
            'name'  => $request->name,
            'email' => $request->email
        ], 201);
    }

    public function update(object $params): Response
    {
        $id = $params->id;
        $request = Request::only(['name', 'email', 'password']);

        $user = new User;
        $user->update(['id' => $id], [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => password_hash($request->password, PASSWORD_DEFAULT)
        ], 200);

        return new Response([
            'id'    => (int)$id,
            'name'  => $request->name,
            'email' => $request->email
        ]);
    }

    public function delete(object $params): Response
    {
        $id = $params->id;

        $user = new User;
        $user->delete(['id' => $id]);

        return new Response('', 204);
    }
}
