<?php

namespace app\http;

class Request
{
    private string $httpMethod = '';
    private string $uri = '';
    private array $params = [];
    private array $postVars = [];
    private array $queryParams = [];
    private array $headers = [];

    public function __construct()
    {
        $this->postVars    = $_POST ?? [];
        $this->queryParams = $_GET ?? [];
        $this->headers     = getallheaders();
        $this->uri         = $this->getUri();
        $this->httpMethod  = $this->getHttpMethod();
    }

    public function setParams($params)
    {
        $this->params = $params;
    }

    public function getParams()
    {
        return $this->params;
    }

    public function getUri()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return $uri !== '/' ? rtrim($uri, '/') : '/';
    }

    public function getHttpMethod()
    {
        return $_SERVER['REQUEST_METHOD'] ?? '';
    }
}
