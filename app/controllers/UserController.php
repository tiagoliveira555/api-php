<?php

namespace app\controllers;

use app\http\Response;

class UserController
{
    public function index()
    {
        return new Response('USER::INDEX');
    }

    public function show()
    {
        return new Response('USER::SHOW');
    }

    public function create()
    {
        return new Response('USER::SHOW');
    }
}
