<?php

namespace app\controllers;

use app\http\Response;

class HomeController
{
    public function index()
    {
        return new Response(date('d/m/Y H:i:s'));
    }
}
