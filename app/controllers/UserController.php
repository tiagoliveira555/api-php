<?php

namespace app\controllers;

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
        return new Response('USER::SHOW ' . $params['id']);
    }

    public function create()
    {
        $request = Request::only(['name', 'email']);
        return new Response([
            'name'  => $request->name,
            'email' => $request->email
        ]);
    }
}
