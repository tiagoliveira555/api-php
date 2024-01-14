<?php

namespace app\support;

class Method
{
    public static function get()
    {
        return $_SERVER['REQUEST_METHOD'] ?? '';
    }
}
