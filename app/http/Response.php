<?php

namespace app\http;

class Response
{
    public function __construct(
        private mixed $body,
        private int $statusCode = 200,
        private array $headers = []
    ) {
    }

    public function send()
    {
        http_response_code($this->statusCode);

        if (!empty($this->headers)) {
            foreach ($this->headers as $key => $value) {
                header("$key : $value");
            }
        }

        echo json_encode($this->body, JSON_UNESCAPED_SLASHES);
    }
}
