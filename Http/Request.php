<?php

declare(strict_types = 1);

namespace App\Http;

class Request
{
    public function __construct() {

    }

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

    public function get(string $key)
    {
        return filter_input(INPUT_GET, $key) ?? '';
    }

    public function post(string $key)
    {
        return filter_input(INPUT_POST, $key) ?? '';
    }

    public function getAndUnset(string $key)
    {
        $value = self::get($key);
        unset($_GET[$key]);

        return $value;
    }

    public function postAndUnset(string $key)
    {
        $value = self::post($key);
        unset($_POST[$key]);

        return $value;
    }
}