<?php

namespace app\controllers;

use app\database\models\User;
use app\http\Request;
use app\http\Response;
use Exception;

class UserController
{
    public function __construct(
        private User $user = new User
    ) {
    }

    public function index()
    {
        $users = array_map(function ($user) {
            return [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ];
        }, $this->user->findAll());

        return new Response($users);
    }

    public function show($params)
    {
        $user = $this->user->findBy(['id' => $params->id]);

        if (!$user) {
            throw new Exception('user not found', 404);
        }

        return new Response([
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ]);
    }

    public function create(): Response
    {
        $request = Request::only(['name', 'email', 'password']);

        $userFound = $this->user->findBy(['email' => $request->email]);

        if ($userFound) {
            throw new Exception('email already in use', 404);
        }
        $userId = $this->user->insert([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => password_hash($request->password, PASSWORD_DEFAULT)
        ]);

        return new Response([
            'id'    => (int)$userId,
            'name'  => $request->name,
            'email' => $request->email,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ], 201);
    }

    public function update(object $params): Response
    {
        $request = Request::only(['name', 'email', 'password']);

        $user = $this->user->findBy(['id' => $params->id]);

        if (!$user) {
            throw new Exception('user not found', 404);
        }

        $userByEmail = $this->user->findBy(['email' => $request->email]);

        if ($userByEmail && $userByEmail->id !== $user->id) {
            throw new Exception('email already in use', 400);
        }

        $this->user->update(['id' => $user->id], [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => password_hash($request->password, PASSWORD_DEFAULT)
        ]);

        return new Response([
            'id'         => $user->id,
            'name'       => $request->name,
            'email'      => $request->email,
            'created_at' => $user->created_at,
            'updated_at' => date('Y-m-d H:i:s')
        ], 200);
    }

    public function delete(object $params): Response
    {
        $user = $this->user->findBy(['id' => $params->id]);

        if (!$user) {
            throw new Exception('user not found', 404);
        }

        $this->user->delete(['id' => $user->id]);

        return new Response('', 204);
    }
}
