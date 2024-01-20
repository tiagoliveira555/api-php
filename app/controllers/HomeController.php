<?php

namespace app\controllers;

use app\http\Response;

class HomeController
{
    public function index()
    {
        return new Response([
            'Author'   => 'Tiago Oliveira',
            'E-mail'   => 'tiagoliveira555@gmail.com',
            'Linkedin' => 'https://www.linkedin.com/in/tiagoliveira555',
            'Github'   => 'https://github.com/tiagoliveira555'
        ]);
    }
}
