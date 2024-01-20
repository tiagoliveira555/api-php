<?php

namespace app\controllers;

use app\database\models\Pagination;
use app\database\models\User;
use app\http\Request;
use app\http\Response;
use app\http\validations\Validation;
use Exception;

class UserController
{
    public function __construct(
        private User $user = new User
    ) {
    }

    public function index()
    {
        $pagination = new Pagination;
        $pagination->setTotalItems($this->user->count());
        $limit = $pagination->getLimit();

        $usersPagination = $this->user->pagination($limit);

        $users = array_map(fn ($user) => $this->userWithoutPassword($user), $usersPagination);

        return new Response([
            'data'       => $users,
            'pagination' => $pagination->getPagination()
        ]);
    }

    public function show(object $params)
    {
        $user = $this->user->findBy(['id' => $params->id]);

        if (!$user) {
            throw new Exception('user not found', 404);
        }
        return new Response($this->userWithoutPassword($user), 200);
    }

    public function create(): Response
    {
        $errors = $this->userValidation();

        if ($errors) {
            return new Response(['errors' => $errors], 400);
        }

        $request = Request::only(['name', 'email', 'password']);

        $userFound = $this->user->findBy(['email' => $request->email]);

        if ($userFound) {
            throw new Exception('email already in use', 400);
        }
        $user = $this->user->insert([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => password_hash($request->password, PASSWORD_DEFAULT)
        ]);

        return new Response($this->userWithoutPassword($user), 201);
    }

    public function update(object $params): Response
    {
        $errors =  $this->userValidation();

        if ($errors) {
            return new Response($errors, 400);
        }

        $request = Request::only(['name', 'email', 'password']);

        $user = $this->user->findBy(['id' => $params->id]);

        if (!$user) {
            throw new Exception('user not found', 404);
        }

        $userByEmail = $this->user->findBy(['email' => $request->email]);

        if ($userByEmail && $userByEmail->id !== $user->id) {
            throw new Exception('email already in use', 400);
        }

        $user = $this->user->update(['id' => $user->id], [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => password_hash($request->password, PASSWORD_DEFAULT)
        ]);

        return new Response($this->userWithoutPassword($user), 200);
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

    private function userWithoutPassword(object $user)
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }

    private function userValidation()
    {
        return Validation::validate([
            'name' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'min:8', 'max:255']
        ]);
    }
}
