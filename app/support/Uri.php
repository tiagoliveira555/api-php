<?php

namespace app\support;

class Uri
{
    public static function get()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return $uri !== '/' ? rtrim($uri, '/') : '/';
    }
}
