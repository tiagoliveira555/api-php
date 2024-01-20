<?php

namespace app\config;

class Environment
{
    public static function load(string $dir)
    {
        if (file_exists($dir . '/../../.env')) {
            $lines = file($dir . '/../../.env');
            foreach ($lines as $line) {
                putenv(trim($line));
            }
        }
    }
}
