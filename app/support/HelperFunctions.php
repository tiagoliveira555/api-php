<?php

function uri()
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    return $uri !== '/' ? rtrim($uri, '/') : '/';
}

function httpMethod()
{
    return $_SERVER['REQUEST_METHOD'] ?? '';
}
