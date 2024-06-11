<?php

declare(strict_types = 1);

namespace App;

class Request
{

    public static function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'];
        $path = explode('?', $uri)[0];

        $path = rtrim($path, '/') ? rtrim($path, '/') : '/';
        
        return $path;
    }

    public static function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}